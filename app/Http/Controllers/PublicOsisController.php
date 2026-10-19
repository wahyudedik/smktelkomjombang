<?php

namespace App\Http\Controllers;

use App\Models\Calon;
use App\Models\OsisElection;
use App\Models\Pemilih;
use App\Models\Siswa;
use App\Models\Voting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Public E-OSIS Voting — pemungutan suara publik TANPA login.
 *
 * Alur: landing (form verifikasi) → verify (resolve Pemilih by NIS/NISN/NIP)
 * → vote (kartu kandidat, mirror tampilan admin) → thanks.
 *
 * Keputusan desain:
 * - Identifier SISWA: NIS atau NISN (satu field, dicari di pemilihs.nis OR
 *   pemilihs.nisn, user_type 'siswa' atau null untuk import manual).
 * - Identifier GURU: NIP — generatePemilihFromUsers() menyimpan NIP guru ke
 *   kolom pemilihs.nis dengan user_type='guru', jadi lookup cukup
 *   pemilihs.nis + user_type='guru' (tanpa join ke tabel gurus).
 * - Vote publik: SISWA = 1 pasangan calon (radio single-select);
 *   GURU = multi-select 1–2 pasangan calon DISTINCT (checkbox, mirror
 *   path login di OSISController::processVote()) → 1–2 baris Voting
 *   dalam satu transaksi.
 * - Anti-fraud: throttle osis-public (10 req/menit/IP) + session verifikasi
 *   wajib + DB::transaction + lockForUpdate pemilihs + double-check
 *   (status + Voting exists) + gender/allowed_classes enforcement server-side
 *   + logging IP/user-agent + cache invalidation.
 * - Path pemilihan selalu lewat pemilihs (pemilih_id) — konsisten untuk siswa
 *   & guru; siswa yang punya row siswas ter-link juga di-update marker
 *   dual-tracking-nya (siswas.has_voted_osis) dalam transaksi yang sama.
 */
class PublicOsisController extends Controller
{
    /**
     * Kunci session yang menyimpan hasil verifikasi identitas pemilih publik.
     */
    private const SESSION_KEY = 'public_osis_voter';

    /**
     * Pesan error umum — sengaja tidak detail agar tidak membocorkan
     * apakah identifier yang dimasukkan valid/tidak.
     */
    private const GENERIC_NOT_FOUND = 'Data tidak ditemukan atau tidak berhak memilih.';

    /**
     * Landing page pemilihan publik — form verifikasi identitas.
     */
    public function index(): View
    {
        return view('osis.public.landing', [
            'election' => OsisElection::active()->first(),
        ]);
    }

    /**
     * Verifikasi identifier (NIS/NISN untuk siswa, NIP untuk guru) dan
     * resolve Pemilih. Jika valid & belum memilih → set session verifikasi
     * lalu redirect ke halaman vote.
     */
    public function verify(Request $request): RedirectResponse
    {
        $election = OsisElection::active()->first();
        if (!$election) {
            return redirect()->route('osis.public.landing')
                ->with('error', 'Pemilihan OSIS belum dimulai atau sudah berakhir.');
        }

        $validated = $request->validate([
            'tipe' => ['required', Rule::in(['siswa', 'guru'])],
            'identifier' => ['required', 'string', 'max:32'],
        ], [
            'tipe.required' => 'Pilih tipe pemilih (Siswa atau Guru).',
            'tipe.in' => 'Tipe pemilih tidak valid.',
            'identifier.required' => 'Nomor identitas wajib diisi.',
            'identifier.max' => 'Nomor identitas terlalu panjang (maksimal 32 karakter).',
        ]);

        $identifier = trim((string) $validated['identifier']);
        $isGuru = $validated['tipe'] === 'guru';

        // Resolve pemilih: NIS atau NISN (satu field input publik).
        // Siswa: user_type 'siswa' ATAU null (pemilih hasil import manual tanpa user_type).
        $pemilih = Pemilih::active()
            ->where(function ($query) use ($identifier) {
                $query->where('nis', $identifier)
                    ->orWhere('nisn', $identifier);
            })
            ->where(function ($query) use ($isGuru) {
                if ($isGuru) {
                    // Guru: NIP disimpan di kolom `nis` oleh generatePemilihFromUsers()
                    $query->where('user_type', 'guru');
                } else {
                    $query->where('user_type', 'siswa')->orWhereNull('user_type');
                }
            })
            ->first();

        if (!$pemilih) {
            // Pesan umum — jangan bocorkan apakah identifier valid/tidak
            return redirect()->route('osis.public.landing')
                ->with('error', self::GENERIC_NOT_FOUND);
        }

        // Defense in depth: kelas siswa harus diizinkan election (guru tidak dibatasi)
        if (!$isGuru
            && $election->allowed_classes
            && !in_array($pemilih->kelas, $election->allowed_classes)) {
            return redirect()->route('osis.public.landing')
                ->with('error', 'Kelas Anda tidak diizinkan untuk memilih dalam pemilihan ini.');
        }

        // Sudah memilih → halaman terima kasih mode "sudah memilih"
        // (tanpa membocorkan pilihan yang sudah diberikan)
        if ($pemilih->hasVoted()) {
            $request->session()->forget(self::SESSION_KEY);

            return redirect()->route('osis.public.thanks')
                ->with('already_voted', true);
        }

        $request->session()->put(self::SESSION_KEY, [
            'pemilih_id' => $pemilih->id,
            'election_id' => $election->id,
            'nama' => $pemilih->nama,
            'label' => $isGuru ? 'Guru' : ($pemilih->kelas ?: 'Siswa'),
            'user_type' => $isGuru ? 'guru' : 'siswa',
            'jenis_kelamin' => $pemilih->jenis_kelamin,
        ]);

        return redirect()->route('osis.public.vote');
    }

