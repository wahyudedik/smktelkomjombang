<?php

namespace App\Console\Commands;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

/**
 * Command linking massal `siswas.user_id` → users (role siswa).
 *
 * Konteks (bug production): vote OSIS siswa tidak tersimpan karena
 * OSISController::processVote() resolve Siswa::where('user_id', $user->id)
 * → null untuk akun siswa yang baris `siswas`-nya belum ter-link, dan TIDAK
 * ada proses aplikasi yang mengisi `siswas.user_id` secara massal.
 *
 * Match key (berdasarkan schema NYATA, bukan asumsi):
 * - `siswas.email` (nullable — migration 2025_09_26_093230_create_siswas_table.php)
 *   ↔ `users.email` (unique — migration 0001_01_01_000000_create_users_table.php),
 *   case-insensitive.
 * - Kandidat HANYA user dengan role Spatie 'siswa'.
 * - Jalur NIS TIDAK dipakai: tabel `users` TIDAK punya kolom NIS dan
 *   `users.name` adalah nama orang (UserFactory: fake()->name()), bukan NIS —
 *   tidak ada key NIS yang valid ke tabel users. `pemilihs.nis` ↔ `siswas.nis`
 *   sengaja TIDAK dipakai: korespondensi antar tabel tidak terverifikasi →
 *   risiko link salah (anti-fraud).
 *
 * Aturan anti-fraud (WAJIB):
 * - UPDATE ONLY — tidak pernah INSERT/CREATE baris `siswas`.
 * - Hanya memproses siswa dengan `user_id IS NULL` (yang sudah ter-link di-skip,
 *   dihitung "sudah ter-link", tidak disentuh).
 * - Skip + catat: (a) 0 match, (b) >1 match ambigu (TIDAK memilih acak —
 *   laporkan id siswa + id user kandidat), (c) konflik (kandidat user sudah
 *   ter-link ke siswa lain via user_id).
 * - Transaksi PER SISWA (bukan satu transaksi raksasa) + guard `whereNull`
 *   di dalam transaksi agar aman terhadap race.
 *
 * Usage: php artisan osis:link-siswa-users
 */
class LinkSiswaUsers extends Command
{
    protected $signature = 'osis:link-siswa-users';

    protected $description = 'Link siswas.user_id ke users (role siswa) via match email — UPDATE only, anti-fraud (tanpa INSERT siswas baru)';

    /** Batas contoh detail per kategori skip di output agar tidak meledak. */
    private const MAX_EXAMPLE_LINES = 20;

