<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Barang;
use App\Models\Calon;
use App\Models\Pemilih;
use App\Models\Kelulusan;
use App\Imports\UserImport;
use App\Imports\SiswaImport;
use App\Imports\GuruImport;
use App\Imports\BarangImport;
use App\Imports\CalonImport;
use App\Imports\PemilihImport;
use App\Imports\KelulusanImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class BulkImportController extends Controller
{
    /**
     * Display the bulk import dashboard
     */
    public function index()
    {
        // Get import statistics
        $stats = [
            'total_users' => User::count(),
            'total_siswa' => Siswa::count(),
            'total_guru' => Guru::count(),
            'total_barang' => Barang::count(),
            'total_calon' => Calon::count(),
            'total_pemilih' => Pemilih::count(),
            'total_kelulusan' => Kelulusan::count(),
        ];

        return view('admin.bulk-import.index', compact('stats'));
    }

    /**
     * Process bulk import for multiple modules
     */
    public function processBulkImport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'module' => 'required|in:users,siswa,guru,barang,calon,pemilih,kelulusan',
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120', // 5MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $module = $request->module;
            $file = $request->file('file');

            Log::info("Bulk import started", [
                'module' => $module,
                'filename' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'user_id' => Auth::id()
            ]);

            $result = $this->importByModule($module, $file);

            return response()->json([
                'success' => true,
                'message' => "Import completed successfully for {$module}",
                'data' => $result
            ]);
        } catch (\Exception $e) {
            Log::error("Bulk import failed", [
                'module' => $request->module,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Import data based on module type
     */
    private function importByModule($module, $file)
    {
        switch ($module) {
            case 'users':
                $import = new UserImport();
                Excel::import($import, $file);
                return [
                    'module' => 'users',
                    'imported' => $import->getRowCount() ?? 0,
                    'errors' => $import->errors() ?? []
                ];

            case 'siswa':
                $import = new SiswaImport();
                Excel::import($import, $file);
                return [
                    'module' => 'siswa',
                    'imported' => $import->getRowCount() ?? 0,
                    'errors' => $import->errors() ?? []
                ];

            case 'guru':
                $import = new GuruImport();
                Excel::import($import, $file);
                return [
                    'module' => 'guru',
                    'imported' => $import->getRowCount() ?? 0,
                    'errors' => $import->errors() ?? []
                ];

            case 'barang':
                $import = new BarangImport();
                Excel::import($import, $file);
                return [
                    'module' => 'barang',
                    'imported' => $import->getRowCount() ?? 0,
                    'errors' => $import->errors() ?? []
                ];

            case 'calon':
                $import = new CalonImport();
                Excel::import($import, $file);
                return [
                    'module' => 'calon',
                    'imported' => $import->getRowCount() ?? 0,
                    'errors' => $import->errors() ?? []
                ];

            case 'pemilih':
                $import = new PemilihImport();
                Excel::import($import, $file);
                return [
                    'module' => 'pemilih',
                    'imported' => $import->getRowCount() ?? 0,
                    'errors' => $import->errors() ?? []
                ];

            case 'kelulusan':
                $import = new KelulusanImport();
                Excel::import($import, $file);
                return [
                    'module' => 'kelulusan',
                    'imported' => $import->getRowCount() ?? 0,
                    'errors' => $import->errors() ?? []
                ];

            default:
                throw new \Exception("Unsupported module: {$module}");
        }
    }

    /**
     * Download template for specific module
     */
    public function downloadTemplate($module)
    {
        switch ($module) {
            case 'users':
                return $this->generateUserTemplate();
            case 'siswa':
                return $this->generateSiswaTemplate();
            case 'guru':
                return $this->generateGuruTemplate();
            case 'barang':
                return $this->generateBarangTemplate();
            case 'calon':
                return $this->generateCalonTemplate();
            case 'pemilih':
                return $this->generatePemilihTemplate();
            case 'kelulusan':
                return $this->generateKelulusanTemplate();
            default:
                abort(404, 'Template not found');
        }
    }

    /**
     * Generate user import template
     */
    private function generateUserTemplate()
    {
        $sampleData = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'role' => 'siswa',
                'password' => 'password123',
            ]
        ];

        return $this->exportTemplate($sampleData, 'template-import-users.xlsx', [
            'name',
            'email',
            'role',
            'password'
        ]);
    }

    /**
     * Generate siswa import template
     */
    private function generateSiswaTemplate()
    {
        $sampleData = [
            [
                'nama_lengkap' => 'Ahmad Rizki',
                'nisn' => '1234567890',
                'nis' => '2024001',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2008-01-15',
                'tempat_lahir' => 'Jakarta',
                'kelas' => 'X',
                'jurusan' => 'IPA',
                'alamat' => 'Jl. Contoh No. 123',
                'no_telepon' => '08123456789',
                'email' => 'ahmad@example.com',
            ]
        ];

        return $this->exportTemplate($sampleData, 'template-import-siswa.xlsx', [
            'nama_lengkap',
            'nisn',
            'nis',
            'jenis_kelamin',
            'tanggal_lahir',
            'tempat_lahir',
            'kelas',
            'jurusan',
            'alamat',
            'no_telepon',
            'email'
        ]);
    }

    /**
     * Generate guru import template
     */
    private function generateGuruTemplate()
    {
        $sampleData = [
            [
                'nip' => '196501011990031001',
                'nama_lengkap' => 'Dr. Ahmad Rizki, M.Pd',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1965-01-01',
                'tempat_lahir' => 'Jakarta',
                'alamat' => 'Jl. Pendidikan No. 123',
                'no_telepon' => '08123456789',
                'email' => 'ahmad@example.com',
                'status_kepegawaian' => 'PNS',
                'jabatan' => 'Guru Mata Pelajaran',
                'tanggal_masuk' => '1990-03-01',
                'status_aktif' => 'aktif',
                'pendidikan_terakhir' => 'S3',
                'universitas' => 'Universitas Pendidikan Indonesia',
                'tahun_lulus' => '2010',
                'mata_pelajaran' => 'Matematika, Fisika',
            ]
        ];

        return $this->exportTemplate($sampleData, 'template-import-guru.xlsx', [
            'nip',
            'nama_lengkap',
            'jenis_kelamin',
            'tanggal_lahir',
            'tempat_lahir',
            'alamat',
            'no_telepon',
            'email',
            'status_kepegawaian',
            'jabatan',
            'tanggal_masuk',
            'status_aktif',
            'pendidikan_terakhir',
            'universitas',
            'tahun_lulus',
            'mata_pelajaran'
        ]);
    }

    /**
     * Generate barang import template
     */
    private function generateBarangTemplate()
    {
        $sampleData = [
            [
                'kode_barang' => 'BRG001',
                'nama_barang' => 'Laptop Dell',
                'kategori' => 'Elektronik',
                'ruang' => 'Lab Komputer',
                'jumlah' => '10',
                'kondisi' => 'baik',
                'tanggal_pengadaan' => '2024-01-15',
                'harga' => '15000000',
            ]
        ];

        return $this->exportTemplate($sampleData, 'template-import-barang.xlsx', [
            'kode_barang',
            'nama_barang',
            'kategori',
            'ruang',
            'jumlah',
            'kondisi',
            'tanggal_pengadaan',
            'harga'
        ]);
    }

    /**
     * Generate calon import template
     */
    private function generateCalonTemplate()
    {
        $sampleData = [
            [
                'nomor_urut' => '1',
                'nama' => 'Ahmad Rizki',
                'kelas' => 'XI IPA 1',
                'jenis_kelamin' => 'L',
                'visi' => 'Memajukan OSIS',
                'misi' => 'Meningkatkan prestasi',
            ]
        ];

        return $this->exportTemplate($sampleData, 'template-import-calon.xlsx', [
            'nomor_urut',
            'nama',
            'kelas',
            'jenis_kelamin',
            'visi',
            'misi'
        ]);
    }

    /**
     * Generate pemilih import template
     */
    private function generatePemilihTemplate()
    {
        $sampleData = [
            [
                'nama' => 'Siti Nurhaliza',
                'nisn' => '1234567890',
                'kelas' => 'X IPA 1',
                'jenis_kelamin' => 'P',
                'email' => 'siti@example.com',
            ]
        ];

        return $this->exportTemplate($sampleData, 'template-import-pemilih.xlsx', [
            'nama',
            'nisn',
            'kelas',
            'jenis_kelamin',
            'email'
        ]);
    }

    /**
     * Generate kelulusan import template
     */
    private function generateKelulusanTemplate()
    {
        $sampleData = [
            [
                'nama' => 'Ahmad Rizki',
                'nisn' => '1234567890',
                'nis' => '2024001',
                'jurusan' => 'IPA',
                'tahun_ajaran' => '2024',
                'status' => 'lulus',
                'tanggal_lulus' => '2024-06-15',
            ]
        ];

        return $this->exportTemplate($sampleData, 'template-import-kelulusan.xlsx', [
            'nama',
            'nisn',
            'nis',
            'jurusan',
            'tahun_ajaran',
            'status',
            'tanggal_lulus'
        ]);
    }

    /**
     * Export template helper
     */
    private function exportTemplate($data, $filename, $headings)
    {
        $templateExport = new class($data, $headings) implements
            \Maatwebsite\Excel\Concerns\FromArray,
            \Maatwebsite\Excel\Concerns\WithHeadings,
            \Maatwebsite\Excel\Concerns\WithStyles
        {
            protected $data;
            protected $headings;

            public function __construct($data, $headings)
            {
                $this->data = $data;
                $this->headings = $headings;
            }

            public function array(): array
            {
                return $this->data;
            }

            public function headings(): array
            {
                return $this->headings;
            }

            public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
            {
                return [
                    1 => ['font' => ['bold' => true]],
                ];
            }
        };

        return Excel::download($templateExport, $filename);
    }
}
