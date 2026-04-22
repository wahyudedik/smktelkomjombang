<?php

namespace App\Http\Controllers;

use App\Models\JadwalPelajaran;
use App\Models\MataPelajaran;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JadwalPelajaranExport;
use App\Imports\JadwalPelajaranImport;
use Barryvdh\DomPDF\Facade\Pdf;

class JadwalPelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = JadwalPelajaran::with(['mataPelajaran', 'guru', 'kelas']);

        // Filter by kelas
        if ($request->has('kelas_id') && $request->kelas_id !== '') {
            $query->where('kelas_id', $request->kelas_id);
        }

        // Filter by guru
        if ($request->has('guru_id') && $request->guru_id !== '') {
            $query->where('guru_id', $request->guru_id);
        }

        // Filter by hari
        if ($request->has('hari') && $request->hari !== '') {
            $query->where('hari', $request->hari);
        }

        // Filter by tahun ajaran
        if ($request->has('tahun_ajaran') && $request->tahun_ajaran !== '') {
            $query->where('tahun_ajaran', $request->tahun_ajaran);
        }

        // Filter by semester
        if ($request->has('semester') && $request->semester !== '') {
            $query->where('semester', $request->semester);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('mataPelajaran', function ($mq) use ($search) {
                    $mq->where('nama', 'like', '%' . $search . '%');
                })
                    ->orWhereHas('guru', function ($gq) use ($search) {
                        $gq->where('nama_lengkap', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('kelas', function ($kq) use ($search) {
                        $kq->where('nama', 'like', '%' . $search . '%');
                    })
                    ->orWhere('ruang', 'like', '%' . $search . '%');
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'hari');
        $sortOrder = $request->get('sort_order', 'asc');

        if ($sortBy === 'hari') {
            // Custom sort for hari
            $hariOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            $query->orderByRaw("FIELD(hari, '" . implode("','", $hariOrder) . "')");
            $query->orderBy('jam_mulai', 'asc');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $jadwals = $query->paginate(20);

        // Get filter options
        $kelasList = Kelas::orderBy('nama')->get();
        $guruList = Guru::active()->orderBy('nama_lengkap')->get();
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $tahunAjaranList = JadwalPelajaran::distinct()->pluck('tahun_ajaran')->toArray();
        $semesterList = ['Ganjil', 'Genap'];
        $statusList = ['aktif', 'nonaktif'];

        return view('jadwal-pelajaran.index', compact(
            'jadwals',
            'kelasList',
            'guruList',
            'hariList',
            'tahunAjaranList',
            'semesterList',
            'statusList'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mataPelajaranList = MataPelajaran::orderBy('nama')->get();
        $guruList = Guru::active()->orderBy('nama_lengkap')->get();
        $kelasList = Kelas::orderBy('nama')->get();
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $semesterList = ['Ganjil', 'Genap'];

        return view('jadwal-pelajaran.create', compact(
            'mataPelajaranList',
            'guruList',
            'kelasList',
            'hariList',
            'semesterList'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'guru_id' => 'required|exists:gurus,id',
            'kelas_id' => 'required|exists:kelas,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'ruang' => 'nullable|string|max:100',
            'tahun_ajaran' => 'required|string|max:20',
            'semester' => 'required|in:Ganjil,Genap',
            'catatan' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        try {
            $jadwal = DB::transaction(function () use ($request) {
                $jadwal = JadwalPelajaran::create([
                    'mata_pelajaran_id' => $request->mata_pelajaran_id,
                    'guru_id' => $request->guru_id,
                    'kelas_id' => $request->kelas_id,
                    'hari' => $request->hari,
                    'jam_mulai' => $request->jam_mulai,
                    'jam_selesai' => $request->jam_selesai,
                    'ruang' => $request->ruang,
                    'tahun_ajaran' => $request->tahun_ajaran,
                    'semester' => $request->semester,
                    'catatan' => $request->catatan,
                    'status' => $request->status,
                ]);

                // Check for conflicts
                if ($jadwal->hasConflict()) {
                    throw new \Exception('Jadwal bentrok dengan jadwal lain untuk kelas yang sama!');
                }

                if ($jadwal->guruHasConflict()) {
                    throw new \Exception('Guru sudah memiliki jadwal mengajar di waktu yang sama!');
                }

                // Log the action
                AuditLog::createLog(
                    'jadwal_created',
                    Auth::id(),
                    'JadwalPelajaran',
                    $jadwal->id,
                    null,
                    $jadwal->toArray(),
                    $request->ip(),
                    $request->userAgent()
                );

                return $jadwal;
            });

            return redirect()
                ->route('admin.jadwal-pelajaran.index')
                ->with('success', 'Jadwal pelajaran berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Error creating jadwal: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan jadwal: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(JadwalPelajaran $jadwalPelajaran)
    {
        $jadwalPelajaran->load(['mataPelajaran', 'guru', 'kelas']);
        return view('jadwal-pelajaran.show', compact('jadwalPelajaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JadwalPelajaran $jadwalPelajaran)
    {
        $mataPelajaranList = MataPelajaran::orderBy('nama')->get();
        $guruList = Guru::active()->orderBy('nama_lengkap')->get();
        $kelasList = Kelas::orderBy('nama')->get();
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $semesterList = ['Ganjil', 'Genap'];

        return view('jadwal-pelajaran.edit', compact(
            'jadwalPelajaran',
            'mataPelajaranList',
            'guruList',
            'kelasList',
            'hariList',
            'semesterList'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JadwalPelajaran $jadwalPelajaran)
    {
        $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'guru_id' => 'required|exists:gurus,id',
            'kelas_id' => 'required|exists:kelas,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'ruang' => 'nullable|string|max:100',
            'tahun_ajaran' => 'required|string|max:20',
            'semester' => 'required|in:Ganjil,Genap',
            'catatan' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        try {
            DB::transaction(function () use ($request, $jadwalPelajaran) {
                $oldData = $jadwalPelajaran->toArray();

                $jadwalPelajaran->update([
                    'mata_pelajaran_id' => $request->mata_pelajaran_id,
                    'guru_id' => $request->guru_id,
                    'kelas_id' => $request->kelas_id,
                    'hari' => $request->hari,
                    'jam_mulai' => $request->jam_mulai,
                    'jam_selesai' => $request->jam_selesai,
                    'ruang' => $request->ruang,
                    'tahun_ajaran' => $request->tahun_ajaran,
                    'semester' => $request->semester,
                    'catatan' => $request->catatan,
                    'status' => $request->status,
                ]);

                // Check for conflicts
                if ($jadwalPelajaran->hasConflict()) {
                    throw new \Exception('Jadwal bentrok dengan jadwal lain untuk kelas yang sama!');
                }

                if ($jadwalPelajaran->guruHasConflict()) {
                    throw new \Exception('Guru sudah memiliki jadwal mengajar di waktu yang sama!');
                }

                // Log the action
                AuditLog::createLog(
                    'jadwal_updated',
                    Auth::id(),
                    'JadwalPelajaran',
                    $jadwalPelajaran->id,
                    $oldData,
                    $jadwalPelajaran->toArray(),
                    $request->ip(),
                    $request->userAgent()
                );
            });

            return redirect()
                ->route('admin.jadwal-pelajaran.index')
                ->with('success', 'Jadwal pelajaran berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Error updating jadwal: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui jadwal: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JadwalPelajaran $jadwalPelajaran)
    {
        try {
            $oldData = $jadwalPelajaran->toArray();
            $jadwalPelajaran->delete();

            // Log the action
            AuditLog::createLog(
                'jadwal_deleted',
                Auth::id(),
                'JadwalPelajaran',
                $jadwalPelajaran->id,
                $oldData,
                null,
                request()->ip(),
                request()->userAgent()
            );

            return redirect()
                ->route('admin.jadwal-pelajaran.index')
                ->with('success', 'Jadwal pelajaran berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error deleting jadwal: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus jadwal pelajaran!');
        }
    }

    /**
     * Export jadwal to Excel.
     */
    public function export(Request $request)
    {
        $filters = [
            'kelas_id' => $request->kelas_id,
            'guru_id' => $request->guru_id,
            'tahun_ajaran' => $request->tahun_ajaran,
            'semester' => $request->semester,
        ];

        $filename = 'jadwal-pelajaran-' . date('Y-m-d-His') . '.xlsx';

        return Excel::download(new JadwalPelajaranExport($filters), $filename);
    }

    /**
     * Import jadwal from Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            DB::transaction(function () use ($request) {
                Excel::import(new JadwalPelajaranImport, $request->file('file'));
            });

            return redirect()
                ->route('admin.jadwal-pelajaran.index')
                ->with('success', 'Jadwal pelajaran berhasil diimpor!');
        } catch (\Exception $e) {
            Log::error('Error importing jadwal: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Gagal mengimpor jadwal: ' . $e->getMessage());
        }
    }

    /**
     * Get jadwal by kelas in calendar format.
     */
    public function calendar(Request $request)
    {
        $kelasId = $request->get('kelas_id');
        $tahunAjaran = $request->get('tahun_ajaran', date('Y') . '/' . (date('Y') + 1));
        $semester = $request->get('semester', 'Ganjil');

        $jadwals = JadwalPelajaran::with(['mataPelajaran', 'guru', 'kelas'])
            ->when($kelasId, function ($query) use ($kelasId) {
                return $query->where('kelas_id', $kelasId);
            })
            ->where('tahun_ajaran', $tahunAjaran)
            ->where('semester', $semester)
            ->where('status', 'aktif')
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
            ->orderBy('jam_mulai')
            ->get()
            ->groupBy('hari');

        $kelasList = Kelas::orderBy('nama')->get();
        $tahunAjaranList = JadwalPelajaran::distinct()->pluck('tahun_ajaran')->toArray();

        return view('jadwal-pelajaran.calendar', compact(
            'jadwals',
            'kelasList',
            'kelasId',
            'tahunAjaran',
            'semester',
            'tahunAjaranList'
        ));
    }

    /**
     * Check for schedule conflicts.
     */
    public function checkConflict(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'guru_id' => 'required|exists:gurus,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'tahun_ajaran' => 'required|string',
            'semester' => 'required|in:Ganjil,Genap',
            'jadwal_id' => 'nullable|exists:jadwal_pelajaran,id',
        ]);

        // Create temporary jadwal for conflict checking
        $tempJadwal = new JadwalPelajaran([
            'id' => $request->jadwal_id ?? 0,
            'kelas_id' => $request->kelas_id,
            'guru_id' => $request->guru_id,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'tahun_ajaran' => $request->tahun_ajaran,
            'semester' => $request->semester,
            'status' => 'aktif',
        ]);

        $kelasConflict = $tempJadwal->hasConflict();
        $guruConflict = $tempJadwal->guruHasConflict();

        return response()->json([
            'has_conflict' => $kelasConflict || $guruConflict,
            'kelas_conflict' => $kelasConflict,
            'guru_conflict' => $guruConflict,
            'message' => $kelasConflict
                ? 'Jadwal bentrok dengan jadwal lain untuk kelas yang sama!'
                : ($guruConflict
                    ? 'Guru sudah memiliki jadwal mengajar di waktu yang sama!'
                    : 'Tidak ada bentrok jadwal.')
        ]);
    }

    /**
     * Export jadwal to PDF.
     */
    public function exportPdf(Request $request)
    {
        $query = JadwalPelajaran::with(['mataPelajaran', 'guru', 'kelas']);

        // Apply filters
        if ($request->has('kelas_id') && $request->kelas_id !== '') {
            $query->where('kelas_id', $request->kelas_id);
        }

        if ($request->has('guru_id') && $request->guru_id !== '') {
            $query->where('guru_id', $request->guru_id);
        }

        if ($request->has('tahun_ajaran') && $request->tahun_ajaran !== '') {
            $query->where('tahun_ajaran', $request->tahun_ajaran);
        }

        if ($request->has('semester') && $request->semester !== '') {
            $query->where('semester', $request->semester);
        }

        $jadwals = $query->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
            ->orderBy('jam_mulai')
            ->get()
            ->groupBy('hari');

        $pdf = Pdf::loadView('jadwal-pelajaran.pdf', compact('jadwals'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('jadwal-pelajaran-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export jadwal to JSON.
     */
    public function exportJson(Request $request)
    {
        $query = JadwalPelajaran::with(['mataPelajaran', 'guru', 'kelas']);

        // Apply filters
        if ($request->has('kelas_id') && $request->kelas_id !== '') {
            $query->where('kelas_id', $request->kelas_id);
        }

        if ($request->has('tahun_ajaran') && $request->tahun_ajaran !== '') {
            $query->where('tahun_ajaran', $request->tahun_ajaran);
        }

        $jadwals = $query->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
            ->orderBy('jam_mulai')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $jadwals,
            'total' => $jadwals->count(),
            'exported_at' => now()->toIso8601String()
        ]);
    }

    /**
     * Export jadwal to XML.
     */
    public function exportXml(Request $request)
    {
        $query = JadwalPelajaran::with(['mataPelajaran', 'guru', 'kelas']);

        // Apply filters
        if ($request->has('kelas_id') && $request->kelas_id !== '') {
            $query->where('kelas_id', $request->kelas_id);
        }

        $jadwals = $query->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
            ->orderBy('jam_mulai')
            ->get();

        $xml = new \SimpleXMLElement('<jadwal_pelajaran/>');
        $xml->addAttribute('exported_at', now()->toIso8601String());
        $xml->addAttribute('total', $jadwals->count());

        foreach ($jadwals as $jadwal) {
            $jadwalNode = $xml->addChild('jadwal');
            $jadwalNode->addChild('id', $jadwal->id);
            $jadwalNode->addChild('mata_pelajaran', htmlspecialchars($jadwal->mataPelajaran->nama ?? ''));
            $jadwalNode->addChild('guru', htmlspecialchars($jadwal->guru->nama_lengkap ?? ''));
            $jadwalNode->addChild('kelas', htmlspecialchars($jadwal->kelas->nama ?? ''));
            $jadwalNode->addChild('hari', htmlspecialchars($jadwal->hari));
            $jadwalNode->addChild('jam_mulai', $jadwal->jam_mulai);
            $jadwalNode->addChild('jam_selesai', $jadwal->jam_selesai);
            $jadwalNode->addChild('ruang', htmlspecialchars($jadwal->ruang ?? ''));
            $jadwalNode->addChild('tahun_ajaran', $jadwal->tahun_ajaran);
            $jadwalNode->addChild('semester', $jadwal->semester);
            $jadwalNode->addChild('status', $jadwal->status);
        }

        return response($xml->asXML(), 200)
            ->header('Content-Type', 'application/xml')
            ->header('Content-Disposition', 'attachment; filename="jadwal-pelajaran-' . date('Y-m-d') . '.xml"');
    }
}
