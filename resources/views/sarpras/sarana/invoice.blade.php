<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Sarana - {{ $sarana->kode_inventaris }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }
        .header-left h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header-left p {
            font-size: 11px;
            color: #666;
        }
        .header-right {
            text-align: right;
        }
        .header-right h2 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            margin-bottom: 8px;
        }
        .info-label {
            width: 150px;
            font-weight: bold;
        }
        .info-value {
            flex: 1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th,
        table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            width: 100%;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }
        .signature-table td {
            width: 50%;
            padding: 0;
            vertical-align: bottom;
        }
        .signature-left {
            text-align: left;
            padding-right: 30px;
        }
        .signature-right {
            text-align: right;
            padding-left: 30px;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 5px;
            width: 100%;
        }
        .signature-left .signature-line {
            text-align: left;
        }
        .signature-right .signature-line {
            text-align: right;
        }
        .signature-line strong {
            display: block;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <div class="header-left">
                <h1>INVOICE SARANA</h1>
                <p>Sekolah - Portal Sekolah</p>
            </div>
            <div class="header-right">
                <h2>{{ $sarana->kode_inventaris }}</h2>
                <p>Tanggal: {{ $sarana->formatted_tanggal }}</p>
            </div>
        </div>

        <div class="info-section">
            <div class="info-row">
                <div class="info-label">Ruang:</div>
                <div class="info-value">{{ $sarana->ruang->nama_ruang ?? '-' }} ({{ $sarana->ruang->kode_ruang ?? '-' }})</div>
            </div>
            <div class="info-row">
                <div class="info-label">Sumber Dana:</div>
                <div class="info-value">{{ $sarana->sumber_dana ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Kode Sumber Dana:</div>
                <div class="info-value">{{ $sarana->kode_sumber_dana ?? '-' }}</div>
            </div>
            @if ($sarana->catatan)
            <div class="info-row">
                <div class="info-label">Catatan:</div>
                <div class="info-value">{{ $sarana->catatan }}</div>
            </div>
            @endif
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Kode Barang</th>
                    <th>Kategori</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-center">Kondisi</th>
                    <th class="text-right">Harga Satuan</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $grandTotal = 0;
                @endphp
                @foreach ($sarana->barang as $index => $barang)
                    @php
                        $hargaSatuan = $barang->harga_beli ?? 0;
                        $jumlah = $barang->pivot->jumlah ?? 1;
                        $totalItem = $hargaSatuan * $jumlah;
                        $grandTotal += $totalItem;
                        $kondisiText = match ($barang->pivot->kondisi) {
                            'baik' => 'Baik',
                            'rusak' => 'Rusak',
                            'hilang' => 'Hilang',
                            default => 'Tidak Diketahui',
                        };
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td>{{ $barang->kode_barang }}</td>
                        <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                        <td class="text-center">{{ $jumlah }}</td>
                        <td class="text-center">{{ $kondisiText }}</td>
                        <td class="text-right">
                            @if ($hargaSatuan > 0)
                                Rp {{ number_format($hargaSatuan, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-right" style="font-weight: bold;">
                            @if ($totalItem > 0)
                                Rp {{ number_format($totalItem, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="text-right" style="font-weight: bold;">Total Jumlah Barang:</td>
                    <td class="text-center" style="font-weight: bold;">{{ $sarana->total_jumlah }}</td>
                    <td></td>
                </tr>
                <tr style="background-color: #f5f5f5;">
                    <td colspan="6" class="text-right" style="font-weight: bold; font-size: 14px;">GRAND TOTAL:</td>
                    <td colspan="2" class="text-right" style="font-weight: bold; font-size: 14px;">
                        Rp {{ number_format($grandTotal, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>

        <div class="footer">
            <table class="signature-table">
                <tr>
                    <td class="signature-left">
                        <div class="signature-line">
                            <strong>Yang Menerima</strong>
                        </div>
                    </td>
                    <td class="signature-right">
                        <div class="signature-line">
                            <strong>Yang Menyerahkan</strong>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>