    public function handle(): int
    {
        $this->info('=== Linking siswas.user_id → users (role siswa) ===');
        $this->newLine();
        $this->line('Match key : email (siswas.email = users.email, case-insensitive)');
        $this->line('Kandidat  : users dengan role "siswa" (Spatie)');
        $this->line('Aturan    : UPDATE ONLY — tidak membuat baris siswas baru');
        $this->newLine();

        $alreadyLinked = Siswa::whereNotNull('user_id')->count();

        // Map kandidat user role siswa per-email lowercase (sekali query, tanpa N+1).
        // Role 'siswa' mungkin belum ada di DB (mis. instalasi baru) → kandidat kosong,
        // semua siswa jatuh ke kategori "tidak ada match" (exit 0, bukan error crash).
        $candidatesByEmail = [];
        $roleSiswaExists = Role::where('name', 'siswa')->exists();

        if ($roleSiswaExists) {
            User::role('siswa')->pluck('id', 'email')->each(function ($id, $email) use (&$candidatesByEmail): void {
                $key = strtolower(trim((string) $email));
                if ($key === '') {
                    return;
                }
                $candidatesByEmail[$key][] = $id;
            });
        }

        $siswas = Siswa::whereNull('user_id')->orderBy('id')->get(['id', 'nis', 'email']);

        $linked = 0;
        $raceSkipped = 0;
        $noMatch = 0;
        $ambiguous = 0;
        $conflict = 0;
        $noMatchIds = [];
        $ambiguousDetails = [];
        $conflictDetails = [];

        foreach ($siswas as $siswa) {
            $emailKey = strtolower(trim((string) $siswa->email));
            $candidateIds = $emailKey === '' ? [] : ($candidatesByEmail[$emailKey] ?? []);

            if (count($candidateIds) === 0) {
                $noMatch++;
                $noMatchIds[] = $siswa->id;
                continue;
            }

            if (count($candidateIds) > 1) {
                // Ambigu — jangan pilih acak, laporkan id siswa + id user kandidat
                $ambiguous++;
                $ambiguousDetails[] = "siswa#{$siswa->id} (nis={$siswa->nis}) → user ids: [" . implode(', ', $candidateIds) . ']';
                continue;
            }

            $candidateUserId = $candidateIds[0];

            // Konflik: kandidat user sudah ter-link ke siswa lain → jangan overwrite
            $linkedElsewhereId = Siswa::where('user_id', $candidateUserId)
                ->where('id', '!=', $siswa->id)
                ->value('id');

            if ($linkedElsewhereId !== null) {
                $conflict++;
                $conflictDetails[] = "siswa#{$siswa->id} (nis={$siswa->nis}) → user#{$candidateUserId} sudah ter-link ke siswa#{$linkedElsewhereId}";
                continue;
            }

            // UPDATE ONLY dalam transaksi per siswa + guard whereNull (race-safe)
            $updated = DB::transaction(function () use ($siswa, $candidateUserId): bool {
                return Siswa::whereKey($siswa->id)
                    ->whereNull('user_id')
                    ->update(['user_id' => $candidateUserId]) > 0;
            });

            if ($updated) {
                $linked++;
            } else {
                // Race: baris terisi oleh proses lain di antara query dan update
                $raceSkipped++;
            }
        }

        // --- Summary ---
        $this->newLine();
        $this->info('Ringkasan:');
        $this->line("- Total siswa user_id NULL (diproses) : {$siswas->count()}");
        $this->line("- Berhasil di-link                    : {$linked}");
        $this->line("- Skip - sudah ter-link sebelumnya    : {$alreadyLinked}");
        $this->line("- Skip - tidak ada kandidat match     : {$noMatch}");
        $this->line("- Skip - ambigu (>1 kandidat)         : {$ambiguous}");
        $this->line("- Skip - konflik (user sudah linked)  : {$conflict}");
        if ($raceSkipped > 0) {
            $this->line("- Skip - race (terisi proses lain)    : {$raceSkipped}");
        }

        $this->newLine();
        $this->printExamples('Tidak ada match (id siswa)', array_map(fn (int $id): string => "#{$id}", $noMatchIds));
        $this->printExamples('Ambigu', $ambiguousDetails);
        $this->printExamples('Konflik', $conflictDetails);

        $this->newLine();
        $this->info('✅ Selesai. Tidak ada baris siswas baru yang dibuat (UPDATE ONLY).');

        if ($noMatch > 0 || $ambiguous > 0 || $conflict > 0) {
            $this->warn('⚠️  Ada siswa yang belum ter-link — hubungkan manual via admin (edit siswa → pilih user), atau perbaiki email siswa lalu jalankan ulang command ini.');
        }

        return self::SUCCESS;
    }

    /**
     * Cetak contoh detail per kategori skip (maksimal MAX_EXAMPLE_LINES baris).
     */
    private function printExamples(string $label, array $items): void
    {
        if ($items === []) {
            return;
        }

        $this->line("- {$label}:");
        foreach (array_slice($items, 0, self::MAX_EXAMPLE_LINES) as $item) {
            $this->line("    {$item}");
        }

        $remaining = count($items) - self::MAX_EXAMPLE_LINES;
        if ($remaining > 0) {
            $this->line("    … dan {$remaining} lainnya");
        }
    }
}
