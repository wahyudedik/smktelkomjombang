<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SiswaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $siswas;

    public function __construct($siswas)
    {
        $this->siswas = $siswas;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->siswas;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'NIS',
            'NISN',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Tanggal Lahir',
            'Tempat Lahir',
            'Alamat',
            'No Telepon',
            'No WA',
            'Email',
            'Kelas',
            'Jurusan',
            'Tahun Masuk',
            'Tahun Lulus',
            'Status',
            'Nama Ayah',
            'Pekerjaan Ayah',
            'Nama Ibu',
            'Pekerjaan Ibu',
            'No Telepon Ortu',
            'Alamat Ortu',
            'Prestasi',
            'Catatan',
        ];
    }

    /**
     * @param Siswa $siswa
     * @return array
     */
    public function map($siswa): array
    {
        return [
            $siswa->nis,
            $siswa->nisn,
            $siswa->nama_lengkap,
            $siswa->jenis_kelamin,
            $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('Y-m-d') : '', // @phpstan-ignore-line
            $siswa->tempat_lahir,
            $siswa->alamat,
            $siswa->no_telepon,
            $siswa->no_wa,
            $siswa->email,
            $siswa->kelas,
            $siswa->jurusan,
            $siswa->tahun_masuk,
            $siswa->tahun_lulus,
            $siswa->status,
            $siswa->nama_ayah,
            $siswa->pekerjaan_ayah,
            $siswa->nama_ibu,
            $siswa->pekerjaan_ibu,
            $siswa->no_telepon_ortu,
            $siswa->alamat_ortu,
            $siswa->prestasi,
            $siswa->catatan,
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
            'B' => 15,
            'C' => 25,
            'D' => 15,
            'E' => 15,
            'F' => 20,
            'G' => 30,
            'H' => 15,
            'I' => 15,
            'J' => 25,
            'K' => 15,
            'L' => 20,
            'M' => 15,
            'N' => 15,
            'O' => 15,
            'P' => 20,
            'Q' => 20,
            'R' => 20,
            'S' => 20,
            'T' => 15,
            'U' => 30,
            'V' => 30,
            'W' => 30,
        ];
    }
}
