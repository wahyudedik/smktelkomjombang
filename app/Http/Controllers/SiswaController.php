<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiswaImport;
use App\Exports\SiswaExport;
use Barryvdh\DomPDF\Facade\Pdf;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Siswa::with('user');

        // Filter by status (lebih robust: check filled)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by class (lebih robust: check filled)
        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        // Filter by year (lebih robust: check filled)
        if ($request->filled('tahun_masuk')) {
            $query->where('tahun_masuk', $request->tahun_masuk);
        }

        // Search by name, NIS, or NISN (lebih robust: check filled)
        if ($request->filled('search')) {
            $search = trim($request->search);
            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', '%' . $search . '%')
                        ->orWhere('nis', 'like', '%' . $search . '%')
                        ->orWhere('nisn', 'like', '%' . $search . '%');
                });
            }
        }

        // Sort
        $sortBy = $request->get('sort_by', 'nama_lengkap');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $siswas = $query->paginate(15)->withQueryString(); // Preserve query string saat pagination
        $statuses = ['aktif', 'lulus', 'pindah', 'keluar', 'meninggal'];
        $kelas = $this->getAvailableClasses();
        $tahunMasuk = Siswa::distinct()->pluck('tahun_masuk')->sort()->values();

        return view('siswa.index', compact('siswas', 'statuses', 'kelas', 'tahunMasuk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = $this->getAvailableClasses();
        $jurusan = $this->getAvailableMajors();
        $ekstrakurikuler = $this->getAvailableExtracurriculars();

        // Get users that are not already assigned to any student
        $usedUserIds = Siswa::whereNotNull('user_id')->pluck('user_id')->toArray();
        $users = User::whereHas('roles', function ($q) {
                $q->where('name', 'siswa');
            })
            ->whereNotIn('id', $usedUserIds)
            ->get();

        return view('siswa.create', compact('kelas', 'jurusan', 'ekstrakurikuler', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|string|unique:siswas,nis',
            'nisn' => 'required|string|unique:siswas,nisn',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telepon' => 'nullable|string|max:20',
            'no_wa' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kelas' => 'nullable|string|max:50',
            'jurusan' => 'nullable|string|max:100',
            'tahun_masuk' => 'required|integer|min:2000|max:' . date('Y'),
            'tahun_lulus' => 'nullable|integer|min:2000|max:' . date('Y'),
            'status' => 'required|in:aktif,lulus,pindah,keluar,meninggal',
            'nama_ayah' => 'nullable|string|max:255',
            'pekerjaan_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'pekerjaan_ibu' => 'nullable|string|max:255',
            'no_telepon_ortu' => 'nullable|string|max:20',
            'alamat_ortu' => 'nullable|string',
            'prestasi' => 'nullable|string',
            'catatan' => 'nullable|string',
            'ekstrakurikuler' => 'nullable|array',
            'ekstrakurikuler.*' => 'string',
            'user_id' => 'nullable|exists:users,id|unique:siswas,user_id',
        ]);

        $data = $request->all();

        // Handle photo upload
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('siswa/photos', 'public');
        }

        $siswa = Siswa::create($data);

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        $siswa->load('user');
        return view('siswa.show', compact('siswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        $kelas = $this->getAvailableClasses();
        $jurusan = $this->getAvailableMajors();
        $ekstrakurikuler = $this->getAvailableExtracurriculars();

        // Get users that are not already assigned to any student, plus the current student's user
        $usedUserIds = Siswa::whereNotNull('user_id')
            ->where('id', '!=', $siswa->id)
            ->pluck('user_id')
            ->toArray();
        $users = User::whereHas('roles', function ($q) {
                $q->where('name', 'siswa');
            })
            ->where(function ($query) use ($usedUserIds, $siswa) {
                $query->whereNotIn('id', $usedUserIds)
                    ->orWhere('id', $siswa->user_id);
            })
            ->get();

        return view('siswa.edit', compact('siswa', 'kelas', 'jurusan', 'ekstrakurikuler', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nis' => 'required|string|unique:siswas,nis,' . $siswa->id,
            'nisn' => 'required|string|unique:siswas,nisn,' . $siswa->id,
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telepon' => 'nullable|string|max:20',
            'no_wa' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kelas' => 'nullable|string|max:50',
            'jurusan' => 'nullable|string|max:100',
            'tahun_masuk' => 'required|integer|min:2000|max:' . date('Y'),
            'tahun_lulus' => 'nullable|integer|min:2000|max:' . date('Y'),
            'status' => 'required|in:aktif,lulus,pindah,keluar,meninggal',
            'nama_ayah' => 'nullable|string|max:255',
            'pekerjaan_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'pekerjaan_ibu' => 'nullable|string|max:255',
            'no_telepon_ortu' => 'nullable|string|max:20',
            'alamat_ortu' => 'nullable|string',
            'prestasi' => 'nullable|string',
            'catatan' => 'nullable|string',
            'ekstrakurikuler' => 'nullable|array',
            'ekstrakurikuler.*' => 'string',
            'user_id' => 'nullable|exists:users,id|unique:siswas,user_id,' . $siswa->id,
        ]);

        $data = $request->all();

        // Handle photo upload
        if ($request->hasFile('foto')) {
            // Delete old photo
            if ($siswa->foto) {
                Storage::disk('public')->delete($siswa->foto);
            }
            $data['foto'] = $request->file('foto')->store('siswa/photos', 'public');
        }

        $siswa->update($data);

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)
    {
        // Delete photo
        if ($siswa->foto) {
            Storage::disk('public')->delete($siswa->foto);
        }

        $siswa->delete();

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }

    /**
     * Get available classes.
     */
    private function getAvailableClasses()
    {
        // Get from database first, fallback to hardcoded if empty
        $dbClasses = DB::table('kelas')->pluck('nama')->toArray();

        if (empty($dbClasses)) {
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

        return $dbClasses;
    }

    /**
     * Get available majors.
     */
    private function getAvailableMajors()
    {
        // Get from database first, fallback to hardcoded if empty
        $dbMajors = DB::table('jurusan')->pluck('nama')->toArray();

        if (empty($dbMajors)) {
            return [
                'IPA (Ilmu Pengetahuan Alam)',
                'IPS (Ilmu Pengetahuan Sosial)',
                'Bahasa',
                'Teknik Informatika',
                'Teknik Mesin',
                'Teknik Elektro',
                'Akuntansi',
                'Administrasi Perkantoran',
                'Pemasaran',
            ];
        }

        return $dbMajors;
    }

    /**
     * Get available extracurriculars.
     */
    private function getAvailableExtracurriculars()
    {
        // Get from database first, fallback to hardcoded if empty
        $dbExtracurriculars = DB::table('ekstrakurikuler')->pluck('nama')->toArray();

        if (empty($dbExtracurriculars)) {
            return [
                'Basket',
                'Futsal',
                'Voli',
                'Badminton',
                'Paduan Suara',
                'Tari Tradisional',
                'Teater',
                'Fotografi',
                'Debat Bahasa Inggris',
                'Matematika Club',
                'Science Club',
                'Literasi Digital',
                'Pramuka',
                'Paskibra',
                'OSIS',
                'PMR',
                'KIR',
            ];
        }

        return $dbExtracurriculars;
    }

    /**
     * Show import form.
     */
    public function import()
    {
        return view('siswa.import');
    }

    /**
     * Download template Excel for import.
     */
    public function downloadTemplate()
    {
        // Create sample data for template
        $sampleData = [
            [
                'nis' => '2024001',
                'nisn' => '1234567890',
                'nama_lengkap' => 'Ahmad Rizki',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2006-05-15',
                'tempat_lahir' => 'Jakarta',
                'alamat' => 'Jl. Pendidikan No. 123, Jakarta',
                'no_telepon' => '08123456789',
                'no_wa' => '08123456789',
                'email' => 'ahmad.rizki@siswa.com',
                'kelas' => 'X IPA 1',
                'jurusan' => 'IPA',
                'tahun_masuk' => '2024',
                'tahun_lulus' => '',
                'status' => 'aktif',
                'nama_ayah' => 'Budi Rizki',
                'pekerjaan_ayah' => 'PNS',
                'nama_ibu' => 'Siti Rizki',
                'pekerjaan_ibu' => 'Ibu Rumah Tangga',
                'no_telepon_ortu' => '08123456788',
                'alamat_ortu' => 'Jl. Pendidikan No. 123, Jakarta',
                'prestasi' => 'Juara 1 Olimpiade Matematika',
                'catatan' => 'Siswa berprestasi'
            ],
            [
                'nis' => '2024002',
                'nisn' => '0987654321',
                'nama_lengkap' => 'Siti Nurhaliza',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2006-08-20',
                'tempat_lahir' => 'Bandung',
                'alamat' => 'Jl. Siswa No. 456, Bandung',
                'no_telepon' => '08987654321',
                'no_wa' => '08987654321',
                'email' => 'siti.nurhaliza@siswa.com',
                'kelas' => 'X IPS 1',
                'jurusan' => 'IPS',
                'tahun_masuk' => '2024',
                'tahun_lulus' => '',
                'status' => 'aktif',
                'nama_ayah' => 'Ahmad Nurhaliza',
                'pekerjaan_ayah' => 'Wiraswasta',
                'nama_ibu' => 'Fatimah Nurhaliza',
                'pekerjaan_ibu' => 'Guru',
                'no_telepon_ortu' => '08987654320',
                'alamat_ortu' => 'Jl. Siswa No. 456, Bandung',
                'prestasi' => 'Juara 2 Lomba Debat',
                'catatan' => 'Siswa aktif dan kreatif'
            ]
        ];

        // Create a new export class for template
        $templateExport = new class($sampleData) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithStyles, \Maatwebsite\Excel\Concerns\WithColumnWidths {
            protected $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function array(): array
            {
                return $this->data;
            }

            public function headings(): array
            {
                return [
                    'nis',
                    'nisn',
                    'nama_lengkap',
                    'jenis_kelamin',
                    'tanggal_lahir',
                    'tempat_lahir',
                    'alamat',
                    'no_telepon',
                    'no_wa',
                    'email',
                    'kelas',
                    'jurusan',
                    'tahun_masuk',
                    'tahun_lulus',
                    'status',
                    'nama_ayah',
                    'pekerjaan_ayah',
                    'nama_ibu',
                    'pekerjaan_ibu',
                    'no_telepon_ortu',
                    'alamat_ortu',
                    'prestasi',
                    'catatan'
                ];
            }

            public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
            {
                return [
                    1 => ['font' => ['bold' => true]],
                ];
            }

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
                    'W' => 30
                ];
            }
        };

        return Excel::download($templateExport, 'template-import-siswa.xlsx');
    }

    /**
     * Process import.
     */
    public function processImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            // Get file info for logging
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();

            Log::info("Starting siswa import process", [
                'file_name' => $fileName,
                'file_size' => $fileSize,
                'user_id' => Auth::id()
            ]);

            // Create import instance
            $import = new SiswaImport();

            // Import the file
            Excel::import($import, $file);

            // Get import results
            $importedCount = $import->getRowCount() ?? 0;
            $errors = $import->errors();
            $failures = $import->failures();

            Log::info("Siswa import completed", [
                'imported_count' => $importedCount,
                'errors_count' => count($errors),
                'failures_count' => count($failures)
            ]);

            // Prepare success message with details
            $message = "Data siswa berhasil diimpor!";
            $details = [];

            if ($importedCount > 0) {
                $details[] = "Berhasil mengimpor {$importedCount} siswa";
            }

            if (count($failures) > 0) {
                $details[] = count($failures) . " siswa gagal diimpor (cek log untuk detail)";
            }

            if (count($errors) > 0) {
                $details[] = count($errors) . " siswa memiliki error validasi";
            }

            if (!empty($details)) {
                $message .= " (" . implode(', ', $details) . ")";
            }

            return redirect()->route('admin.siswa.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error("Siswa import failed", [
                'error' => $e->getMessage(),
                'file' => $request->file('file')->getClientOriginalName(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }

    /**
     * Export data.
     */
    public function export(Request $request)
    {
        $query = Siswa::query();

        // Apply filters
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('kelas') && $request->kelas !== '') {
            $query->where('kelas', $request->kelas);
        }

        if ($request->has('jurusan') && $request->jurusan !== '') {
            $query->where('jurusan', $request->jurusan);
        }

        $siswas = $query->get();

        return Excel::download(new SiswaExport($siswas), 'siswa-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export siswa to PDF.
     */
    public function exportPdf(Request $request)
    {
        $query = Siswa::with('user');

        // Apply filters
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('kelas') && $request->kelas !== '') {
            $query->where('kelas', $request->kelas);
        }

        if ($request->has('jurusan') && $request->jurusan !== '') {
            $query->where('jurusan', $request->jurusan);
        }

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                    ->orWhere('nis', 'like', '%' . $search . '%')
                    ->orWhere('nisn', 'like', '%' . $search . '%');
            });
        }

        $siswas = $query->orderBy('nama_lengkap', 'asc')->get();

        $pdf = Pdf::loadView('siswa.pdf', compact('siswas'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('data-siswa-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export siswa to JSON.
     */
    public function exportJson(Request $request)
    {
        $query = Siswa::with('user');

        // Apply filters
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('kelas') && $request->kelas !== '') {
            $query->where('kelas', $request->kelas);
        }

        $siswas = $query->orderBy('nama_lengkap', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $siswas,
            'total' => $siswas->count(),
            'exported_at' => now()->toIso8601String()
        ]);
    }

    /**
     * Export siswa to XML.
     */
    public function exportXml(Request $request)
    {
        $query = Siswa::with('user');

        // Apply filters
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $siswas = $query->orderBy('nama_lengkap', 'asc')->get();

        $xml = new \SimpleXMLElement('<siswas/>');
        $xml->addAttribute('exported_at', now()->toIso8601String());
        $xml->addAttribute('total', $siswas->count());

        foreach ($siswas as $siswa) {
            $siswaNode = $xml->addChild('siswa');
            $siswaNode->addChild('id', $siswa->id);
            $siswaNode->addChild('nis', $siswa->nis);
            $siswaNode->addChild('nisn', $siswa->nisn ?? '');
            $siswaNode->addChild('nama_lengkap', htmlspecialchars($siswa->nama_lengkap));
            $siswaNode->addChild('jenis_kelamin', $siswa->jenis_kelamin);
            $siswaNode->addChild('kelas', htmlspecialchars($siswa->kelas ?? ''));
            $siswaNode->addChild('jurusan', htmlspecialchars($siswa->jurusan ?? ''));
            $siswaNode->addChild('status', $siswa->status);
        }

        return response($xml->asXML(), 200)
            ->header('Content-Type', 'application/xml')
            ->header('Content-Disposition', 'attachment; filename="data-siswa-' . date('Y-m-d') . '.xml"');
    }
}
