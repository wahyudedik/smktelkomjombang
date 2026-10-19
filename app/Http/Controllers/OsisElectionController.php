<?php

namespace App\Http\Controllers;

use App\Models\OsisElection;
use App\Models\Pemilih;
use App\Models\Siswa;
use App\Models\Voting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * UI CRUD pemilihan OSIS (elections).
 *
 * Sebelumnya election hanya bisa dibuat/dikelola via CLI `osis:manage`.
 * Controller ini menyediakan UI lengkap: create, edit, activate, close,
 * delete, dan reset votes — dengan guard keamanan:
 * - Hanya 1 election aktif (activate menonaktifkan lain dalam transaksi)
 * - Delete ditolak jika election sudah punya votes (harus reset dulu)
 * - Reset votes hanya untuk election locked/ended + konfirmasi teks judul
 */
class OsisElectionController extends Controller
{
    /**
     * Daftar kelas tersedia untuk group checkbox allowed_classes.
     *
     * Sumber sama dengan OSISController::getAvailableClasses() —
     * dipakai form create/edit election.
     *
     * @return array<int, string>
     */
    public static function availableClasses(): array
    {
        return [
            'X IPA 1',
            'X IPA 2',
            'X IPA 3',
            'X IPS 1',
            'X IPS 2',
            'X IPS 3',
            'XI IPA 1',
            'XI IPA 2',
            'XI IPA 3',
            'XI IPS 1',
            'XI IPS 2',
            'XI IPS 3',
            'XII IPA 1',
            'XII IPA 2',
            'XII IPA 3',
            'XII IPS 1',
            'XII IPS 2',
            'XII IPS 3',
        ];
    }

    /**
     * Daftar semua election (terbaru di atas) dengan jumlah vote valid.
     */
    public function index()
    {
        $elections = OsisElection::withCount(['votes' => function ($query) {
            $query->valid();
        }])
            ->latest()
            ->paginate(15);

        return view('osis.elections.index', compact('elections'));
    }

    /**
     * Form create election baru.
     */
    public function create()
    {
        $availableClasses = self::availableClasses();

        return view('osis.elections.create', compact('availableClasses'));
    }

