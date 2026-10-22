<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceIdentity;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;

class PublicAttendanceController extends BaseController
{
    /**
     * Kunci sesi yang mengikat identitas absensi (attendance identity) yang sudah
     * tervalidasi ke sesi pengguna. Data absensi HANYA dilayani dari sesi ini —
     * tidak pernah dari PIN di query/request — sehingga PIN tidak bisa dipakai
     * ulang (replay) dan tidak bocor ke HTML/history browser.
     */
    public const SESSION_KEY = 'attendance_self_service_id';

    /**
     * Maksimum percobaan PIN gagal sebelum pengguna diblokir sementara.
     */
    public const MAX_FAILED_ATTEMPTS = 10;

    /**
     * Durasi blokir sementara (menit) setelah MAX_FAILED_ATTEMPTS kegagalan.
     */
    public const LOCKOUT_MINUTES = 10;

    /**
     * Tampilkan form PIN — atau data absensi jika sesi masih terikat.
     */
    public function index()
    {
        $identity = $this->resolveSessionIdentity();

        if (!$identity) {
            // Belum ada sesi valid — hanya form PIN, tanpa data apa pun.
            return view(theme_view('attendance.public.check'));
        }

        return view(theme_view('attendance.public.check'), $this->buildData($identity));
    }

    /**
     * Validasi PIN lalu ikat identitas ke sesi (session-binding).
     *
     * Setelah PIN tervalidasi, identitas disimpan ke sesi lalu redirect ke
     * GET /absensi/saya. PIN tidak pernah dikirim balik ke view/response.
     */
    public function check(Request $request)
    {
        $request->validate([
            'pin' => 'required|string|max:20',
        ]);

        $pin = (string) $request->input('pin');
        $failKey = self::pinFailCacheKey($pin);

        // Lockout aktif untuk PIN ini? Tolak tanpa membocorkan apa pun.
        if (Cache::get(self::lockoutCacheKey($pin))) {
            return view(theme_view('attendance.public.check'), [
                'error' => self::lockoutMessage(),
            ]);
        }

        // Cari identitas berdasarkan PIN (siswa, guru, atau user).
        $identity = AttendanceIdentity::query()
            ->where('device_pin', $pin)
            ->where('is_active', true)
            ->first();

        if (!$identity) {
            // Hitung kegagalan per PIN. Key di-hash agar PIN tidak tersimpan
            // plaintext di cache store (production memakai CACHE_STORE=database).
            // Cache::add hanya menyetel TTL pada percobaan pertama.
            Cache::add($failKey, 0, now()->addMinutes(self::LOCKOUT_MINUTES));
            $failures = (int) Cache::increment($failKey);

            if ($failures >= self::MAX_FAILED_ATTEMPTS) {
                // Blokir sementara — request berikutnya ditolak sebelum PIN dicek.
                Cache::put(
                    self::lockoutCacheKey($pin),
                    true,
                    now()->addMinutes(self::LOCKOUT_MINUTES)
                );
                Cache::forget($failKey);

                return view(theme_view('attendance.public.check'), [
                    'error' => self::lockoutMessage(),
                ]);
            }

            // Pesan generik — tidak mengungkap apakah PIN pernah terdaftar.
            return view(theme_view('attendance.public.check'), [
                'error' => 'PIN tidak ditemukan.',
            ]);
        }

        // PIN valid: reset counter kegagalan lalu ikat identitas ke sesi.
        Cache::forget($failKey);
        Cache::forget(self::lockoutCacheKey($pin));

        $request->session()->put(self::SESSION_KEY, $identity->id);

        return redirect()->route('public.attendance.index');
    }

    /**
     * Putuskan sesi self-service ("Ganti PIN" / keluar).
     */
    public function logout(Request $request)
    {
        $request->session()->forget(self::SESSION_KEY);

        return redirect()->route('public.attendance.index');
    }

    /**
     * Ambil identitas yang terikat pada sesi (jika masih ada & aktif).
     */
    private function resolveSessionIdentity(): ?AttendanceIdentity
    {
        $id = session(self::SESSION_KEY);

        if (!$id) {
            return null;
        }

        $identity = AttendanceIdentity::query()
            ->where('is_active', true)
            ->find($id);

        if (!$identity) {
            // Identitas sudah dihapus/nonaktif — buang sesi agar tidak basi.
            session()->forget(self::SESSION_KEY);

            return null;
        }

        return $identity;
    }

    /**
     * Susun data tampilan dari identitas sesi (tanpa PIN).
     */
    private function buildData(AttendanceIdentity $identity): array
    {
        $identity->load(['guru', 'siswa', 'user']);

        // Resolve nama berdasarkan jenis identitas.
        $name = match (true) {
            $identity->guru !== null => $identity->guru->nama_lengkap,
            $identity->siswa !== null => $identity->siswa->nama_lengkap,
            $identity->user !== null => $identity->user->name,
            default => '-',
        };

        // Ambil absensi 30 hari terakhir.
        $attendances = Attendance::query()
            ->where('attendance_identity_id', $identity->id)
            ->whereDate('date', '>=', now()->subDays(30))
            ->orderByDesc('date')
            ->orderByDesc('first_in_at')
            ->get();

        return [
            'identity' => $identity,
            'name' => $name,
            'attendances' => $attendances,
        ];
    }

    /**
     * Kunci cache counter kegagalan per PIN (di-hash — PIN asli tidak disimpan).
     */
    public static function pinFailCacheKey(string $pin): string
    {
        return 'attendance_pin_fail:'.hash('sha256', $pin);
    }

    /**
     * Kunci cache blokir sementara per PIN (di-hash — PIN asli tidak disimpan).
     */
    public static function lockoutCacheKey(string $pin): string
    {
        return 'attendance_pin_lock:'.hash('sha256', $pin);
    }

    /**
     * Pesan saat pengguna diblokir sementara.
     */
    public static function lockoutMessage(): string
    {
        return 'Terlalu banyak percobaan PIN yang gagal. Coba lagi dalam '.self::LOCKOUT_MINUTES.' menit.';
    }
}
