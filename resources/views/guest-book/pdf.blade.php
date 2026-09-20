<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Export Buku Tamu - {{ now()->format('d/m/Y') }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #1e40af;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 16px;
            margin: 0 0 5px 0;
            color: #1e40af;
        }
        .header p {
            font-size: 10px;
            margin: 2px 0;
            color: #666;
        }
        .filters {
            font-size: 9px;
            color: #666;
            margin-bottom: 15px;
            padding: 5px 10px;
            background: #f3f4f6;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #d1d5db;
            padding: 5px 6px;
            text-align: left;
            font-size: 9px;
        }
        th {
            background-color: #1e40af;
            color: white;
            font-weight: bold;
            font-size: 9px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #999;
        }
        .status-check_in { color: #16a34a; font-weight: bold; }
        .status-check_out { color: #6b7280; }
        .status-dibatalkan { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <h2>Data Buku Tamu</h2>
        <p>Dicetak: {{ now()->format('d M Y H:i') }}</p>
    </div>

    @if($filtersSummary)
        <div class="filters">
            Filter: {{ $filtersSummary }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th style="width:3%">No</th>
                <th style="width:10%">No. Tiket</th>
                <th style="width:14%">Nama Tamu</th>
                <th style="width:10%">NIK</th>
                <th style="width:12%">Instansi</th>
                <th style="width:10%">Jabatan</th>
                <th style="width:9%">Kategori</th>
                <th style="width:12%">Tujuan</th>
                <th style="width:8%">Status</th>
                <th style="width:11%">Jam Masuk</th>
                <th style="width:11%">Jam Keluar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($guests as $index => $guest)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $guest->ticket_number }}</td>
                    <td>{{ $guest->guest_name }}</td>
                    <td>{{ $guest->nik ?? '-' }}</td>
                    <td>{{ $guest->organization ?? '-' }}</td>
                    <td>{{ $guest->position ?? '-' }}</td>
                    <td>{{ $visitCategories[$guest->visit_category] ?? $guest->visit_category }}</td>
                    <td>{{ $guest->visit_purpose ?? '-' }}</td>
                    <td class="status-{{ $guest->status }}">
                        @if($guest->status === 'check_in')
                            Sedang Berkunjung
                        @elseif($guest->status === 'check_out')
                            Selesai
                        @elseif($guest->status === 'dibatalkan')
                            Dibatalkan
                        @else
                            {{ $guest->status }}
                        @endif
                    </td>
                    <td>{{ $guest->check_in_at ? $guest->check_in_at->format('d/m/Y H:i') : '-' }}</td>
                    <td>{{ $guest->check_out_at ? $guest->check_out_at->format('d/m/Y H:i') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" style="text-align:center;">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Halaman ini dicetak secara otomatis oleh sistem {{ config('app.name') }}
    </div>
</body>
</html>
