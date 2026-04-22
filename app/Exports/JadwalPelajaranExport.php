<?php

namespace App\Exports;

use App\Models\JadwalPelajaran;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JadwalPelajaranExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = JadwalPelajaran::with(['mataPelajaran', 'guru', 'kelas']);

        // Apply filters
        if (!empty($this->filters['kelas_id'])) {
            $query->where('kelas_id', $this->filters['kelas_id']);
        }

        if (!empty($this->filters['guru_id'])) {
            $query->where('guru_id', $this->filters['guru_id']);
        }

        if (!empty($this->filters['tahun_ajaran'])) {
            $query->where('tahun_ajaran', $this->filters['tahun_ajaran']);
        }

        if (!empty($this->filters['semester'])) {
            $query->where('semester', $this->filters['semester']);
        }

        return $query->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
            ->orderBy('jam_mulai');
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Mata Pelajaran',
            'Guru',
            'Kelas',
            'Hari',
            'Jam Mulai',
            'Jam Selesai',
            'Ruang',
            'Tahun Ajaran',
            'Semester',
            'Catatan',
            'Status',
        ];
    }

    /**
     * @param JadwalPelajaran $jadwal
     * @return array
     */
    public function map($jadwal): array
    {
        return [
            $jadwal->id,
            $jadwal->mataPelajaran->nama ?? '-',
            $jadwal->guru->full_name ?? '-',
            $jadwal->kelas->nama ?? '-',
            $jadwal->hari,
            $jadwal->jam_mulai,
            $jadwal->jam_selesai,
            $jadwal->ruang ?? '-',
            $jadwal->tahun_ajaran,
            $jadwal->semester,
            $jadwal->catatan ?? '-',
            $jadwal->status,
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 25,
            'C' => 25,
            'D' => 15,
            'E' => 12,
            'F' => 12,
            'G' => 12,
            'H' => 15,
            'I' => 15,
            'J' => 12,
            'K' => 30,
            'L' => 12,
        ];
    }
}
