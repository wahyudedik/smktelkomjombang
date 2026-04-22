<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\GuruImport;
use App\Exports\GuruExport;
use Barryvdh\DomPDF\Facade\Pdf;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Guru::with('user');

        // Filter by status (lebih robust: check filled)
        if ($request->filled('status')) {
            $query->where('status_aktif', $request->status);
        }

        // Filter by employment status (lebih robust: check filled)
        if ($request->filled('employment_status')) {
            $query->where('status_kepegawaian', $request->employment_status);
        }

        // Filter by subject (lebih robust: check filled dan handle null)
        if ($request->filled('subject')) {
            $query->where(function ($q) use ($request) {
                // Handle jika mata_pelajaran null atau empty array
                $q->whereNotNull('mata_pelajaran')
                    ->where('mata_pelajaran', '!=', '[]')
                    ->where('mata_pelajaran', '!=', '')
                    ->whereJsonContains('mata_pelajaran', $request->subject);
            });
        }

        // Search by name or NIP (lebih robust: check filled)
        if ($request->filled('search')) {
            $search = trim($request->search);
            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', '%' . $search . '%')
                        ->orWhere('nip', 'like', '%' . $search . '%');
                });
            }
        }

        // Sort
        $sortBy = $request->get('sort_by', 'nama_lengkap');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $gurus = $query->paginate(15)->withQueryString(); // Preserve query string saat pagination
        $statuses = ['aktif', 'tidak_aktif', 'pensiun', 'meninggal'];
        $employmentStatuses = ['PNS', 'CPNS', 'GTT', 'GTY', 'Honorer'];
        $subjects = $this->getAvailableSubjects();

        return view('guru.index', compact('gurus', 'statuses', 'employmentStatuses', 'subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get subjects from database first, fallback to hardcoded if empty
        $dbSubjects = MataPelajaran::pluck('nama')->toArray();
        $subjects = !empty($dbSubjects) ? $dbSubjects : $this->getAvailableSubjects();

        // Get users that are not already assigned to any teacher
        $usedUserIds = Guru::whereNotNull('user_id')->pluck('user_id')->toArray();
        $users = User::whereHas('roles', function ($q) {
                $q->where('name', 'guru');
            })
            ->whereNotIn('id', $usedUserIds)
            ->get();

        return view('guru.create', compact('subjects', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|string|unique:gurus,nip',
            'nama_lengkap' => 'required|string|max:255',
            'gelar_depan' => 'nullable|string|max:50',
            'gelar_belakang' => 'nullable|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telepon' => 'nullable|string|max:20',
            'no_wa' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status_kepegawaian' => 'required|in:PNS,CPNS,GTT,GTY,Honorer',
            'jabatan' => 'nullable|string|max:100',
            'tanggal_masuk' => 'required|date',
            'tanggal_keluar' => 'nullable|date|after:tanggal_masuk',
            'status_aktif' => 'required|in:aktif,tidak_aktif,pensiun,meninggal',
            'pendidikan_terakhir' => 'required|string',
            'universitas' => 'required|string|max:255',
            'tahun_lulus' => 'required|string|max:4',
            'sertifikasi' => 'nullable|string',
            'mata_pelajaran' => 'required|array|min:1',
            'mata_pelajaran.*' => 'required|string',
            'prestasi' => 'nullable|string',
            'catatan' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id|unique:gurus,user_id',
        ]);

        $data = $request->all();

        // Handle photo upload
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('guru/photos', 'public');
        }

        $guru = Guru::create($data);

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Guru $guru)
    {
        $guru->load('user');
        return view('guru.show', compact('guru'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guru $guru)
    {
        // Get subjects from database first, fallback to hardcoded if empty
        $dbSubjects = MataPelajaran::pluck('nama')->toArray();
        $subjects = !empty($dbSubjects) ? $dbSubjects : $this->getAvailableSubjects();

        // Get users that are not already assigned to any teacher, plus the current teacher's user
        $usedUserIds = Guru::whereNotNull('user_id')
            ->where('id', '!=', $guru->id)
            ->pluck('user_id')
            ->toArray();
        $users = User::whereHas('roles', function ($q) {
                $q->where('name', 'guru');
            })
            ->where(function ($query) use ($usedUserIds, $guru) {
                $query->whereNotIn('id', $usedUserIds)
                    ->orWhere('id', $guru->user_id);
            })
            ->get();

        return view('guru.edit', compact('guru', 'subjects', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nip' => 'required|string|unique:gurus,nip,' . $guru->id,
            'nama_lengkap' => 'required|string|max:255',
            'gelar_depan' => 'nullable|string|max:50',
            'gelar_belakang' => 'nullable|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telepon' => 'nullable|string|max:20',
            'no_wa' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status_kepegawaian' => 'required|in:PNS,CPNS,GTT,GTY,Honorer',
            'jabatan' => 'nullable|string|max:100',
            'tanggal_masuk' => 'required|date',
            'tanggal_keluar' => 'nullable|date|after:tanggal_masuk',
            'status_aktif' => 'required|in:aktif,tidak_aktif,pensiun,meninggal',
            'pendidikan_terakhir' => 'required|string',
            'universitas' => 'required|string|max:255',
            'tahun_lulus' => 'required|string|max:4',
            'sertifikasi' => 'nullable|string',
            'mata_pelajaran' => 'required|array|min:1',
            'mata_pelajaran.*' => 'required|string',
            'prestasi' => 'nullable|string',
            'catatan' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id|unique:gurus,user_id,' . $guru->id,
        ]);

        $data = $request->all();

        // Handle photo upload
        if ($request->hasFile('foto')) {
            // Delete old photo
            if ($guru->foto) {
                Storage::disk('public')->delete($guru->foto);
            }
            $data['foto'] = $request->file('foto')->store('guru/photos', 'public');
        }

        $guru->update($data);

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guru $guru)
    {
        // Delete photo
        if ($guru->foto) {
            Storage::disk('public')->delete($guru->foto);
        }

        $guru->delete();

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }

    /**
     * Export guru to PDF.
     */
    public function exportPdf(Request $request)
    {
        $query = Guru::with('user');

        // Apply same filters as index
        if ($request->has('status') && $request->status !== '') {
            $query->where('status_aktif', $request->status);
        }

        if ($request->has('employment_status') && $request->employment_status !== '') {
            $query->where('status_kepegawaian', $request->employment_status);
        }

        if ($request->has('subject') && $request->subject !== '') {
            $query->whereJsonContains('mata_pelajaran', $request->subject);
        }

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                    ->orWhere('nip', 'like', '%' . $search . '%');
            });
        }

        $gurus = $query->orderBy('nama_lengkap', 'asc')->get();

        $pdf = Pdf::loadView('guru.pdf', compact('gurus'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('data-guru-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export guru to JSON.
     */
    public function exportJson(Request $request)
    {
        $query = Guru::with('user');

        // Apply filters
        if ($request->has('status') && $request->status !== '') {
            $query->where('status_aktif', $request->status);
        }

        $gurus = $query->orderBy('nama_lengkap', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $gurus,
            'total' => $gurus->count(),
            'exported_at' => now()->toIso8601String()
        ]);
    }

    /**
     * Export guru to XML.
     */
    public function exportXml(Request $request)
    {
        $query = Guru::with('user');

        // Apply filters
        if ($request->has('status') && $request->status !== '') {
            $query->where('status_aktif', $request->status);
        }

        $gurus = $query->orderBy('nama_lengkap', 'asc')->get();

        $xml = new \SimpleXMLElement('<gurus/>');
        $xml->addAttribute('exported_at', now()->toIso8601String());
        $xml->addAttribute('total', $gurus->count());

        foreach ($gurus as $guru) {
            $guruNode = $xml->addChild('guru');
            $guruNode->addChild('id', $guru->id);
            $guruNode->addChild('nip', $guru->nip);
            $guruNode->addChild('nama_lengkap', htmlspecialchars($guru->nama_lengkap));
            $guruNode->addChild('full_name', htmlspecialchars($guru->full_name));
            $guruNode->addChild('jenis_kelamin', $guru->jenis_kelamin);
            $guruNode->addChild('tanggal_lahir', $guru->tanggal_lahir);
            $guruNode->addChild('tempat_lahir', htmlspecialchars($guru->tempat_lahir));
            $guruNode->addChild('status_kepegawaian', $guru->status_kepegawaian);
            $guruNode->addChild('jabatan', htmlspecialchars($guru->jabatan ?? ''));
            $guruNode->addChild('status_aktif', $guru->status_aktif);

            // Mata pelajaran
            if (is_array($guru->mata_pelajaran)) {
                $mapelNode = $guruNode->addChild('mata_pelajaran');
                foreach ($guru->mata_pelajaran as $mapel) {
                    $mapelNode->addChild('item', htmlspecialchars($mapel));
                }
            }
        }

        return response($xml->asXML(), 200)
            ->header('Content-Type', 'application/xml')
            ->header('Content-Disposition', 'attachment; filename="data-guru-' . date('Y-m-d') . '.xml"');
    }

    /**
     * Get available subjects.
     */
    private function getAvailableSubjects()
    {
        return MataPelajaran::orderBy('nama')->pluck('nama')->toArray();
    }

    /**
     * Add new subject.
     */
    public function addSubject(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:mata_pelajaran,nama'
        ]);

        MataPelajaran::create([
            'nama' => $request->nama
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mata pelajaran berhasil ditambahkan.',
            'data' => [
                'nama' => $request->nama
            ]
        ]);
    }

    /**
     * Show import form.
     */
    public function import()
    {
        return view('guru.import');
    }

    /**
     * Download template Excel for import.
     */
    public function downloadTemplate()
    {
        // Create sample data for template
        $sampleData = [
            [
                'nip' => '196501011990031001',
                'nama_lengkap' => 'Dr. Ahmad Rizki, M.Pd',
                'gelar_depan' => 'Dr.',
                'gelar_belakang' => 'M.Pd',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1965-01-01',
                'tempat_lahir' => 'Jakarta',
                'alamat' => 'Jl. Pendidikan No. 123, Jakarta',
                'no_telepon' => '08123456789',
                'no_wa' => '08123456789',
                'email' => 'ahmad.rizki@sekolah.com',
                'status_kepegawaian' => 'PNS',
                'jabatan' => 'Kepala Sekolah',
                'tanggal_masuk' => '1990-03-01',
                'tanggal_keluar' => '',
                'status_aktif' => 'aktif',
                'pendidikan_terakhir' => 'S3 Pendidikan',
                'universitas' => 'Universitas Pendidikan Indonesia',
                'tahun_lulus' => '2010',
                'sertifikasi' => 'Sertifikasi Guru Profesional',
                'mata_pelajaran' => 'Matematika, Fisika',
                'prestasi' => 'Guru Berprestasi Nasional 2020',
                'catatan' => 'Guru senior dengan pengalaman 30 tahun'
            ],
            [
                'nip' => '197803151999032002',
                'nama_lengkap' => 'Siti Nurhaliza, S.Pd',
                'gelar_depan' => '',
                'gelar_belakang' => 'S.Pd',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '1978-03-15',
                'tempat_lahir' => 'Bandung',
                'alamat' => 'Jl. Guru No. 456, Bandung',
                'no_telepon' => '08987654321',
                'no_wa' => '08987654321',
                'email' => 'siti.nurhaliza@sekolah.com',
                'status_kepegawaian' => 'PNS',
                'jabatan' => 'Guru Mata Pelajaran',
                'tanggal_masuk' => '1999-03-01',
                'tanggal_keluar' => '',
                'status_aktif' => 'aktif',
                'pendidikan_terakhir' => 'S1 Pendidikan Bahasa Indonesia',
                'universitas' => 'Universitas Pendidikan Indonesia',
                'tahun_lulus' => '2000',
                'sertifikasi' => 'Sertifikasi Guru Profesional',
                'mata_pelajaran' => 'Bahasa Indonesia, Bahasa Inggris',
                'prestasi' => 'Guru Berprestasi Tingkat Kota 2019',
                'catatan' => 'Guru kreatif dan inovatif'
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
                    'nip',
                    'nama_lengkap',
                    'gelar_depan',
                    'gelar_belakang',
                    'jenis_kelamin',
                    'tanggal_lahir',
                    'tempat_lahir',
                    'alamat',
                    'no_telepon',
                    'no_wa',
                    'email',
                    'status_kepegawaian',
                    'jabatan',
                    'tanggal_masuk',
                    'tanggal_keluar',
                    'status_aktif',
                    'pendidikan_terakhir',
                    'universitas',
                    'tahun_lulus',
                    'sertifikasi',
                    'mata_pelajaran',
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
                    'A' => 20,
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
                    'W' => 30
                ];
            }
        };

        return Excel::download($templateExport, 'template-import-guru.xlsx');
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

            Log::info("Starting guru import process", [
                'file_name' => $fileName,
                'file_size' => $fileSize,
                'user_id' => Auth::id()
            ]);

            // Create import instance
            $import = new GuruImport();

            // Import the file
            Excel::import($import, $file);

            // Get import results
            $importedCount = $import->getRowCount() ?? 0;
            $errors = $import->errors();
            $failures = $import->failures();

            Log::info("Guru import completed", [
                'imported_count' => $importedCount,
                'errors_count' => count($errors),
                'failures_count' => count($failures)
            ]);

            // Prepare success message with details
            $message = "Data guru berhasil diimpor!";
            $details = [];

            if ($importedCount > 0) {
                $details[] = "Berhasil mengimpor {$importedCount} guru";
            }

            if (count($failures) > 0) {
                $details[] = count($failures) . " guru gagal diimpor (cek log untuk detail)";
            }

            if (count($errors) > 0) {
                $details[] = count($errors) . " guru memiliki error validasi";
            }

            if (!empty($details)) {
                $message .= " (" . implode(', ', $details) . ")";
            }

            return redirect()->route('admin.guru.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error("Guru import failed", [
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
        $query = Guru::query();

        // Apply filters
        if ($request->has('status') && $request->status !== '') {
            $query->where('status_aktif', $request->status);
        }

        if ($request->has('employment_status') && $request->employment_status !== '') {
            $query->where('status_kepegawaian', $request->employment_status);
        }

        $gurus = $query->get();

        return Excel::download(new GuruExport($gurus), 'guru-' . date('Y-m-d') . '.xlsx');
    }
}