    /**
     * Simpan election baru.
     */
    public function store(Request $request)
    {
        $data = $this->validateElection($request);

        $election = DB::transaction(function () use ($data) {
            // Invariant: hanya 1 election aktif — nonaktifkan election lain
            // (pola sama dengan CLI handleCreate) agar OsisElection::active()->first()
            // di voting flow tidak ambigu.
            if ($data['is_active']) {
                OsisElection::query()
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }

            return OsisElection::create($data);
        });

        cache()->forget('osis_dashboard_stats');

        Log::info('OSIS election dibuat via UI', [
            'election_id' => $election->id,
            'title' => $election->title,
            'is_active' => $election->is_active,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.osis.elections.index')
            ->with('success', "Election \"{$election->title}\" berhasil dibuat.");
    }

    /**
     * Form edit election.
     */
    public function edit(OsisElection $election)
    {
        $availableClasses = self::availableClasses();
        $selectedClasses = $election->allowed_classes ?? [];

        // Kelas custom (tidak ada di daftar standar) tetap harus tampil tercentang
        $availableClasses = array_values(array_unique(array_merge($availableClasses, $selectedClasses)));

        return view('osis.elections.edit', compact('election', 'availableClasses', 'selectedClasses'));
    }

    /**
     * Update election.
     */
    public function update(Request $request, OsisElection $election)
    {
        $data = $this->validateElection($request, $election->id);

        DB::transaction(function () use ($election, $data) {
            // Aktivasi via form edit → nonaktifkan election lain (pola sama dengan store)
            if ($data['is_active'] && !$election->is_active) {
                OsisElection::query()
                    ->where('id', '!=', $election->id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }

            $election->update($data);
        });

        cache()->forget('osis_dashboard_stats');

        Log::info('OSIS election diperbarui via UI', [
            'election_id' => $election->id,
            'title' => $election->title,
            'is_active' => $election->is_active,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.osis.elections.index')
            ->with('success', "Election \"{$election->title}\" berhasil diperbarui.");
    }

    /**
     * Hapus election — ditolak jika sudah ada votes (data tidak boleh hilang diam-diam).
     */
    public function destroy(OsisElection $election)
    {
        if ($election->votes()->exists()) {
            return redirect()->route('admin.osis.elections.index')
                ->with('error', "Election \"{$election->title}\" memiliki "
                    . "{$election->votes()->count()} vote dan tidak dapat dihapus. "
                    . 'Gunakan tombol "Reset Votes" terlebih dahulu, lalu hapus kembali.');
        }

        $title = $election->title;
        $election->delete();

        cache()->forget('osis_dashboard_stats');

        Log::info('OSIS election dihapus via UI', [
            'title' => $title,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.osis.elections.index')
            ->with('success', "Election \"{$title}\" berhasil dihapus.");
    }

    /**
     * Jadikan election aktif (nonaktifkan election lain dalam transaksi).
     */
    public function activate(OsisElection $election)
    {
        if ($election->is_locked) {
            return redirect()->route('admin.osis.elections.index')
                ->with('error', 'Election terkunci tidak dapat diaktifkan kembali. Buat election baru sebagai gantinya.');
        }

        if ($election->hasEnded()) {
            return redirect()->route('admin.osis.elections.index')
                ->with('error', 'Election yang sudah berakhir tidak dapat diaktifkan kembali. Perpanjang tanggal berakhir terlebih dahulu.');
        }

        DB::transaction(function () use ($election) {
            OsisElection::query()
                ->where('id', '!=', $election->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            $election->update(['is_active' => true]);
        });

        cache()->forget('osis_dashboard_stats');

        Log::info('OSIS election diaktifkan via UI', [
            'election_id' => $election->id,
            'title' => $election->title,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.osis.elections.index')
            ->with('success', "Election \"{$election->title}\" kini menjadi pemilihan aktif.");
    }

    /**
     * Tutup & kunci election (is_active=false, is_locked=true) — voting berhenti menerima suara.
     */
    public function close(OsisElection $election)
    {
        $election->update([
            'is_active' => false,
            'is_locked' => true,
        ]);

        cache()->forget('osis_dashboard_stats');

        Log::info('OSIS election ditutup via UI', [
            'election_id' => $election->id,
            'title' => $election->title,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.osis.elections.index')
            ->with('success', "Election \"{$election->title}\" berhasil ditutup dan dikunci.");
    }

    /**
     * Reset semua votes di election tertentu.
     *
     * Guard keamanan (paling aman):
     * - Election yang SEDANG berjalan TIDAK boleh di-reset → tutup dulu
     *   (mencegah reset liar di tengah pemungutan suara).
     * - Konfirmasi teks wajib: client harus mengirim `confirm_title` = judul election.
     *
     * Dalam satu transaksi: hapus Voting election ini, reset pemilihs terkait →
     * belum_memilih, reset siswas terkait → has_voted_osis=false.
     */
    public function resetVotes(Request $request, OsisElection $election)
    {
        if (!$election->is_locked && !$election->hasEnded()) {
            return redirect()->route('admin.osis.elections.index')
                ->with('error', 'Pemilihan masih berjalan. Tutup pemilihan terlebih dahulu sebelum mereset suara.');
        }

        if ((string) $request->input('confirm_title') !== $election->title) {
            return redirect()->route('admin.osis.elections.index')
                ->with('error', 'Konfirmasi gagal: judul election yang diketik tidak sesuai. Reset dibatalkan.');
        }

        [$deletedVotes, $resetPemilihs, $resetSiswas] = DB::transaction(function () use ($election): array {
            // Kumpulkan ID terpengaruh SEBELUM delete
            $affectedPemilihIds = Voting::where('election_id', $election->id)
                ->whereNotNull('pemilih_id')
                ->pluck('pemilih_id')
                ->unique();

            $affectedSiswaIds = Voting::where('election_id', $election->id)
                ->whereNotNull('siswa_id')
                ->pluck('siswa_id')
                ->unique();

            $deletedVotes = Voting::where('election_id', $election->id)->delete();

            // Reset flag one-vote pemilihs yang memilih di election ini saja (scoped, bukan global)
            $resetPemilihs = 0;
            if ($affectedPemilihIds->isNotEmpty()) {
                $resetPemilihs = Pemilih::whereIn('id', $affectedPemilihIds)
                    ->where('status', 'sudah_memilih')
                    ->update([
                        'status' => 'belum_memilih',
                        'waktu_memilih' => null,
                        'ip_address' => null,
                        'user_agent' => null,
                    ]);
            }

            // Idem untuk siswas.has_voted_osis (marker voting jalur siswa)
            $resetSiswas = 0;
            if ($affectedSiswaIds->isNotEmpty()) {
                $resetSiswas = Siswa::whereIn('id', $affectedSiswaIds)
                    ->where('has_voted_osis', true)
                    ->update([
                        'has_voted_osis' => false,
                        'voted_at' => null,
                        'voting_ip' => null,
                        'voting_user_agent' => null,
                    ]);
            }

            return [$deletedVotes, $resetPemilihs, $resetSiswas];
        });

        cache()->forget('osis_dashboard_stats');

        Log::info('OSIS election votes di-reset via UI', [
            'election_id' => $election->id,
            'title' => $election->title,
            'deleted_votes' => $deletedVotes,
            'reset_pemilihs' => $resetPemilihs,
            'reset_siswas' => $resetSiswas,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.osis.elections.index')
            ->with('success', "Reset selesai: {$deletedVotes} vote dihapus, "
                . "{$resetPemilihs} pemilih & {$resetSiswas} siswa dikembalikan ke status belum memilih.");
    }

    /**
     * Validasi form create/update election.
     *
     * allowed_classes dikombinasikan dari checkbox (allowed_classes[]) dan
     * input teks custom (custom_classes, pisah koma). Kosongkan keduanya = semua kelas.
     *
     * @return array<string, mixed>
     */
    private function validateElection(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'max_votes_per_student' => ['required', 'integer', 'min:1', 'max:10'],
            'allowed_classes' => ['nullable', 'array'],
            'allowed_classes.*' => ['string', 'max:100'],
            'custom_classes' => ['nullable', 'string', 'max:1000'],
            'show_results' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'end_date.after' => 'Tanggal berakhir harus setelah tanggal mulai.',
        ]);

        // Merge checkbox + kelas custom (pisah koma), buang duplikat & kosong
        $customClasses = array_filter(array_map('trim', explode(',', (string) ($data['custom_classes'] ?? ''))));
        $classes = collect($data['allowed_classes'] ?? [])
            ->merge($customClasses)
            ->unique()
            ->values()
            ->all();

        unset($data['custom_classes']);
        $data['allowed_classes'] = $classes === [] ? null : $classes;
        $data['show_results'] = (bool) ($data['show_results'] ?? true);
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        return $data;
    }
}
