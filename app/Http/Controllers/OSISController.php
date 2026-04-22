<?php

namespace App\Http\Controllers;

use App\Models\Calon;
use App\Models\Pemilih;
use App\Models\Voting;
use App\Models\Siswa;
use App\Models\OsisElection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CalonImport;
use App\Exports\CalonExport;
use App\Imports\PemilihImport;
use App\Exports\PemilihExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class OSISController extends Controller
{
    /**
     * Display OSIS dashboard.
     */
    public function index()
    {
        // Cache stats for 2 minutes (voting data changes frequently)
        $stats = cache()->remember('osis_dashboard_stats', 120, function () {
            return [
                'total_calon' => Calon::active()->count(),
                'total_pemilih' => Pemilih::active()->count(),
                'total_votes' => Voting::valid()->count(),
                'voting_percentage' => $this->getVotingPercentage(),
            ];
        });

        // Don't cache calons and votes - needs to be fresh
        $calons = Calon::active()->ordered()->withCount('votings')->get();
        $recentVotes = Voting::with(['calon', 'pemilih'])
            ->valid()
            ->latest()
            ->limit(10)
            ->get();

        return view('osis.index', compact('stats', 'calons', 'recentVotes'));
    }

    /**
     * Display calon management.
     */
    public function calonIndex(Request $request)
    {
        $query = Calon::query();

        // Filter by status (lebih robust: check filled)
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by pencalonan type (lebih robust: check filled)
        if ($request->filled('jenis_pencalonan')) {
            $query->where('jenis_pencalonan', $request->jenis_pencalonan);
        }

        // Search by name (lebih robust: check filled dan trim)
        if ($request->filled('search')) {
            $search = trim($request->search);
            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_ketua', 'like', '%' . $search . '%')
                        ->orWhere('nama_wakil', 'like', '%' . $search . '%');
                });
            }
        }

        $calons = $query->withCount('votings')->paginate(15)->withQueryString(); // Preserve query string saat pagination
        $pencalonanTypes = ['ketua', 'wakil', 'pasangan'];

        return view('osis.calon.index', compact('calons', 'pencalonanTypes'));
    }

    /**
     * Show the form for creating a new calon.
     */
    public function createCalon()
    {
        // Get active students for dropdown (cached for 5 minutes)
        $siswas = cache()->remember('active_siswas_dropdown', 300, function () {
            return \App\Models\Siswa::where('status', 'aktif')
                ->orderBy('nama_lengkap')
                ->get(['id', 'nama_lengkap', 'nis', 'kelas', 'email', 'jenis_kelamin']);
        });

        // Get unique classes for dropdown (cached for 10 minutes)
        $kelas = cache()->remember('active_siswa_kelas', 600, function () {
            return \App\Models\Siswa::where('status', 'aktif')
                ->whereNotNull('kelas')
                ->distinct()
                ->pluck('kelas')
                ->sort()
                ->values();
        });

        return view('osis.calon.create', compact('siswas', 'kelas'));
    }

    /**
     * Store a newly created calon.
     */
    public function storeCalon(Request $request)
    {
        $request->validate([
            'nama_ketua' => 'required|string|max:255',
            'foto_ketua' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nama_wakil' => 'nullable|string|max:255',
            'foto_wakil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'jenis_kelamin' => 'required|in:L,P',
            'visi_misi' => 'required|string',
            'jenis_pencalonan' => 'required|in:ketua,wakil,pasangan',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();

        // Handle photo uploads
        if ($request->hasFile('foto_ketua')) {
            $data['foto_ketua'] = $request->file('foto_ketua')->store('osis/calon', 'public');
        }
        if ($request->hasFile('foto_wakil')) {
            $data['foto_wakil'] = $request->file('foto_wakil')->store('osis/calon', 'public');
        }

        Calon::create($data);

        // Clear cache for OSIS stats and dropdowns
        cache()->forget('osis_dashboard_stats');
        cache()->forget('active_siswas_dropdown');
        cache()->forget('active_siswa_kelas');

        return redirect()->route('admin.osis.calon.index')
            ->with('success', 'Calon berhasil ditambahkan.');
    }

    /**
     * Display the specified calon.
     */
    public function showCalon(Calon $calon)
    {
        $calon->loadCount('votings');
        return view('osis.calon.show', compact('calon'));
    }

    /**
     * Show the form for editing the specified calon.
     */
    public function editCalon(Calon $calon)
    {
        // Get active students for dropdown (cached for 5 minutes)
        $siswas = cache()->remember('active_siswas_dropdown', 300, function () {
            return \App\Models\Siswa::where('status', 'aktif')
                ->orderBy('nama_lengkap')
                ->get(['id', 'nama_lengkap', 'nis', 'kelas', 'email', 'jenis_kelamin']);
        });

        // Get unique classes for dropdown (cached for 10 minutes)
        $kelas = cache()->remember('active_siswa_kelas', 600, function () {
            return \App\Models\Siswa::where('status', 'aktif')
                ->whereNotNull('kelas')
                ->distinct()
                ->pluck('kelas')
                ->sort()
                ->values();
        });

        return view('osis.calon.edit', compact('calon', 'siswas', 'kelas'));
    }

    /**
     * Update the specified calon.
     */
    public function updateCalon(Request $request, Calon $calon)
    {
        $request->validate([
            'nama_ketua' => 'required|string|max:255',
            'foto_ketua' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nama_wakil' => 'nullable|string|max:255',
            'foto_wakil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'jenis_kelamin' => 'required|in:L,P',
            'visi_misi' => 'required|string',
            'jenis_pencalonan' => 'required|in:ketua,wakil,pasangan',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();

        // Handle photo uploads
        if ($request->hasFile('foto_ketua')) {
            if ($calon->foto_ketua) {
                Storage::disk('public')->delete($calon->foto_ketua);
            }
            $data['foto_ketua'] = $request->file('foto_ketua')->store('osis/calon', 'public');
        }
        if ($request->hasFile('foto_wakil')) {
            if ($calon->foto_wakil) {
                Storage::disk('public')->delete($calon->foto_wakil);
            }
            $data['foto_wakil'] = $request->file('foto_wakil')->store('osis/calon', 'public');
        }

        $calon->update($data);

        // Clear cache for OSIS stats
        cache()->forget('osis_dashboard_stats');

        return redirect()->route('admin.osis.calon.index')
            ->with('success', 'Calon berhasil diperbarui.');
    }

    /**
     * Remove the specified calon.
     */
    public function destroyCalon(Calon $calon)
    {
        // Delete photos
        if ($calon->foto_ketua) {
            Storage::disk('public')->delete($calon->foto_ketua);
        }
        if ($calon->foto_wakil) {
            Storage::disk('public')->delete($calon->foto_wakil);
        }

        $calon->delete();

        // Clear cache for OSIS stats
        cache()->forget('osis_dashboard_stats');

        return redirect()->route('admin.osis.calon.index')
            ->with('success', 'Calon berhasil dihapus.');
    }

    /**
     * Display pemilih management.
     */
    public function pemilihIndex(Request $request)
    {
        $query = Pemilih::query();

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'sudah_memilih') {
                $query->sudahMemilih();
            } elseif ($request->status === 'belum_memilih') {
                $query->belumMemilih();
            }
        }

        // Filter by class
        if ($request->has('kelas') && $request->kelas !== '') {
            $query->kelas($request->kelas);
        }

        // Search by name or NIS
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('nis', 'like', '%' . $search . '%');
            });
        }

        $pemilihs = $query->orderBy('nama')->paginate(15);
        $kelas = $this->getAvailableClasses();

        return view('osis.pemilih.index', compact('pemilihs', 'kelas'));
    }

    /**
     * Show the form for creating a new pemilih.
     */
    public function createPemilih()
    {
        $kelas = $this->getAvailableClasses();
        return view('osis.pemilih.create', compact('kelas'));
    }

    /**
     * Store a newly created pemilih.
     */
    public function storePemilih(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|unique:pemilihs,nis',
            'kelas' => 'required|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'email' => 'nullable|email|max:255',
            'nomor_hp' => 'nullable|regex:/^[\d+\-\s()]+$/|min:10|max:20',
            'alamat' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Handle status checkbox
        $data = $request->all();
        if ($request->has('status_sudah_memilih') && $request->status_sudah_memilih == '1') {
            $data['status'] = 'sudah_memilih';
            $data['waktu_memilih'] = now();
        } else {
            $data['status'] = 'belum_memilih';
            $data['waktu_memilih'] = null;
        }
        unset($data['status_sudah_memilih']); // Remove the checkbox field

        Pemilih::create($data);

        return redirect()->route('admin.osis.pemilih.index')
            ->with('success', 'Pemilih berhasil ditambahkan.');
    }

    /**
     * Display the specified pemilih.
     */
    public function showPemilih(Pemilih $pemilih)
    {
        $pemilih->load('votings.calon');
        return view('osis.pemilih.show', compact('pemilih'));
    }

    /**
     * Show the form for editing the specified pemilih.
     */
    public function editPemilih(Pemilih $pemilih)
    {
        $kelas = $this->getAvailableClasses();
        return view('osis.pemilih.edit', compact('pemilih', 'kelas'));
    }

    /**
     * Update the specified pemilih.
     */
    public function updatePemilih(Request $request, Pemilih $pemilih)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|unique:pemilihs,nis,' . $pemilih->id,
            'kelas' => 'required|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'email' => 'nullable|email|max:255',
            'nomor_hp' => 'nullable|regex:/^[\d+\-\s()]+$/|min:10|max:20',
            'alamat' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Handle status checkbox
        $data = $request->all();
        if ($request->has('status_sudah_memilih') && $request->status_sudah_memilih == '1') {
            $data['status'] = 'sudah_memilih';
            $data['waktu_memilih'] = now();
        } else {
            $data['status'] = 'belum_memilih';
            $data['waktu_memilih'] = null;
        }
        unset($data['status_sudah_memilih']); // Remove the checkbox field

        $pemilih->update($data);

        return redirect()->route('admin.osis.pemilih.index')
            ->with('success', 'Pemilih berhasil diperbarui.');
    }

    /**
     * Remove the specified pemilih.
     */
    public function destroyPemilih(Pemilih $pemilih)
    {
        $pemilih->delete();

        return redirect()->route('admin.osis.pemilih.index')
            ->with('success', 'Pemilih berhasil dihapus.');
    }

    /**
     * Display voting interface.
     */
    public function voting()
    {
        // Check if user is a student
        $user = Auth::user();
        if (!$user->hasRole('siswa')) {
            return redirect()->route('admin.osis.index')
                ->with('error', 'Hanya siswa yang dapat memilih. Silakan login sebagai siswa untuk melakukan voting.');
        }

        // Get student data
        $siswa = Siswa::where('user_id', $user->id)->first();
        if (!$siswa) {
            return redirect()->route('admin.osis.index')
                ->with('error', 'Data siswa tidak ditemukan. Silakan hubungi administrator.');
        }

        // Check if student has already voted
        if ($siswa->hasVotedOsis()) {
            return redirect()->route('admin.osis.results')
                ->with('info', 'Anda sudah memilih dalam pemilihan OSIS ini.');
        }

        // Get active election
        $election = OsisElection::active()->first();
        if (!$election) {
            return redirect()->route('admin.osis.index')
                ->with('error', 'Tidak ada pemilihan OSIS yang sedang berlangsung.');
        }

        // Check if student's class is allowed to vote
        if ($election->allowed_classes && !in_array($siswa->kelas, $election->allowed_classes)) {
            return redirect()->route('admin.osis.index')
                ->with('error', 'Kelas Anda tidak diizinkan untuk memilih dalam pemilihan ini.');
        }

        // Filter candidates based on student's gender
        $query = $election->candidates()->active()->ordered();

        // For students, filter by gender (calon cewek tampil untuk siswa cewek, calon cowok untuk siswa cowok)
        if ($siswa->jenis_kelamin) {
            $calons = $query->byGender($siswa->jenis_kelamin)->get();
        } else {
            $calons = $query->get();
        }

        return view('osis.voting', compact('calons', 'siswa', 'election'));
    }

    /**
     * Process vote.
     */
    public function processVote(Request $request)
    {
        $request->validate([
            'calon_id' => 'required|exists:calons,id',
        ]);

        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->first();

        if (!$siswa) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Data siswa tidak ditemukan.');
        }

        if ($siswa->hasVotedOsis()) {
            return redirect()->route('admin.osis.voting')
                ->with('error', 'Anda sudah memilih dalam pemilihan OSIS ini.');
        }

        // Get active election
        $election = OsisElection::active()->first();
        if (!$election) {
            return redirect()->route('admin.osis.index')
                ->with('error', 'Tidak ada pemilihan OSIS yang sedang berlangsung.');
        }

        $calon = Calon::findOrFail($request->calon_id);

        // Validate gender for students (guru can vote for any candidate, siswa can only vote for same gender)
        if ($user->hasRole('siswa') && $calon->jenis_kelamin && $siswa->jenis_kelamin !== $calon->jenis_kelamin) {
            return redirect()->route('admin.osis.voting')
                ->with('error', 'Anda hanya dapat memilih calon yang sesuai dengan jenis kelamin Anda.');
        }

        // Create vote record
        Voting::create([
            'calon_id' => $calon->id,
            'pemilih_id' => null, // We'll use student ID instead
            'siswa_id' => $siswa->id,
            'election_id' => $election->id,
            'voted_at' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'is_valid' => true,
        ]);

        // Mark student as voted
        $siswa->markAsVoted($request->ip(), $request->userAgent());

        return redirect()->route('admin.osis.results')
            ->with('success', 'Terima kasih! Suara Anda telah tercatat.');
    }

    /**
     * Display voting results.
     */
    public function results()
    {
        $calons = Calon::active()->ordered()->withCount('votings')->get();
        $totalVotes = Voting::valid()->count();
        $totalPemilih = Pemilih::active()->count();
        $sudahMemilih = Pemilih::sudahMemilih()->count();
        $belumMemilih = Pemilih::belumMemilih()->count();
        $votingPercentage = $totalPemilih > 0 ? round(($totalVotes / $totalPemilih) * 100, 2) : 0;
        $recentVotes = Voting::with(['calon', 'pemilih'])
            ->valid()
            ->latest()
            ->limit(5)
            ->get();

        return view('osis.results', compact('calons', 'totalVotes', 'totalPemilih', 'sudahMemilih', 'belumMemilih', 'votingPercentage', 'recentVotes'));
    }

    /**
     * Display all candidates for teachers (no gender filter).
     */
    public function teacherView()
    {
        // Check if user is a teacher or admin
        $user = Auth::user();
        if (!$user->hasAnyRole(['guru', 'admin', 'superadmin'])) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Hanya guru dan admin yang dapat mengakses halaman ini.');
        }

        // Get active election
        $election = OsisElection::active()->first();
        if (!$election) {
            return redirect()->route('admin.osis.index')
                ->with('error', 'Tidak ada pemilihan OSIS yang sedang berlangsung.');
        }

        // Get all candidates (no gender filter for teachers and admins)
        $calons = $election->candidates()->active()->ordered()->get();

        return view('osis.teacher-view', compact('calons', 'election'));
    }

    /**
     * Display voting analytics.
     */
    public function analytics()
    {
        $stats = [
            'total_calon' => Calon::active()->count(),
            'total_pemilih' => Pemilih::active()->count(),
            'total_votes' => Voting::valid()->count(),
            'voting_percentage' => $this->getVotingPercentage(),
        ];

        $calons = Calon::active()->ordered()->withCount('votings')->get();
        $recentVotes = Voting::with(['calon', 'pemilih'])
            ->valid()
            ->latest()
            ->limit(10)
            ->get();

        return view('osis.analytics', compact('stats', 'calons', 'recentVotes'));
    }

    /**
     * Get available classes.
     */
    private function getAvailableClasses()
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
     * Get voting percentage.
     */
    private function getVotingPercentage(): float
    {
        $totalPemilih = Pemilih::active()->count();
        $totalVotes = Voting::valid()->count();

        if ($totalPemilih === 0) {
            return 0;
        }

        return round(($totalVotes / $totalPemilih) * 100, 2);
    }

    /**
     * Show import form for calon.
     */
    public function importCalon()
    {
        return view('osis.calon.import');
    }

    /**
     * Download template Excel for calon import.
     */
    public function downloadCalonTemplate()
    {
        // Create sample data for template
        $sampleData = [
            [
                'nama_ketua' => 'Ahmad Rizki',
                'nama_wakil' => 'Siti Nurhaliza',
                'jenis_kelamin' => 'L',
                'visi_misi' => 'Mewujudkan OSIS yang berprestasi dan berkarakter. Misi: 1. Meningkatkan prestasi akademik 2. Mengembangkan bakat dan minat siswa 3. Menjalin kerjasama yang baik dengan semua pihak',
                'jenis_pencalonan' => 'pasangan',
                'status_aktif' => 'aktif'
            ],
            [
                'nama_ketua' => 'Budi Santoso',
                'nama_wakil' => '',
                'jenis_kelamin' => 'L',
                'visi_misi' => 'Menjadi ketua OSIS yang melayani dengan hati. Misi: 1. Melayani kebutuhan siswa 2. Menjadi contoh yang baik 3. Membangun komunikasi yang efektif',
                'jenis_pencalonan' => 'ketua',
                'status_aktif' => 'aktif'
            ],
            [
                'nama_ketua' => 'Sari Indah',
                'nama_wakil' => '',
                'jenis_kelamin' => 'P',
                'visi_misi' => 'Menjadi wakil ketua OSIS yang kreatif dan inovatif. Misi: 1. Mengembangkan program kreatif 2. Mendorong inovasi siswa 3. Membangun teamwork yang solid',
                'jenis_pencalonan' => 'wakil',
                'status_aktif' => 'aktif'
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
                    'nama_ketua',
                    'nama_wakil',
                    'jenis_kelamin',
                    'visi_misi',
                    'jenis_pencalonan',
                    'status_aktif'
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
                    'A' => 25,
                    'B' => 25,
                    'C' => 15,
                    'D' => 50,
                    'E' => 20,
                    'F' => 15,
                ];
            }
        };

        return Excel::download($templateExport, 'template-import-calon.xlsx');
    }

    /**
     * Process calon import.
     */
    public function processCalonImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            // Get file info for logging
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();

            Log::info("Starting calon import process", [
                'file_name' => $fileName,
                'file_size' => $fileSize,
                'user_id' => Auth::id()
            ]);

            // Create import instance
            $import = new CalonImport();

            // Import the file
            Excel::import($import, $file);

            // Get import results
            $importedCount = $import->getRowCount() ?? 0;
            $errors = $import->errors();
            $failures = $import->failures();

            Log::info("Calon import completed", [
                'imported_count' => $importedCount,
                'errors_count' => count($errors),
                'failures_count' => count($failures)
            ]);

            // Prepare success message with details
            $message = "Data calon OSIS berhasil diimpor!";
            $details = [];

            if ($importedCount > 0) {
                $details[] = "Berhasil mengimpor {$importedCount} calon";
            }

            if (count($failures) > 0) {
                $details[] = count($failures) . " calon gagal diimpor (cek log untuk detail)";
            }

            if (count($errors) > 0) {
                $details[] = count($errors) . " calon memiliki error validasi";
            }

            if (!empty($details)) {
                $message .= " (" . implode(', ', $details) . ")";
            }

            return redirect()->route('admin.osis.calon.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error("Calon import failed", [
                'error' => $e->getMessage(),
                'file' => $request->file('file')->getClientOriginalName(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }

    /**
     * Export calon data.
     */
    public function exportCalon(Request $request)
    {
        $query = Calon::withCount('votings');

        // Apply filters
        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'aktif') {
                $query->where('is_active', true);
            } elseif ($request->status === 'tidak_aktif') {
                $query->where('is_active', false);
            }
        }

        if ($request->has('jenis_pencalonan') && $request->jenis_pencalonan !== '') {
            $query->where('jenis_pencalonan', $request->jenis_pencalonan);
        }

        $calons = $query->get();

        return Excel::download(new CalonExport($calons), 'calon-osis-' . date('Y-m-d') . '.xlsx');
    }


    /**
     * Auto-generate pemilih from existing guru and siswa.
     */
    public function generatePemilihFromUsers()
    {
        try {
            $createdCount = 0;
            $updatedCount = 0;

            // Import from Siswa
            $siswas = Siswa::with('user')->get();
            foreach ($siswas as $siswa) {
                if ($siswa->user) {
                    $existingPemilih = Pemilih::where('user_id', $siswa->user->id)->first();

                    if (!$existingPemilih) {
                        Pemilih::create([
                            'user_id' => $siswa->user->id,
                            'user_type' => 'siswa', // Keep user_type in pemilihs table for tracking
                            'nama' => $siswa->nama_lengkap,
                            'nis' => $siswa->nis,
                            'nisn' => $siswa->nisn,
                            'kelas' => $siswa->kelas,
                            'jenis_kelamin' => $siswa->jenis_kelamin,
                            'email' => $siswa->email,
                            'nomor_hp' => $siswa->no_telepon,
                            'alamat' => $siswa->alamat,
                            'status' => 'belum_memilih',
                            'is_active' => true,
                        ]);
                        $createdCount++;
                    } else {
                        // Update existing pemilih data
                        $existingPemilih->update([
                            'nama' => $siswa->nama_lengkap,
                            'nis' => $siswa->nis,
                            'nisn' => $siswa->nisn,
                            'kelas' => $siswa->kelas,
                            'jenis_kelamin' => $siswa->jenis_kelamin,
                            'email' => $siswa->email,
                            'nomor_hp' => $siswa->no_telepon,
                            'alamat' => $siswa->alamat,
                        ]);
                        $updatedCount++;
                    }
                }
            }

            // Import from Guru
            $gurus = \App\Models\Guru::with('user')->get();
            foreach ($gurus as $guru) {
                if ($guru->user) {
                    $existingPemilih = Pemilih::where('user_id', $guru->user->id)->first();

                    if (!$existingPemilih) {
                        Pemilih::create([
                            'user_id' => $guru->user->id,
                            'user_type' => 'guru', // Keep user_type in pemilihs table for tracking
                            'nama' => $guru->nama_lengkap,
                            'nis' => $guru->nip, // Guru menggunakan NIP sebagai identifier
                            'nisn' => null,
                            'kelas' => 'Guru', // Guru tidak punya kelas, set default
                            'jenis_kelamin' => $guru->jenis_kelamin,
                            'email' => $guru->email,
                            'nomor_hp' => $guru->no_telepon,
                            'alamat' => $guru->alamat,
                            'status' => 'belum_memilih',
                            'is_active' => true,
                        ]);
                        $createdCount++;
                    } else {
                        // Update existing pemilih data
                        $existingPemilih->update([
                            'nama' => $guru->nama_lengkap,
                            'nis' => $guru->nip,
                            'kelas' => 'Guru',
                            'jenis_kelamin' => $guru->jenis_kelamin,
                            'email' => $guru->email,
                            'nomor_hp' => $guru->no_telepon,
                            'alamat' => $guru->alamat,
                        ]);
                        $updatedCount++;
                    }
                }
            }

            return redirect()->route('admin.osis.pemilih.index')
                ->with('success', "Pemilih berhasil dibuat otomatis! Dibuat: {$createdCount}, Diupdate: {$updatedCount}");
        } catch (\Exception $e) {
            Log::error('Error generating pemilih from users: ' . $e->getMessage());
            return redirect()->route('admin.osis.pemilih.index')
                ->with('error', 'Terjadi kesalahan saat membuat pemilih: ' . $e->getMessage());
        }
    }

    /**
     * Show import form for pemilih.
     */
    public function importPemilih()
    {
        return view('osis.pemilih.import');
    }

    /**
     * Download template Excel for pemilih import.
     */
    public function downloadPemilihTemplate()
    {
        // Create sample data for template
        $sampleData = [
            [
                'nama' => 'Ahmad Rizki',
                'email' => 'ahmad.rizki@email.com',
                'user_type' => 'siswa', // Keep user_type in pemilihs table for tracking
                'jenis_kelamin' => 'L',
                'kelas_jabatan' => 'XII IPA 1',
                'status' => 'active',
            ],
            [
                'nama' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@email.com',
                'user_type' => 'siswa', // Keep user_type in pemilihs table for tracking
                'jenis_kelamin' => 'P',
                'kelas_jabatan' => 'XI IPS 2',
                'status' => 'active',
            ],
            [
                'nama' => 'Dr. Budi Santoso, S.Pd',
                'email' => 'budi.santoso@email.com',
                'user_type' => 'guru', // Keep user_type in pemilihs table for tracking
                'jenis_kelamin' => 'L',
                'kelas_jabatan' => 'Wali Kelas XII',
                'status' => 'active',
            ],
        ];

        $templateExport = new class($sampleData) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithStyles, \Maatwebsite\Excel\Concerns\WithColumnWidths {
            private $data;

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
                    'nama',
                    'email',
                    'user_type',
                    'jenis_kelamin',
                    'kelas_jabatan',
                    'status'
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
                    'A' => 30,
                    'B' => 30,
                    'C' => 15,
                    'D' => 15,
                    'E' => 25,
                    'F' => 12,
                ];
            }
        };

        return Excel::download($templateExport, 'template-import-pemilih.xlsx');
    }

    /**
     * Process pemilih import.
     */
    public function processPemilihImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            // Get file info for logging
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();

            Log::info("Starting pemilih import process", [
                'file_name' => $fileName,
                'file_size' => $fileSize,
                'user_id' => Auth::id()
            ]);

            // Create import instance
            $import = new PemilihImport();

            // Import the file
            Excel::import($import, $file);

            // Get import results
            $importedCount = $import->getRowCount() ?? 0;
            $errors = $import->errors();
            $failures = $import->failures();

            Log::info("Pemilih import completed", [
                'imported_count' => $importedCount,
                'errors_count' => count($errors),
                'failures_count' => count($failures)
            ]);

            // Prepare success message with details
            $message = "Data pemilih berhasil diimpor!";
            $details = [];

            if ($importedCount > 0) {
                $details[] = "Berhasil mengimpor {$importedCount} pemilih";
            }

            if (count($failures) > 0) {
                $details[] = count($failures) . " pemilih gagal diimpor (cek log untuk detail)";
            }

            if (count($errors) > 0) {
                $details[] = count($errors) . " pemilih memiliki error validasi";
            }

            if (!empty($details)) {
                $message .= " (" . implode(', ', $details) . ")";
            }

            return redirect()->route('admin.osis.pemilih.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error("Pemilih import failed", [
                'error' => $e->getMessage(),
                'file' => $request->file('file')->getClientOriginalName(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }

    /**
     * Export pemilih data.
     */
    public function exportPemilih(Request $request)
    {
        $query = Pemilih::with('user');

        // Apply filters
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('user_type') && $request->user_type !== '') {
            $query->where('user_type', $request->user_type);
        }

        if ($request->has('has_voted') && $request->has_voted !== '') {
            $hasVoted = $request->has_voted === '1' || $request->has_voted === 'yes';
            $query->where('has_voted', $hasVoted);
        }

        $pemilihs = $query->get();

        return Excel::download(new PemilihExport($pemilihs), 'pemilih-osis-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export voting results to PDF.
     */
    public function exportVotingResultsPdf(Request $request)
    {
        $electionId = $request->election_id;

        $election = OsisElection::findOrFail($electionId);

        $results = Calon::where('election_id', $electionId)
            ->withCount('votings')
            ->orderBy('votings_count', 'desc')
            ->get();

        $totalVoters = Pemilih::where('election_id', $electionId)->count();
        $totalVoted = Voting::where('election_id', $electionId)->count();
        $votePercentage = $totalVoters > 0 ? ($totalVoted / $totalVoters) * 100 : 0;

        $pdf = Pdf::loadView('osis.voting-results-pdf', compact('election', 'results', 'totalVoters', 'totalVoted', 'votePercentage'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('hasil-voting-osis-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export voting results to JSON.
     */
    public function exportVotingResultsJson(Request $request)
    {
        $electionId = $request->election_id;

        $election = OsisElection::findOrFail($electionId);

        $results = Calon::where('election_id', $electionId)
            ->withCount('votings')
            ->with(['siswa'])
            ->orderBy('votings_count', 'desc')
            ->get();

        $totalVoters = Pemilih::where('election_id', $electionId)->count();
        $totalVoted = Voting::where('election_id', $electionId)->count();

        return response()->json([
            'success' => true,
            'election' => [
                'id' => $election->id,
                'nama' => $election->nama,
                'tahun' => $election->tahun,
                'status' => $election->status
            ],
            'statistics' => [
                'total_voters' => $totalVoters,
                'total_voted' => $totalVoted,
                'vote_percentage' => $totalVoters > 0 ? round(($totalVoted / $totalVoters) * 100, 2) : 0
            ],
            'results' => $results->map(function ($calon) {
                return [
                    'id' => $calon->id,
                    'nama' => $calon->siswa->nama_lengkap ?? $calon->nama,
                    'visi' => $calon->visi,
                    'misi' => $calon->misi,
                    'total_votes' => $calon->votings_count,
                    'vote_percentage' => Voting::where('calon_id', $calon->id)->count()
                ];
            }),
            'exported_at' => now()->toIso8601String()
        ]);
    }

    /**
     * Export voting results to XML.
     */
    public function exportVotingResultsXml(Request $request)
    {
        $electionId = $request->election_id;

        $election = OsisElection::findOrFail($electionId);

        $results = Calon::where('election_id', $electionId)
            ->withCount('votings')
            ->with(['siswa'])
            ->orderBy('votings_count', 'desc')
            ->get();

        $totalVoters = Pemilih::where('election_id', $electionId)->count();
        $totalVoted = Voting::where('election_id', $electionId)->count();

        $xml = new \SimpleXMLElement('<voting_results/>');
        $xml->addAttribute('exported_at', now()->toIso8601String());

        $electionNode = $xml->addChild('election');
        $electionNode->addChild('id', $election->id);
        $electionNode->addChild('nama', htmlspecialchars($election->nama));
        $electionNode->addChild('tahun', $election->tahun);
        $electionNode->addChild('status', $election->status);

        $statsNode = $xml->addChild('statistics');
        $statsNode->addChild('total_voters', $totalVoters);
        $statsNode->addChild('total_voted', $totalVoted);
        $statsNode->addChild('vote_percentage', $totalVoters > 0 ? round(($totalVoted / $totalVoters) * 100, 2) : 0);

        $resultsNode = $xml->addChild('results');
        foreach ($results as $calon) {
            $calonNode = $resultsNode->addChild('candidate');
            $calonNode->addChild('id', $calon->id);
            $calonNode->addChild('nama', htmlspecialchars($calon->siswa->nama_lengkap ?? $calon->nama));
            $calonNode->addChild('total_votes', $calon->votings_count);
        }

        return response($xml->asXML(), 200)
            ->header('Content-Type', 'application/xml')
            ->header('Content-Disposition', 'attachment; filename="hasil-voting-osis-' . date('Y-m-d') . '.xml"');
    }
}