    /**
     * Halaman vote publik — kartu kandidat (mirror tampilan voting admin).
     */
    public function vote(): View|RedirectResponse
    {
        $voter = session(self::SESSION_KEY);
        if (!$voter) {
            return redirect()->route('osis.public.landing')
                ->with('error', 'Silakan verifikasi identitas Anda terlebih dahulu.');
        }

        $election = OsisElection::active()->find($voter['election_id'] ?? null);
        if (!$election) {
            session()->forget(self::SESSION_KEY);

            return redirect()->route('osis.public.landing')
                ->with('error', 'Pemilihan OSIS tidak tersedia saat ini.');
        }

        $pemilih = Pemilih::active()->find($voter['pemilih_id'] ?? null);
        if (!$pemilih) {
            session()->forget(self::SESSION_KEY);

            return redirect()->route('osis.public.landing')
                ->with('error', self::GENERIC_NOT_FOUND);
        }

        if ($pemilih->hasVoted()) {
            session()->forget(self::SESSION_KEY);

            return redirect()->route('osis.public.thanks')->with('already_voted', true);
        }

        $isGuru = $pemilih->user_type === 'guru';

        // Kelas siswa harus diizinkan election (konsisten dengan verify())
        if (!$isGuru
            && $election->allowed_classes
            && !in_array($pemilih->kelas, $election->allowed_classes)) {
            session()->forget(self::SESSION_KEY);

            return redirect()->route('osis.public.landing')
                ->with('error', 'Kelas Anda tidak diizinkan untuk memilih dalam pemilihan ini.');
        }

        // Aturan bisnis filter gender kandidat (mirror path login):
        // - Guru → melihat SEMUA kandidat aktif
        // - Siswa L → hanya kandidat L; Siswi P → hanya kandidat P
        // - Fallback aman: jenis_kelamin null/kosong → SEMUA kandidat
        $showAll = true;
        $genderLabel = null;
        $gender = $pemilih->jenis_kelamin;

        if (!$isGuru && ($gender === 'L' || $gender === 'P')) {
            $calons = Calon::active()->ordered()->byGender($gender)->get();
            $showAll = false;
            $genderLabel = $gender === 'L' ? 'Laki-laki' : 'Perempuan';
        } else {
            $calons = Calon::active()->ordered()->get();
        }

        return view('osis.public.vote', compact(
            'calons',
            'election',
            'pemilih',
            'isGuru',
            'showAll',
            'genderLabel'
        ));
    }

