<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Print Barcode Labels</title>
    <style>
        @page {
            size: A4;
            margin: 1cm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .barcode-label {
            width: 100%;
            max-width: 300px;
            border: 1px solid #000;
            padding: 10px;
            margin: 10px auto;
            text-align: center;
            page-break-inside: avoid;
        }

        .barcode-image {
            max-width: 100%;
            height: auto;
            margin: 10px 0;
        }

        .item-info {
            font-size: 12px;
            margin: 5px 0;
        }

        .item-name {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .barcode-code {
            font-family: monospace;
            font-size: 10px;
            margin-top: 5px;
        }

        @media print {
            .no-print {
                display: none;
            }

            .barcode-label {
                margin: 5px 0;
            }
        }
    </style>
</head>

<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <h1>Bulk Print Barcode Labels</h1>
        <p>Total items: {{ $barangs->count() }}</p>
        <button onclick="window.print()"
            style="padding: 10px 20px; font-size: 16px; background: #007cba; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Print All Labels
        </button>
        <button onclick="window.close()"
            style="padding: 10px 20px; font-size: 16px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
            Close
        </button>
    </div>

    @foreach ($barangs as $barang)
        <!-- Barcode Linear (Garis-garis) -->
        <div class="barcode-label">
            <div class="item-name">{{ $barang->nama_barang }}</div>

            <img src="{{ $barang->barcode_image_url }}" alt="Barcode" class="barcode-image">

            <div class="barcode-code">{{ $barang->barcode }}</div>

            <div class="item-info">
                <div><strong>Kode:</strong> {{ $barang->kode_barang }}</div>
                <div><strong>Kategori:</strong> {{ $barang->kategori->nama_kategori ?? 'N/A' }}</div>
                <div><strong>Ruang:</strong> {{ $barang->ruang->nama_ruang ?? 'N/A' }}</div>
                <div><strong>Kondisi:</strong> {{ $barang->kondisi_display }}</div>
                <div><strong>Status:</strong> {{ $barang->status }}</div>
            </div>
        </div>

        <!-- QR Code (Kotak) -->
        <div class="barcode-label">
            <div class="item-name">{{ $barang->nama_barang }}</div>

            <img src="{{ $barang->qr_code_image_url }}" alt="QR Code" class="barcode-image">

            <div class="barcode-code">{{ $barang->qr_code }}</div>

            <div class="item-info">
                <div><strong>Kode:</strong> {{ $barang->kode_barang }}</div>
                <div><strong>Kategori:</strong> {{ $barang->kategori->nama_kategori ?? 'N/A' }}</div>
                <div><strong>Ruang:</strong> {{ $barang->ruang->nama_ruang ?? 'N/A' }}</div>
                <div><strong>Kondisi:</strong> {{ $barang->kondisi_display }}</div>
                <div><strong>Status:</strong> {{ $barang->status }}</div>
            </div>
        </div>
    @endforeach
</body>

</html>
