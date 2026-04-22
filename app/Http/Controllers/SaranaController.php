<?php

namespace App\Http\Controllers;

use App\Models\Sarana;
use App\Models\Ruang;
use App\Models\Barang;
use App\Models\KategoriSarpras;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SaranaExport;
use App\Imports\SaranaImport;

class SaranaController extends Controller
{
    /**
     * Display a listing of the sarana.
     */
    public function index(Request $request)
    {
        $query = Sarana::with(['ruang', 'barang.kategori']);

        // Filter by kategori
        if ($request->filled('kategori_id')) {
            $query->kategori($request->kategori_id);
        }

        // Filter by sumber dana
        if ($request->filled('sumber_dana')) {
            $query->sumberDana($request->sumber_dana);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_inventaris', 'like', "%{$search}%")
                    ->orWhere('sumber_dana', 'like', "%{$search}%")
                    ->orWhere('kode_sumber_dana', 'like', "%{$search}%")
                    ->orWhereHas('ruang', function ($r) use ($search) {
                        $r->where('nama_ruang', 'like', "%{$search}%");
                    })
                    ->orWhereHas('barang', function ($b) use ($search) {
                        $b->where('nama_barang', 'like', "%{$search}%");
                    });
            });
        }

        $saranas = $query->latest('tanggal')->paginate(15);
        $kategoris = KategoriSarpras::active()->orderBy('nama_kategori')->get();
        $sumberDanas = Sarana::distinct()->whereNotNull('sumber_dana')->pluck('sumber_dana')->sort()->values();

        return view('sarpras.sarana.index', compact('saranas', 'kategoris', 'sumberDanas'));
    }

    /**
     * Show the form for creating a new sarana.
     */
    public function create(Request $request)
    {
        $ruangs = Ruang::active()->orderBy('nama_ruang')->get();
        
        // Get all available barang (allow multiple sarana with same barang - dinamis untuk dana berbeda)
        $barangs = Barang::with('kategori')
            ->active()
            ->orderBy('nama_barang')
            ->get();
        
        $kategoris = KategoriSarpras::active()->orderBy('nama_kategori')->get();

        // Prepare barang data for JavaScript
        $barangsJson = $barangs->map(function($b) {
            return [
                'id' => $b->id,
                'nama_barang' => $b->nama_barang,
                'kode_barang' => $b->kode_barang,
                'kategori' => $b->kategori->nama_kategori ?? '-',
                'ruang_id' => $b->ruang_id, // Include ruang_id to check if barang has room
                'harga_beli' => $b->harga_beli ?? 0, // Include harga
                'kondisi' => $b->kondisi ?? 'baik', // Include kondisi dari master data
            ];
        })->values()->all();

        // Pre-fill ruang_id if provided
        $prefilledRuangId = $request->get('ruang_id');
        $prefilledBarangId = $request->get('barang_id');

        return view('sarpras.sarana.create', compact('ruangs', 'barangs', 'kategoris', 'barangsJson', 'prefilledRuangId', 'prefilledBarangId'));
    }

    /**
     * Store a newly created sarana.
     */
    public function store(Request $request)
    {
        \Log::info('Sarana store method called', [
            'request_data' => $request->all(),
            'barang_ids' => $request->barang_ids ?? [],
            'jumlah' => $request->jumlah ?? [],
            'kondisi' => $request->kondisi ?? [],
        ]);

        try {
            // Filter out empty values from barang_ids before validation
            $originalBarangIds = $request->barang_ids ?? [];
            $filteredBarangIds = array_filter($originalBarangIds, function($id) {
                return !empty($id) && $id !== '';
            });
            
            if (empty($filteredBarangIds)) {
                \Log::warning('No valid barang_ids found', ['original' => $originalBarangIds]);
                return back()->withErrors(['barang_ids' => 'Minimal satu barang harus dipilih.'])->withInput();
            }
            
            // Rebuild jumlah and kondisi arrays to match filtered barang_ids
            $filteredJumlah = [];
            $filteredKondisi = [];
            $newIndex = 0;
            
            foreach ($originalBarangIds as $oldIndex => $barangId) {
                if (!empty($barangId) && $barangId !== '') {
                    $filteredJumlah[$newIndex] = $request->jumlah[$oldIndex] ?? 1;
                    $filteredKondisi[$newIndex] = $request->kondisi[$oldIndex] ?? 'baik';
                    $newIndex++;
                }
            }
            
            $request->merge([
                'barang_ids' => array_values($filteredBarangIds),
                'jumlah' => $filteredJumlah,
                'kondisi' => $filteredKondisi,
            ]);
            
            \Log::info('After filtering and merging', [
                'barang_ids' => $request->barang_ids,
                'jumlah' => $request->jumlah,
                'kondisi' => $request->kondisi,
            ]);
            
            $request->validate([
                'ruang_id' => 'required|exists:ruang,id',
                'barang_ids' => 'required|array|min:1',
                'barang_ids.*' => 'required|exists:barang,id',
                'jumlah' => 'required|array',
                'jumlah.*' => 'required|integer|min:1',
                'kondisi' => 'required|array',
                'kondisi.*' => 'required|in:baik,rusak,hilang',
                'sumber_dana' => 'nullable|string|max:255',
                'kode_sumber_dana' => 'required|string|max:100',
                'tanggal' => 'required|date',
                'catatan' => 'nullable|string',
            ], [
                'ruang_id.required' => 'Ruang harus dipilih.',
                'ruang_id.exists' => 'Ruang yang dipilih tidak valid.',
                'barang_ids.required' => 'Minimal satu barang harus dipilih.',
                'barang_ids.min' => 'Minimal satu barang harus dipilih.',
                'barang_ids.*.required' => 'Barang harus dipilih.',
                'barang_ids.*.exists' => 'Barang yang dipilih tidak valid.',
                'kode_sumber_dana.required' => 'Kode sumber dana harus diisi.',
                'tanggal.required' => 'Tanggal harus diisi.',
                'tanggal.date' => 'Format tanggal tidak valid.',
            ]);

            \Log::info('Validation passed');

            // Use DB transaction to ensure data consistency
            return \DB::transaction(function() use ($request) {
                \Log::info('Starting DB transaction');
                
                // Prepare barang data (allow multiple sarana with same barang - dinamis untuk dana berbeda)
                $barangData = [];
                $totalJumlah = 0;
                $firstBarang = null;
                
                foreach ($request->barang_ids as $index => $barangId) {
                    if (empty($barangId)) continue;
                    
                    $jumlah = isset($request->jumlah[$index]) ? (int)$request->jumlah[$index] : 1;
                    $kondisi = $request->kondisi[$index] ?? 'baik';
                    
                    $totalJumlah += $jumlah;
                    if ($firstBarang === null) {
                        $firstBarang = Barang::find($barangId);
                    }
                    $barangData[$barangId] = [
                        'jumlah' => $jumlah,
                        'kondisi' => $kondisi,
                    ];
                }
                
                \Log::info('Barang data prepared', [
                    'barang_data' => $barangData,
                    'total_jumlah' => $totalJumlah,
                ]);

                if (empty($barangData)) {
                    \Log::warning('No barang data after filtering');
                    throw new \Illuminate\Validation\ValidationException(
                        \Validator::make([], []),
                        ['barang_ids' => 'Minimal satu barang harus dipilih.']
                    );
                }

                \Log::info('Creating sarana record');
                // Create sarana with temporary kode_inventaris
                $sarana = Sarana::create([
                    'ruang_id' => $request->ruang_id,
                    'sumber_dana' => $request->sumber_dana,
                    'kode_sumber_dana' => $request->kode_sumber_dana,
                    'tanggal' => $request->tanggal,
                    'catatan' => $request->catatan,
                    'kode_inventaris' => 'TEMP', // Temporary, will be updated
                ]);
                
                \Log::info('Sarana created', ['sarana_id' => $sarana->id]);

                \Log::info('Attaching barang to sarana');
                // Attach barang with pivot data
                $sarana->barang()->attach($barangData);
                
                \Log::info('Barang attached successfully');

                // Update ruang_id di tabel barang untuk barang yang ditambahkan
                $barangIdsToUpdate = array_keys($barangData);
                if (!empty($barangIdsToUpdate)) {
                    \Log::info('Updating ruang_id for barang', ['barang_ids' => $barangIdsToUpdate]);
                    $updated = Barang::whereIn('id', $barangIdsToUpdate)
                        ->update(['ruang_id' => $request->ruang_id]);
                    
                    \Log::info('Updated ruang_id for barang', [
                        'barang_ids' => $barangIdsToUpdate,
                        'ruang_id' => $request->ruang_id,
                        'updated_count' => $updated
                    ]);
                }

                // Generate kode inventaris after barang is attached
                $lastSarana = Sarana::orderBy('id', 'desc')->where('id', '!=', $sarana->id)->first();
                $no = $lastSarana ? $lastSarana->id + 1 : $sarana->id;
                $sarana->kode_inventaris = $sarana->generateKodeInventaris(
                    $no,
                    $totalJumlah,
                    $firstBarang ? $firstBarang->kode_barang : null
                );
                $sarana->save();
                
                \Log::info('Sarana saved successfully', [
                    'sarana_id' => $sarana->id,
                    'kode_inventaris' => $sarana->kode_inventaris
                ]);

                return redirect()->route('admin.sarpras.sarana.index')
                    ->with('success', 'Sarana berhasil ditambahkan.');
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Validation exception', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error storing sarana: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified sarana.
     */
    public function show(Sarana $sarana)
    {
        $sarana->load(['ruang', 'barang.kategori']);
        
        // Load audit logs (history)
        $auditLogs = $sarana->auditLogs();
        
        return view('sarpras.sarana.show', compact('sarana', 'auditLogs'));
    }

    /**
     * Show the form for editing the specified sarana.
     */
    public function edit(Sarana $sarana)
    {
        $sarana->load(['ruang', 'barang.kategori']);
        $ruangs = Ruang::active()->orderBy('nama_ruang')->get();
        
        // Get all available barang (allow multiple sarana with same barang - dinamis untuk dana berbeda)
        $barangs = Barang::with('kategori')
            ->active()
            ->orderBy('nama_barang')
            ->get();
        
        $kategoris = KategoriSarpras::active()->orderBy('nama_kategori')->get();

        // Prepare barang data for JavaScript
        $barangsJson = $barangs->map(function($b) {
            return [
                'id' => $b->id,
                'nama_barang' => $b->nama_barang,
                'kode_barang' => $b->kode_barang,
                'kategori' => $b->kategori->nama_kategori ?? '-',
                'ruang_id' => $b->ruang_id, // Include ruang_id
                'harga_beli' => $b->harga_beli ?? 0, // Include harga
            ];
        })->values()->all();

        // Prepare sarana barang data for JavaScript
        $saranaBarangsJson = $sarana->barang->map(function($b) {
            return [
                'barang_id' => $b->id,
                'jumlah' => $b->pivot->jumlah,
                'kondisi' => $b->pivot->kondisi,
                'harga_beli' => $b->harga_beli ?? 0, // Include harga
            ];
        })->values()->all();

        return view('sarpras.sarana.edit', compact('sarana', 'ruangs', 'barangs', 'kategoris', 'barangsJson', 'saranaBarangsJson'));
    }

    /**
     * Update the specified sarana.
     */
    public function update(Request $request, Sarana $sarana)
    {
        try {
            $request->validate([
                'ruang_id' => 'required|exists:ruang,id',
                'barang_ids' => 'required|array|min:1',
                'barang_ids.*' => 'exists:barang,id',
                'jumlah' => 'required|array',
                'jumlah.*' => 'required|integer|min:1',
                'kondisi' => 'required|array',
                'kondisi.*' => 'required|in:baik,rusak,hilang',
                'sumber_dana' => 'nullable|string|max:255',
                'kode_sumber_dana' => 'required|string|max:100',
                'tanggal' => 'required|date',
                'catatan' => 'nullable|string',
            ], [
                'ruang_id.required' => 'Ruang harus dipilih.',
                'ruang_id.exists' => 'Ruang yang dipilih tidak valid.',
                'barang_ids.required' => 'Minimal satu barang harus dipilih.',
                'barang_ids.min' => 'Minimal satu barang harus dipilih.',
                'kode_sumber_dana.required' => 'Kode sumber dana harus diisi.',
                'tanggal.required' => 'Tanggal harus diisi.',
                'tanggal.date' => 'Format tanggal tidak valid.',
            ]);

            // Update sarana
            $sarana->update([
                'ruang_id' => $request->ruang_id,
                'sumber_dana' => $request->sumber_dana,
                'kode_sumber_dana' => $request->kode_sumber_dana,
                'tanggal' => $request->tanggal,
                'catatan' => $request->catatan,
            ]);

            // Sync barang with pivot data (allow multiple sarana with same barang - dinamis untuk dana berbeda)
            $barangData = [];
            $totalJumlah = 0;
            $firstBarang = null;
            foreach ($request->barang_ids as $index => $barangId) {
                if (empty($barangId)) continue; // Skip empty values
                
                $jumlah = isset($request->jumlah[$index]) ? (int)$request->jumlah[$index] : 1;
                $totalJumlah += $jumlah;
                if ($firstBarang === null) {
                    $firstBarang = Barang::find($barangId);
                }
                $barangData[$barangId] = [
                    'jumlah' => $jumlah,
                    'kondisi' => $request->kondisi[$index] ?? 'baik',
                ];
            }

            if (empty($barangData)) {
                return back()->withErrors(['barang_ids' => 'Minimal satu barang harus dipilih.'])->withInput();
            }

            $sarana->barang()->sync($barangData);

            // Update ruang_id di tabel barang untuk barang yang ditambahkan
            foreach (array_keys($barangData) as $barangId) {
                Barang::where('id', $barangId)->update(['ruang_id' => $request->ruang_id]);
            }

            // Regenerate kode inventaris
            $sarana->kode_inventaris = $sarana->generateKodeInventaris(
                $sarana->id,
                $totalJumlah,
                $firstBarang ? $firstBarang->kode_barang : null
            );
            $sarana->save();

            return redirect()->route('admin.sarpras.sarana.index')
                ->with('success', 'Sarana berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error updating sarana: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified sarana.
     */
    public function destroy(Sarana $sarana)
    {
        $sarana->delete();

        return redirect()->route('admin.sarpras.sarana.index')
            ->with('success', 'Sarana berhasil dihapus.');
    }

    /**
     * Get barang by ruang_id (AJAX).
     */
    public function getBarangByRuang(Request $request)
    {
        $request->validate([
            'ruang_id' => 'required|exists:ruang,id',
            'sarana_id' => 'nullable|exists:sarana,id', // For edit mode
        ]);

        // Get barang yang bisa dipilih (allow multiple sarana with same barang - dinamis untuk dana berbeda):
        // 1. Barang yang ada di ruang yang dipilih
        // 2. Barang yang belum punya ruang (ruang_id = null)
        $barangs = Barang::with('kategori')
            ->where(function($query) use ($request) {
                $query->where('ruang_id', $request->ruang_id)
                    ->orWhereNull('ruang_id');
            })
            ->active()
            ->orderByRaw("CASE WHEN ruang_id = ? THEN 0 ELSE 1 END", [$request->ruang_id])
            ->orderBy('nama_barang')
            ->get();

        return response()->json([
            'success' => true,
            'barangs' => $barangs->map(function ($barang) {
                return [
                    'id' => $barang->id,
                    'nama_barang' => $barang->nama_barang,
                    'kode_barang' => $barang->kode_barang,
                    'kategori' => $barang->kategori->nama_kategori ?? '-',
                    'ruang_id' => $barang->ruang_id, // Include ruang_id
                    'harga_beli' => $barang->harga_beli ?? 0, // Include harga
                    'kondisi' => $barang->kondisi ?? 'baik', // Include kondisi dari master data
                ];
            }),
        ]);
    }

    /**
     * Print invoice for sarana.
     */
    public function printInvoice(Sarana $sarana)
    {
        $sarana->load(['ruang', 'barang.kategori']);
        
        $pdf = Pdf::loadView('sarpras.sarana.invoice', compact('sarana'));
        $pdf->setPaper('a4', 'portrait');

        // Sanitize filename: replace invalid characters with dashes
        $filename = 'invoice-sarana-' . $sarana->kode_inventaris . '.pdf';
        $filename = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $filename);
        
        return $pdf->download($filename);
    }

    /**
     * Export sarana to Excel.
     */
    public function exportExcel(Request $request)
    {
        // Get filtered sarana (same as index)
        $query = Sarana::with(['ruang', 'barang.kategori']);

        // Filter by kategori
        if ($request->filled('kategori_id')) {
            $query->kategori($request->kategori_id);
        }

        // Filter by sumber dana
        if ($request->filled('sumber_dana')) {
            $query->sumberDana($request->sumber_dana);
        }

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_inventaris', 'like', "%{$search}%")
                  ->orWhere('sumber_dana', 'like', "%{$search}%")
                  ->orWhereHas('ruang', function($q) use ($search) {
                      $q->where('nama_ruang', 'like', "%{$search}%");
                  })
                  ->orWhereHas('barang', function($q) use ($search) {
                      $q->where('nama_barang', 'like', "%{$search}%");
                  });
            });
        }

        $saranas = $query->orderBy('tanggal', 'desc')->get();

        $filename = 'sarana-export-' . date('Y-m-d_His') . '.xlsx';
        return Excel::download(new SaranaExport($saranas), $filename);
    }

    /**
     * Download template Excel for sarana import.
     */
    public function downloadTemplate()
    {
        // Create sample data
        $sampleData = [
            [
                'Kode Inventaris' => 'INV/0001.B001.R001.002.MAUDU/2025',
                'Ruang' => 'Laboratorium Komputer 1',
                'Kode Ruang' => 'R001',
                'Nama Ruang' => 'Laboratorium Komputer 1',
                'Lokasi Ruang' => 'Gedung A, Lantai 1',
                'Sumber Dana' => 'BOS',
                'Kode Sumber Dana' => 'MAUDU/2025',
                'Tanggal' => '2025-01-15',
                'Catatan' => 'Inventarisasi peralatan',
                'Barang - Nama' => '=== 2 BARANG ===',
                'Barang - Kode' => '',
                'Barang - Kategori' => '',
                'Barang - Jumlah' => '2',
                'Barang - Kondisi' => '',
                'Barang - Harga Satuan' => '',
                'Barang - Total Harga' => '',
                'Barang - Merk' => '',
                'Barang - Model' => '',
                'Barang - Serial Number' => '',
                'Created At' => '',
                'Updated At' => '',
            ],
            [
                'Kode Inventaris' => '',
                'Ruang' => '',
                'Kode Ruang' => '',
                'Nama Ruang' => '',
                'Lokasi Ruang' => '',
                'Sumber Dana' => '',
                'Kode Sumber Dana' => '',
                'Tanggal' => '',
                'Catatan' => '',
                'Barang - Nama' => 'Komputer Desktop',
                'Barang - Kode' => 'B001',
                'Barang - Kategori' => 'Elektronik',
                'Barang - Jumlah' => '1',
                'Barang - Kondisi' => 'Baik',
                'Barang - Harga Satuan' => '5000000',
                'Barang - Total Harga' => '5000000',
                'Barang - Merk' => 'Dell',
                'Barang - Model' => 'OptiPlex 7090',
                'Barang - Serial Number' => 'SN123456',
                'Created At' => '',
                'Updated At' => '',
            ],
            [
                'Kode Inventaris' => '',
                'Ruang' => '',
                'Kode Ruang' => '',
                'Nama Ruang' => '',
                'Lokasi Ruang' => '',
                'Sumber Dana' => '',
                'Kode Sumber Dana' => '',
                'Tanggal' => '',
                'Catatan' => '',
                'Barang - Nama' => 'Proyektor',
                'Barang - Kode' => 'B002',
                'Barang - Kategori' => 'Elektronik',
                'Barang - Jumlah' => '1',
                'Barang - Kondisi' => 'Baik',
                'Barang - Harga Satuan' => '3000000',
                'Barang - Total Harga' => '3000000',
                'Barang - Merk' => 'Epson',
                'Barang - Model' => 'EB-X41',
                'Barang - Serial Number' => 'SN789012',
                'Created At' => '',
                'Updated At' => '',
            ],
        ];

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
                    'Kode Inventaris',
                    'Ruang',
                    'Kode Ruang',
                    'Nama Ruang',
                    'Lokasi Ruang',
                    'Sumber Dana',
                    'Kode Sumber Dana',
                    'Tanggal',
                    'Catatan',
                    'Barang - Nama',
                    'Barang - Kode',
                    'Barang - Kategori',
                    'Barang - Jumlah',
                    'Barang - Kondisi',
                    'Barang - Harga Satuan',
                    'Barang - Total Harga',
                    'Barang - Merk',
                    'Barang - Model',
                    'Barang - Serial Number',
                    'Created At',
                    'Updated At',
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
                    'A' => 25, 'B' => 20, 'C' => 15, 'D' => 20, 'E' => 20,
                    'F' => 15, 'G' => 18, 'H' => 12, 'I' => 30, 'J' => 30,
                    'K' => 15, 'L' => 20, 'M' => 10, 'N' => 12, 'O' => 18,
                    'P' => 18, 'Q' => 15, 'R' => 15, 'S' => 20, 'T' => 20, 'U' => 20,
                ];
            }
        };

        return Excel::download($templateExport, 'template-import-sarana.xlsx');
    }

    /**
     * Import sarana from Excel.
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $file = $request->file('file');
            $import = new SaranaImport();
            
            Excel::import($import, $file);

            $rowCount = $import->getRowCount();
            
            return redirect()->route('admin.sarpras.sarana.index')
                ->with('success', "Berhasil mengimpor {$rowCount} data sarana dari Excel.");
        } catch (\Maatwebsite\Excel\Exceptions\SheetNotFoundException $e) {
            \Log::error("Sheet not found in Excel file", ['error' => $e->getMessage()]);
            return back()
                ->with('error', 'File Excel tidak memiliki sheet yang valid. Pastikan file Excel memiliki data di sheet pertama.');
        } catch (\Maatwebsite\Excel\Exceptions\NoTypeDetectedException $e) {
            \Log::error("No type detected", ['error' => $e->getMessage()]);
            return back()
                ->with('error', 'Format file tidak dikenali. Pastikan file dalam format Excel (.xlsx, .xls) atau CSV (.csv).');
        } catch (\Exception $e) {
            \Log::error("Error importing sarana", ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()
                ->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }
}