    /**
     * Proses vote publik — anti-fraud berlapis.
     */
    public function processVote(Request $request): RedirectResponse
    {
        $voter = session(self::SESSION_KEY);
        if (!$voter) {
            // Vote POST tanpa verifikasi → tolak (cegah submit langsung)
            return redirect()->route('osis.public.landing')
                ->with('error', 'Sesi verifikasi tidak ditemukan. Silakan verifikasi identitas Anda kembali.');
        }

        $election = OsisElection::active()->find($voter['election_id'] ?? null);
        if (!$election) {
            session()->forget(self::SESSION_KEY);

            return redirect()->route('osis.public.landing')
                ->with('error', 'Pemilihan OSIS tidak tersedia saat ini.');
        }

        $pemilih = Pemilih::active()->find($voter['pemilih_id'] ?? null);
        if (!$pemilih) {
            session()->forget(self::SESSION_KEY);

            return redirect()->route('osis.public.landing')
                ->with('error', self::GENERIC_NOT_FOUND);
        }

        $isGuru = $pemilih->user_type === 'guru';

        // Aturan bisnis per role (mirror processVote() path login):
        // - GURU: `calon_ids` array 1–2 pasangan calon DISTINCT → 1–2 baris Voting.
        // - SISWA: satu `calon_id` tunggal (radio) → 1 baris Voting (semantik tidak berubah).
        if ($isGuru) {
            // Backward compatibility: form lama/cache browser mengirim calon_id tunggal
            // dari user guru → perlakukan sebagai array 1 elemen.
            if (!$request->filled('calon_ids') && $request->filled('calon_id')) {
                $request->merge(['calon_ids' => array_filter([$request->input('calon_id')])]);
            }

            // Validasi multi-select guru: 1–2 pasangan calon, tiap elemen integer,
            // DISTINCT (tolak calon sama dipilih 2 kali), dan calon AKTIF (scope active).
            $request->validate([
                'calon_ids' => ['required', 'array', 'min:1', 'max:2'],
                'calon_ids.*' => [
                    'integer',
                    'distinct',
                    Rule::exists('calons', 'id')->where('is_active', true),
                ],
            ], [
                'calon_ids.required' => 'Silakan pilih minimal satu pasangan calon terlebih dahulu.',
                'calon_ids.min' => 'Silakan pilih minimal satu pasangan calon terlebih dahulu.',
                'calon_ids.max' => 'Guru dapat memilih maksimal 2 pasangan calon dalam satu pemungutan suara.',
                'calon_ids.*.distinct' => 'Setiap pasangan calon yang dipilih harus berbeda.',
                'calon_ids.*.exists' => 'Calon yang dipilih tidak ditemukan atau tidak aktif.',
            ]);

            $calonIds = collect($request->input('calon_ids'))
                ->map(fn ($id): int => (int) $id)
                ->unique()
                ->values()
                ->all();

            // Resolve kandidat terpilih (guru bebas memilih laki-laki & perempuan)
            $calons = Calon::active()->whereIn('id', $calonIds)->get();

            if ($calons->count() !== count($calonIds)) {
                // Defense in depth di luar validasi (race: calon dinonaktifkan antar request)
                return redirect()->route('osis.public.vote')
                    ->with('error', 'Calon yang dipilih tidak ditemukan atau tidak aktif.');
            }
        } else {
            // Siswa: satu calon_id tunggal (radio) — semantik TIDAK berubah
            $request->validate([
                'calon_id' => ['required', 'integer', Rule::exists('calons', 'id')->where('is_active', true)],
            ], [
                'calon_id.required' => 'Silakan pilih satu pasangan calon terlebih dahulu.',
                'calon_id.exists' => 'Calon yang dipilih tidak ditemukan atau tidak aktif.',
            ]);

            $calon = Calon::active()->find((int) $request->input('calon_id'));

            if (!$calon) {
                return redirect()->route('osis.public.vote')
                    ->with('error', 'Calon yang dipilih tidak ditemukan atau tidak aktif.');
            }

            $calons = collect([$calon]);
        }

        // Defense in depth: allowed_classes dicek ulang di POST
        // (bukan hanya di GET vote/verify)
        if (!$isGuru
            && $election->allowed_classes
            && !in_array($pemilih->kelas, $election->allowed_classes)) {
            session()->forget(self::SESSION_KEY);

            return redirect()->route('osis.public.landing')
                ->with('error', 'Kelas Anda tidak diizinkan untuk memilih dalam pemilihan ini.');
        }

        // Server-side gender enforcement (mencegah manipulasi request di luar UI):
        // siswa hanya boleh memilih kandidat segender. Tolak hanya jika keduanya
        // non-null dan berbeda; gender null → vote tetap diterima (konsisten path login).
        // Guru: TIDAK ada pembatasan gender.
        if (!$isGuru) {
            $genderMismatch = $calons->contains(function ($calon) use ($pemilih) {
                return $calon->jenis_kelamin
                    && $pemilih->jenis_kelamin
                    && $calon->jenis_kelamin !== $pemilih->jenis_kelamin;
            });

            if ($genderMismatch) {
                return redirect()->route('osis.public.vote')
                    ->with('error', 'Anda hanya dapat memilih calon yang sesuai dengan jenis kelamin Anda.');
            }
        }

        // Pola anti-fraud yang sama dengan processVote() path login:
        // transaksi + lockForUpdate row pemilihs + double-check sudah memilih
        $voteRecorded = DB::transaction(function () use ($request, $pemilih, $calons, $election, $isGuru) {
            // Lock the pemilih row so concurrent requests queue up instead of racing
            $lockedPemilih = Pemilih::whereKey($pemilih->id)->lockForUpdate()->first();

            // Double-check inside the transaction (another request may have voted first)
            if (!$lockedPemilih || !$lockedPemilih->is_active || $lockedPemilih->hasVoted()) {
                return false;
            }

            // Also check for an existing valid voting record (belt & suspenders)
            $alreadyVoted = Voting::where('pemilih_id', $lockedPemilih->id)
                ->where('election_id', $election->id)
                ->where('is_valid', true)
                ->exists();

            if ($alreadyVoted) {
                // Sync the pemilih flag if it diverged from the voting table
                $lockedPemilih->markAsVoted($request->ip(), $request->userAgent());
                return false;
            }

            // Catat vote via pemilih_id (path publik seragam siswa & guru);
            // siswa_id null → unique constraint votings_siswa_election tidak terpengaruh.
            // Guru: insert 1–2 baris (satu per kandidat terpilih) dalam SATU transaksi.
            foreach ($calons as $calon) {
                Voting::create([
                    'calon_id' => $calon->id,
                    'pemilih_id' => $lockedPemilih->id,
                    'siswa_id' => null,
                    'election_id' => $election->id,
                    'waktu_voting' => now(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'is_valid' => true,
                ]);
            }

            // Dual-tracking: update marker row `siswas` (via user_id) dalam
            // transaksi yang sama agar status path admin & publik tidak divergen
            if (!$isGuru && $lockedPemilih->user_id) {
                Siswa::where('user_id', $lockedPemilih->user_id)
                    ->get()
                    ->each(function (Siswa $siswa) use ($request) {
                        if (!$siswa->hasVotedOsis()) {
                            $siswa->markAsVoted($request->ip(), $request->userAgent());
                        }
                    });
            }

            // Marker utama: tandai pemilih sudah memilih
            $lockedPemilih->markAsVoted($request->ip(), $request->userAgent());

            return true;
        });

        if (!$voteRecorded) {
            // Race condition: request lain mencatat vote lebih dulu
            session()->forget(self::SESSION_KEY);

            return redirect()->route('osis.public.thanks')->with('already_voted', true);
        }

        // Invalidate cached dashboard stats so the new vote is reflected
        cache()->forget('osis_dashboard_stats');

        // Sesi voting dihapus — satu akun hanya bisa vote sekali per sesi
        session()->forget(self::SESSION_KEY);

        return redirect()->route('osis.public.thanks')
            ->with('voted', true)
            ->with('show_results', $election->resultsVisible());
    }

    /**
     * Halaman terima kasih (setelah vote sukses) atau "sudah memilih".
     */
    public function thanks(): View|RedirectResponse
    {
        $voted = (bool) session('voted');
        $alreadyVoted = (bool) session('already_voted');

        // Akses langsung tanpa alur vote → redirect ke landing
        if (!$voted && !$alreadyVoted) {
            return redirect()->route('osis.public.landing');
        }

        return view('osis.public.thanks', [
            'voted' => $voted,
            'alreadyVoted' => $alreadyVoted,
            'showResults' => (bool) session('show_results'),
        ]);
    }

    /**
     * Hasil pemilihan publik — hanya tampilkan perolehan per calon jika
     * election resultsVisible() (ended/locked atau show_results=true).
     * Selalu tampilkan ringkasan partisipasi anonim.
     */
    public function results(): View
    {
        // Election aktif, atau election terakhir sebagai fallback (pola results() admin)
        $election = OsisElection::active()->first() ?? OsisElection::query()->latest()->first();

        $totalPemilih = Pemilih::active()->count();
        $sudahMemilih = Pemilih::sudahMemilih()->count();
        $belumMemilih = Pemilih::belumMemilih()->count();

        $resultsHidden = false;
        $calons = collect();
        $totalVotes = 0;

        if ($election) {
            $resultsHidden = !$election->resultsVisible();

            if ($resultsHidden) {
                // Total vote tetap dihitung per-election untuk persentase partisipasi,
                // tapi TANPA rincian per calon
                $totalVotes = Voting::where('election_id', $election->id)->valid()->count();
            } else {
                $electionId = $election->id;

                $calons = Calon::active()
                    ->ordered()
                    ->withCount(['votings' => function ($query) use ($electionId) {
                        $query->where('election_id', $electionId)->valid();
                    }])
                    ->orderByDesc('votings_count')
                    ->get();

                $totalVotes = Voting::where('election_id', $electionId)->valid()->count();
            }
        }

        $votingPercentage = $totalPemilih > 0 ? round(($totalVotes / $totalPemilih) * 100, 2) : 0;

        return view('osis.public.results', compact(
            'election',
            'resultsHidden',
            'calons',
            'totalVotes',
            'totalPemilih',
            'sudahMemilih',
            'belumMemilih',
            'votingPercentage'
        ));
    }
}
