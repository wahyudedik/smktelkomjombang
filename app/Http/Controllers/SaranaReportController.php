<?php

namespace App\Http\Controllers;

use App\Models\Sarana;
use App\Models\Barang;
use App\Models\Ruang;
use App\Models\KategoriSarpras;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SaranaReportController extends Controller
{
    /**
     * Display the reports index page.
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $ruangId = $request->get('ruang_id');
        $kategoriId = $request->get('kategori_id');
        $kondisi = $request->get('kondisi');
        $sumberDana = $request->get('sumber_dana');
        $tanggalDari = $request->get('tanggal_dari');
        $tanggalSampai = $request->get('tanggal_sampai');

        // Get all rooms and categories for filters
        $ruangs = Ruang::active()->orderBy('nama_ruang')->get();
        $kategoris = KategoriSarpras::active()->orderBy('nama_kategori')->get();
        $sumberDanas = Sarana::whereNotNull('sumber_dana')
            ->distinct()
            ->pluck('sumber_dana')
            ->filter()
            ->sort()
            ->values();

        // Build query for sarana
        $saranasQuery = Sarana::with(['ruang', 'barang.kategori']);

        if ($ruangId) {
            $saranasQuery->where('ruang_id', $ruangId);
        }

        if ($sumberDana) {
            $saranasQuery->where('sumber_dana', $sumberDana);
        }

        if ($tanggalDari) {
            $saranasQuery->whereDate('tanggal', '>=', $tanggalDari);
        }

        if ($tanggalSampai) {
            $saranasQuery->whereDate('tanggal', '<=', $tanggalSampai);
        }

        if ($kategoriId) {
            $saranasQuery->whereHas('barang', function($q) use ($kategoriId) {
                $q->where('kategori_id', $kategoriId);
            });
        }

        $saranas = $saranasQuery->orderBy('tanggal', 'desc')->get();

        // Filter by kondisi if specified
        if ($kondisi) {
            $saranas = $saranas->filter(function($sarana) use ($kondisi) {
                return $sarana->barang->contains(function($barang) use ($kondisi) {
                    return $barang->pivot->kondisi === $kondisi;
                });
            });
        }

        // Calculate statistics
        $stats = $this->calculateStats($saranas);

        // Get barang that need maintenance
        $barangPerluPerbaikan = $this->getBarangPerluPerbaikan($ruangId, $kategoriId);

        // Get maintenance by funding source
        $maintenanceByDana = $this->getMaintenanceByDana($sumberDana);

        return view('sarpras.reports.index', compact(
            'saranas',
            'ruangs',
            'kategoris',
            'sumberDanas',
            'stats',
            'barangPerluPerbaikan',
            'maintenanceByDana',
            'ruangId',
            'kategoriId',
            'kondisi',
            'sumberDana',
            'tanggalDari',
            'tanggalSampai'
        ));
    }

    /**
     * Calculate statistics from sarana data.
     */
    private function calculateStats($saranas)
    {
        $totalSarana = $saranas->count();
        $totalBarang = 0;
        $totalNilai = 0;
        $kondisiBaik = 0;
        $kondisiRusak = 0;
        $kondisiHilang = 0;

        foreach ($saranas as $sarana) {
            foreach ($sarana->barang as $barang) {
                $jumlah = $barang->pivot->jumlah;
                $harga = $barang->harga_beli ?? 0;
                $totalBarang += $jumlah;
                $totalNilai += $harga * $jumlah;

                switch ($barang->pivot->kondisi) {
                    case 'baik':
                        $kondisiBaik += $jumlah;
                        break;
                    case 'rusak':
                        $kondisiRusak += $jumlah;
                        break;
                    case 'hilang':
                        $kondisiHilang += $jumlah;
                        break;
                }
            }
        }

        return [
            'total_sarana' => $totalSarana,
            'total_barang' => $totalBarang,
            'total_nilai' => $totalNilai,
            'kondisi_baik' => $kondisiBaik,
            'kondisi_rusak' => $kondisiRusak,
            'kondisi_hilang' => $kondisiHilang,
        ];
    }

    /**
     * Get barang that need maintenance.
     */
    private function getBarangPerluPerbaikan($ruangId = null, $kategoriId = null)
    {
        $query = DB::table('sarana_barang')
            ->join('sarana', 'sarana_barang.sarana_id', '=', 'sarana.id')
            ->join('barang', 'sarana_barang.barang_id', '=', 'barang.id')
            ->where('sarana_barang.kondisi', 'rusak')
            ->select(
                'barang.id',
                'barang.nama_barang',
                'barang.kode_barang',
                'sarana.ruang_id',
                'sarana.sumber_dana',
                'sarana.kode_sumber_dana',
                'sarana_barang.jumlah',
                'sarana.kode_inventaris',
                'sarana.tanggal'
            );

        if ($ruangId) {
            $query->where('sarana.ruang_id', $ruangId);
        }

        if ($kategoriId) {
            $query->where('barang.kategori_id', $kategoriId);
        }

        return $query->get()->map(function($item) {
            $ruang = Ruang::find($item->ruang_id);
            return [
                'barang_id' => $item->id,
                'nama_barang' => $item->nama_barang,
                'kode_barang' => $item->kode_barang,
                'ruang' => $ruang ? $ruang->nama_ruang : '-',
                'jumlah' => $item->jumlah,
                'sumber_dana' => $item->sumber_dana,
                'kode_sumber_dana' => $item->kode_sumber_dana,
                'kode_inventaris' => $item->kode_inventaris,
                'tanggal' => $item->tanggal,
            ];
        });
    }

    /**
     * Get maintenance by funding source.
     */
    private function getMaintenanceByDana($sumberDana = null)
    {
        // Get maintenance for barang that are in sarana
        $barangIds = DB::table('sarana_barang')
            ->join('sarana', 'sarana_barang.sarana_id', '=', 'sarana.id')
            ->when($sumberDana, function($q) use ($sumberDana) {
                return $q->where('sarana.sumber_dana', $sumberDana);
            })
            ->pluck('sarana_barang.barang_id')
            ->unique();

        $maintenances = Maintenance::with(['barang', 'ruang'])
            ->where('jenis_item', 'barang')
            ->whereIn('item_id', $barangIds)
            ->get();

        // Group by sumber dana from sarana
        $grouped = [];
        foreach ($maintenances as $maintenance) {
            $sarana = DB::table('sarana_barang')
                ->join('sarana', 'sarana_barang.sarana_id', '=', 'sarana.id')
                ->where('sarana_barang.barang_id', $maintenance->item_id)
                ->select('sarana.sumber_dana', 'sarana.kode_sumber_dana')
                ->first();
            
            if ($sarana) {
                $dana = $sarana->sumber_dana ?? 'Tidak Diketahui';
                if (!isset($grouped[$dana])) {
                    $grouped[$dana] = [];
                }
                $grouped[$dana][] = $maintenance;
            }
        }

        return collect($grouped);
    }

    /**
     * Export report to PDF.
     */
    public function exportPdf(Request $request)
    {
        // Get filter parameters (same as index)
        $ruangId = $request->get('ruang_id');
        $kategoriId = $request->get('kategori_id');
        $kondisi = $request->get('kondisi');
        $sumberDana = $request->get('sumber_dana');
        $tanggalDari = $request->get('tanggal_dari');
        $tanggalSampai = $request->get('tanggal_sampai');

        // Build query (same as index)
        $saranasQuery = Sarana::with(['ruang', 'barang.kategori']);

        if ($ruangId) {
            $saranasQuery->where('ruang_id', $ruangId);
        }

        if ($sumberDana) {
            $saranasQuery->where('sumber_dana', $sumberDana);
        }

        if ($tanggalDari) {
            $saranasQuery->whereDate('tanggal', '>=', $tanggalDari);
        }

        if ($tanggalSampai) {
            $saranasQuery->whereDate('tanggal', '<=', $tanggalSampai);
        }

        if ($kategoriId) {
            $saranasQuery->whereHas('barang', function($q) use ($kategoriId) {
                $q->where('kategori_id', $kategoriId);
            });
        }

        $saranas = $saranasQuery->orderBy('tanggal', 'desc')->get();

        if ($kondisi) {
            $saranas = $saranas->filter(function($sarana) use ($kondisi) {
                return $sarana->barang->contains(function($barang) use ($kondisi) {
                    return $barang->pivot->kondisi === $kondisi;
                });
            });
        }

        $stats = $this->calculateStats($saranas);
        $barangPerluPerbaikan = $this->getBarangPerluPerbaikan($ruangId, $kategoriId);

        $pdf = Pdf::loadView('sarpras.reports.pdf', compact(
            'saranas',
            'stats',
            'barangPerluPerbaikan',
            'ruangId',
            'kategoriId',
            'kondisi',
            'sumberDana',
            'tanggalDari',
            'tanggalSampai'
        ));

        $filename = 'laporan-sarana-' . date('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }
}

