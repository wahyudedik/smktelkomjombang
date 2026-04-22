<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Hasil Pemilihan OSIS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 5px 0;
            font-size: 20px;
            font-weight: bold;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 16px;
            color: #555;
        }

        .header p {
            margin: 2px 0;
            font-size: 11px;
        }

        .statistics {
            background-color: #f5f5f5;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            border: 2px solid #ddd;
        }

        .statistics h3 {
            margin-top: 0;
            font-size: 14px;
            color: #333;
        }

        .stat-row {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            padding: 5px 0;
            border-bottom: 1px dashed #ccc;
        }

        .stat-label {
            font-weight: bold;
            color: #555;
        }

        .stat-value {
            color: #2563eb;
            font-weight: bold;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th {
            background-color: #2563eb;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-size: 12px;
        }

        table td {
            border: 1px solid #ddd;
            padding: 10px 8px;
            font-size: 11px;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .rank-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-align: center;
        }

        .rank-1 {
            background-color: #ffd700;
            color: #333;
        }

        .rank-2 {
            background-color: #c0c0c0;
            color: #333;
        }

        .rank-3 {
            background-color: #cd7f32;
            color: white;
        }

        .rank-other {
            background-color: #e0e0e0;
            color: #666;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #666;
            padding-top: 10px;
            border-top: 1px solid #ccc;
        }

        .winner-highlight {
            background-color: #fef3c7 !important;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>HASIL PEMILIHAN OSIS</h1>
        <h2>{{ $election->nama }}</h2>
        <p>Tahun: {{ $election->tahun }}</p>
        <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
    </div>

    <div class="statistics">
        <h3>📊 Statistik Pemilihan</h3>
        <div class="stat-row">
            <span class="stat-label">Total Pemilih Terdaftar:</span>
            <span class="stat-value">{{ $totalVoters }} orang</span>
        </div>
        <div class="stat-row">
            <span class="stat-label">Total Suara Masuk:</span>
            <span class="stat-value">{{ $totalVoted }} suara</span>
        </div>
        <div class="stat-row">
            <span class="stat-label">Tingkat Partisipasi:</span>
            <span class="stat-value">{{ number_format($votePercentage, 2) }}%</span>
        </div>
        <div class="stat-row">
            <span class="stat-label">Jumlah Kandidat:</span>
            <span class="stat-value">{{ $results->count() }} kandidat</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="10%">Peringkat</th>
                <th width="35%">Nama Calon</th>
                <th width="20%">Total Suara</th>
                <th width="20%">Persentase</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($results as $index => $calon)
                <tr class="{{ $index === 0 ? 'winner-highlight' : '' }}">
                    <td style="text-align: center;">
                        <span class="rank-badge rank-{{ $index < 3 ? $index + 1 : 'other' }}">
                            {{ $index + 1 }}
                        </span>
                    </td>
                    <td>
                        <strong>{{ $calon->siswa->nama_lengkap ?? $calon->nama }}</strong><br>
                        <small style="color: #666;">{{ $calon->siswa->kelas ?? '' }}</small>
                    </td>
                    <td style="text-align: center; font-size: 14px; font-weight: bold; color: #2563eb;">
                        {{ $calon->votings_count }}
                    </td>
                    <td style="text-align: center;">
                        {{ $totalVoted > 0 ? number_format(($calon->votings_count / $totalVoted) * 100, 2) : '0.00' }}%
                    </td>
                    <td style="text-align: center;">
                        @if ($index === 0)
                            <span style="color: #059669; font-weight: bold;">🏆 Terpilih</span>
                        @else
                            <span style="color: #6b7280;">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">
                        Belum ada data voting tersedia
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>© {{ date('Y') }} Sistem Manajemen Sekolah - Generated by IG to Web</p>
        <p>Dokumen ini bersifat resmi dan dilindungi</p>
    </div>
</body>

</html>
