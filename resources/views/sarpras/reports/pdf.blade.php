<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Sarana - {{ date('d/m/Y') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        .report-container {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #333;
        }
        .header h1 {
            font-size: 20px;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .header p {
            font-size: 11px;
            color: #666;
        }
        .filter-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f5f5f5;
            border-left: 4px solid #3b82f6;
        }
        .filter-info h3 {
            font-size: 12px;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .filter-info p {
            font-size: 10px;
            margin: 2px 0;
        }
        .stats-section {
            margin-bottom: 25px;
        }
        .stats-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .stats-row {
            display: table-row;
        }
        .stats-cell {
            display: table-cell;
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
            width: 25%;
        }
        .stats-cell strong {
            display: block;
            font-size: 16px;
            margin-bottom: 5px;
        }
        .stats-cell small {
            font-size: 9px;
            color: #666;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 9px;
        }
        table th {
            background-color: #3b82f6;
            color: white;
            padding: 8px 5px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #2563eb;
        }
        table td {
            padding: 6px 5px;
            border: 1px solid #ddd;
        }
        table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-green {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-red {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .badge-gray {
            background-color: #e5e7eb;
            color: #374151;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #333;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
        .page-break {
            page-break-after: always;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="report-container">
        <!-- Header -->
        <div class="header">
            <h1>LAPORAN SARANA</h1>
            <p>Tanggal Cetak: {{ date('d F Y, H:i') }}</p>
        </div>

        <!-- Filter Info -->
        @if ($ruangId || $kategoriId || $kondisi || $sumberDana || $tanggalDari || $tanggalSampai)
        <div class="filter-info">
            <h3>Filter yang Digunakan:</h3>
            <p>
                @if ($ruangId)
                    Ruang: {{ \App\Models\Ruang::find($ruangId)->nama_ruang ?? 'ID: ' . $ruangId }} | 
                @endif
                @if ($kategoriId)
                    Kategori: {{ \App\Models\KategoriSarpras::find($kategoriId)->nama_kategori ?? 'ID: ' . $kategoriId }} | 
                @endif
                @if ($kondisi)
                    Kondisi: {{ ucfirst($kondisi) }} | 
                @endif
                @if ($sumberDana)
                    Sumber Dana: {{ $sumberDana }} | 
                @endif
                @if ($tanggalDari)
                    Dari: {{ \Carbon\Carbon::parse($tanggalDari)->format('d/m/Y') }} | 
                @endif
                @if ($tanggalSampai)
                    Sampai: {{ \Carbon\Carbon::parse($tanggalSampai)->format('d/m/Y') }}
                @endif
            </p>
        </div>
        @endif

        <!-- Statistics -->
        <div class="stats-section">
            <div class="section-title">Statistik</div>
            <div class="stats-grid">
                <div class="stats-row">
                    <div class="stats-cell">
                        <strong>{{ $stats['total_sarana'] ?? 0 }}</strong>
                        <small>Total Sarana</small>
                    </div>
                    <div class="stats-cell">
                        <strong>{{ $stats['total_barang'] ?? 0 }}</strong>
                        <small>Total Barang</small>
                    </div>
                    <div class="stats-cell">
                        <strong>Rp {{ number_format($stats['total_nilai'] ?? 0, 0, ',', '.') }}</strong>
                        <small>Total Nilai</small>
                    </div>
                    <div class="stats-cell">
                        <strong>{{ $stats['kondisi_rusak'] ?? 0 }}</strong>
                        <small>Barang Rusak</small>
                    </div>
                </div>
            </div>
            <div class="stats-grid">
                <div class="stats-row">
                    <div class="stats-cell">
                        <strong>{{ $stats['kondisi_baik'] ?? 0 }}</strong>
                        <small>Kondisi Baik</small>
                    </div>
                    <div class="stats-cell">
                        <strong>{{ $stats['kondisi_rusak'] ?? 0 }}</strong>
                        <small>Kondisi Rusak</small>
                    </div>
                    <div class="stats-cell">
                        <strong>{{ $stats['kondisi_hilang'] ?? 0 }}</strong>
                        <small>Kondisi Hilang</small>
                    </div>
                    <div class="stats-cell">
                        <strong>{{ $barangPerluPerbaikan->count() ?? 0 }}</strong>
                        <small>Perlu Perbaikan</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barang Perlu Perbaikan -->
        <div class="page-break">
            <div class="section-title">Barang Perlu Perbaikan (Rusak)</div>
            @if ($barangPerluPerbaikan && $barangPerluPerbaikan->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th style="width: 3%;">No</th>
                            <th style="width: 12%;">Kode Barang</th>
                            <th style="width: 20%;">Nama Barang</th>
                            <th style="width: 15%;">Ruang</th>
                            <th style="width: 8%;" class="text-center">Jumlah</th>
                            <th style="width: 15%;">Sumber Dana</th>
                            <th style="width: 15%;">Kode Inventaris</th>
                            <th style="width: 12%;" class="text-center">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($barangPerluPerbaikan as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td style="font-family: monospace; font-size: 8px;">{{ $item['kode_barang'] ?? '-' }}</td>
                                <td>{{ $item['nama_barang'] ?? '-' }}</td>
                                <td>{{ $item['ruang'] ?? '-' }}</td>
                                <td class="text-center">{{ $item['jumlah'] ?? 0 }}</td>
                                <td>
                                    {{ $item['sumber_dana'] ?? '-' }}
                                    @if (isset($item['kode_sumber_dana']) && $item['kode_sumber_dana'])
                                        <br><small>({{ $item['kode_sumber_dana'] }})</small>
                                    @endif
                                </td>
                                <td style="font-family: monospace; font-size: 8px;">{{ $item['kode_inventaris'] ?? '-' }}</td>
                                <td class="text-center">{{ isset($item['tanggal']) ? \Carbon\Carbon::parse($item['tanggal'])->format('d/m/Y') : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">Tidak ada barang yang perlu perbaikan</div>
            @endif
        </div>

        <!-- Data Sarana -->
        <div class="page-break">
            <div class="section-title">Data Sarana ({{ $saranas->count() }} record)</div>
            @if ($saranas->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th style="width: 3%;">No</th>
                            <th style="width: 15%;">Kode Inventaris</th>
                            <th style="width: 12%;">Ruang</th>
                            <th style="width: 18%;">Barang</th>
                            <th style="width: 10%;">Kategori</th>
                            <th style="width: 10%;">Sumber Dana</th>
                            <th style="width: 6%;" class="text-center">Jml</th>
                            <th style="width: 8%;" class="text-center">Kondisi</th>
                            <th style="width: 10%;" class="text-right">Total Nilai</th>
                            <th style="width: 8%;" class="text-center">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($saranas as $index => $sarana)
                            @php
                                $totalNilaiSarana = 0;
                                $kondisiCount = ['baik' => 0, 'rusak' => 0, 'hilang' => 0];
                                $barangList = [];
                                $kategoriList = [];
                            @endphp
                            @foreach ($sarana->barang as $barang)
                                @php
                                    $jumlah = $barang->pivot->jumlah;
                                    $harga = $barang->harga_beli ?? 0;
                                    $totalNilaiSarana += $harga * $jumlah;
                                    $kondisiCount[$barang->pivot->kondisi] += $jumlah;
                                    $barangList[] = $barang->nama_barang . ' (' . $jumlah . 'x)';
                                    if ($barang->kategori) {
                                        $kategoriList[] = $barang->kategori->nama_kategori;
                                    }
                                @endphp
                            @endforeach
                            @php
                                $kondisiText = [];
                                if ($kondisiCount['baik'] > 0) $kondisiText[] = 'Baik: ' . $kondisiCount['baik'];
                                if ($kondisiCount['rusak'] > 0) $kondisiText[] = 'Rusak: ' . $kondisiCount['rusak'];
                                if ($kondisiCount['hilang'] > 0) $kondisiText[] = 'Hilang: ' . $kondisiCount['hilang'];
                            @endphp
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td style="font-family: monospace; font-size: 8px;">{{ $sarana->kode_inventaris }}</td>
                                <td>{{ $sarana->ruang->nama_ruang ?? '-' }}</td>
                                <td>
                                    @foreach (array_slice($barangList, 0, 2) as $barang)
                                        {{ $barang }}<br>
                                    @endforeach
                                    @if (count($barangList) > 2)
                                        <small>+{{ count($barangList) - 2 }} lainnya</small>
                                    @endif
                                </td>
                                <td>
                                    @foreach (array_unique(array_slice($kategoriList, 0, 2)) as $kategori)
                                        {{ $kategori }}<br>
                                    @endforeach
                                    @if (count(array_unique($kategoriList)) > 2)
                                        <small>+{{ count(array_unique($kategoriList)) - 2 }} lainnya</small>
                                    @endif
                                </td>
                                <td>
                                    {{ $sarana->sumber_dana ?? '-' }}
                                    @if ($sarana->kode_sumber_dana)
                                        <br><small>({{ $sarana->kode_sumber_dana }})</small>
                                    @endif
                                </td>
                                <td class="text-center">{{ $sarana->barang->sum('pivot.jumlah') }}</td>
                                <td class="text-center">
                                    <small>{{ implode(', ', $kondisiText) }}</small>
                                </td>
                                <td class="text-right">Rp {{ number_format($totalNilaiSarana, 0, ',', '.') }}</td>
                                <td class="text-center">{{ $sarana->tanggal->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #e0e7ff; font-weight: bold;">
                            <td colspan="8" class="text-right">GRAND TOTAL:</td>
                            <td class="text-right">Rp {{ number_format($stats['total_nilai'] ?? 0, 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            @else
                <div class="no-data">Tidak ada data sarana yang ditemukan</div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Laporan ini dihasilkan secara otomatis pada {{ date('d F Y, H:i') }}</p>
            <p>Sistem Manajemen Sarana dan Prasarana</p>
        </div>
    </div>
</body>
</html>

