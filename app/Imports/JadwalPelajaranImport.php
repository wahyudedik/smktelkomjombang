<?php

namespace App\Imports;

use App\Models\JadwalPelajaran;
use App\Models\MataPelajaran;
use App\Models\Guru;
use App\Models\Kelas;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class JadwalPelajaranImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $rowCount = 0;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            Log::info("Processing jadwal row", [
                'mata_pelajaran' => $row['mata_pelajaran'] ?? 'N/A',
                'kelas' => $row['kelas'] ?? 'N/A'
            ]);

            // Find mata pelajaran by name
            $mataPelajaran = MataPelajaran::where('nama', trim($row['mata_pelajaran']))->first();
            if (!$mataPelajaran) {
                Log::warning("Mata pelajaran not found: {$row['mata_pelajaran']}");
                return null;
            }

            // Find guru by name
            $guru = Guru::where('nama_lengkap', trim($row['guru']))
                ->orWhere('nip', trim($row['guru']))
                ->first();
            if (!$guru) {
                Log::warning("Guru not found: {$row['guru']}");
                return null;
            }

            // Find kelas by name
            $kelas = Kelas::where('nama', trim($row['kelas']))->first();
            if (!$kelas) {
                Log::warning("Kelas not found: {$row['kelas']}");
                return null;
            }

            // Check if jadwal already exists
            $existing = JadwalPelajaran::where('kelas_id', $kelas->id)
                ->where('hari', trim($row['hari']))
                ->where('jam_mulai', trim($row['jam_mulai']))
                ->where('tahun_ajaran', trim($row['tahun_ajaran']))
                ->where('semester', trim($row['semester']))
                ->first();

            if ($existing) {
                Log::info("Skipping duplicate jadwal for {$kelas->nama} on {$row['hari']} at {$row['jam_mulai']}");
                return null;
            }

            Log::info("Creating jadwal", [
                'mata_pelajaran' => $mataPelajaran->nama,
                'guru' => $guru->nama_lengkap,
                'kelas' => $kelas->nama
            ]);

            $jadwal = new JadwalPelajaran([
                'mata_pelajaran_id' => $mataPelajaran->id,
                'guru_id' => $guru->id,
                'kelas_id' => $kelas->id,
                'hari' => trim($row['hari']),
                'jam_mulai' => trim($row['jam_mulai']),
                'jam_selesai' => trim($row['jam_selesai']),
                'ruang' => !empty($row['ruang']) ? trim($row['ruang']) : null,
                'tahun_ajaran' => trim($row['tahun_ajaran']),
                'semester' => trim($row['semester']),
                'catatan' => !empty($row['catatan']) ? trim($row['catatan']) : null,
                'status' => !empty($row['status']) ? trim($row['status']) : 'aktif',
            ]);

            $this->rowCount++;
            Log::info("Jadwal created successfully", [
                'kelas' => $kelas->nama,
                'hari' => $jadwal->hari,
                'jam' => $jadwal->jam_mulai
            ]);

            return $jadwal;
        } catch (\Exception $e) {
            Log::error("Error creating jadwal", [
                'row' => $row,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return null;
        }
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.mata_pelajaran' => 'required|string',
            '*.guru' => 'required|string',
            '*.kelas' => 'required|string',
            '*.hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            '*.jam_mulai' => 'required',
            '*.jam_selesai' => 'required',
            '*.tahun_ajaran' => 'required|string',
            '*.semester' => 'required|in:Ganjil,Genap',
            '*.ruang' => 'nullable|string',
            '*.catatan' => 'nullable|string',
            '*.status' => 'nullable|in:aktif,nonaktif',
        ];
    }

    /**
     * Get the number of rows imported
     */
    public function getRowCount(): int
    {
        return $this->rowCount;
    }
}
