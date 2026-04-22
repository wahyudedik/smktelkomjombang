<?php

namespace App\Exports;

use App\Models\Guru;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GuruExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $gurus;

    public function __construct($gurus)
    {
        $this->gurus = $gurus;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->gurus;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'NIP',
            'Nama Lengkap',
            'Gelar Depan',
            'Gelar Belakang',
            'Jenis Kelamin',
            'Tanggal Lahir',
            'Tempat Lahir',
            'Alamat',
            'No Telepon',
            'No WA',
            'Email',
            'Status Kepegawaian',
            'Jabatan',
            'Tanggal Masuk',
            'Tanggal Keluar',
            'Status Aktif',
            'Pendidikan Terakhir',
            'Universitas',
            'Tahun Lulus',
            'Sertifikasi',
            'Mata Pelajaran',
            'Prestasi',
            'Catatan',
        ];
    }

    /**
     * @param Guru $guru
     * @return array
     */
    public function map($guru): array
    {
        return [
            $guru->nip,
            $guru->nama_lengkap,
            $guru->gelar_depan,
            $guru->gelar_belakang,
            $guru->jenis_kelamin,
            $guru->tanggal_lahir ? $guru->tanggal_lahir->format('Y-m-d') : '', // @phpstan-ignore-line
            $guru->tempat_lahir,
            $guru->alamat,
            $guru->no_telepon,
            $guru->no_wa,
            $guru->email,
            $guru->status_kepegawaian,
            $guru->jabatan,
            $guru->tanggal_masuk ? $guru->tanggal_masuk->format('Y-m-d') : '', // @phpstan-ignore-line
            $guru->tanggal_keluar ? $guru->tanggal_keluar->format('Y-m-d') : '', // @phpstan-ignore-line
            $guru->status_aktif,
            $guru->pendidikan_terakhir,
            $guru->universitas,
            $guru->tahun_lulus,
            $guru->sertifikasi,
            is_array($guru->mata_pelajaran) ? implode(', ', $guru->mata_pelajaran) : $guru->mata_pelajaran,
            $guru->prestasi,
            $guru->catatan,
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
            'A' => 15,
            'B' => 25,
            'C' => 15,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 20,
            'H' => 30,
            'I' => 15,
            'J' => 15,
            'K' => 25,
            'L' => 15,
            'M' => 20,
            'N' => 15,
            'O' => 15,
            'P' => 15,
            'Q' => 20,
            'R' => 25,
            'S' => 15,
            'T' => 30,
            'U' => 30,
            'V' => 30,
            'W' => 30,
        ];
    }
}
