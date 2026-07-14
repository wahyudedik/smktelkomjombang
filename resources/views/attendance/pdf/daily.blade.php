<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rekap Absensi Harian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #1e293b;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 16px;
            color: #1e293b;
        }

        .header p {
            margin: 2px 0;
            font-size: 10px;
            color: #64748b;
        }

        .stats {
            display: flex;
            justify-content: space-around;
            margin: 15px 0;
            padding: 10px;
            background-color: #f8fafc;
            border-radius: 6px;
        }

        .stat-box {
            text-align: center;
        }

        .stat-box .label {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
        }

        .stat-box .value {
            font-size: 18px;
            font-weight: bold;
            color: #1e293b;
        }

        .stat-box .value.green {
            color: #16a34a;
        }

        .stat-box .value.red {
            color: #dc2626;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th {
            background-color: #1e293b;
            color: white;
            padding: 8px 4px;
            text-align: left;
            font-size: 9px;
        }

        table td {
            border: 1px solid #e2e8f0;
            padding: 6px 4px;
            font-size: 9px;
        }

        table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .status-present {
            color: #16a34a;
            font-weight: bold;
        }

        .status-late {
            color: #d97706;
            font-weight: bold;
        }

        .status-absent {
            color: #dc2626;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 8px;
            color: #94a3b8;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #94a3b8;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Rekap Absensi Harian</h2>
        <p>{{ config('app.name', 'SMK Telekomunikasi Darul Ulum') }}</p>
        <p>Tanggal: <strong>{{ \Carbon\Carbon::parse($date)->format('d F Y') }}</strong></p>
    </div>

    <div class="stats">
        <div class="stat-box">
            <div class="label">Total</div>
            <div class="value">{{ $stats['total'] }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Hadir</div>
            <div class="value green">{{ $stats['present'] }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Tidak Hadir</div>
            <div class="value red">{{ $stats['absent'] }}</div>
        </div>
    </div>

    @if ($attendances->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis</th>
                    <th>Nama</th>
                    <th>PIN</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Durasi</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($attendances as $i => $attendance)
                    @php
                        $identity = $attendance->identity;
                        $nama =
                            $identity->user?->name ??
                            ($identity->guru?->nama_lengkap ?? ($identity->siswa?->nama_lengkap ?? '-'));
                        $firstIn = $attendance->first_in_at?->format('H:i:s') ?? '-';
                        $lastOut = $attendance->last_out_at?->format('H:i:s') ?? '-';
                        $durasi = '-';
                        if ($attendance->first_in_at && $attendance->last_out_at) {
                            $diff = $attendance->last_out_at->diffInMinutes($attendance->first_in_at);
                            $hours = intdiv($diff, 60);
                            $minutes = $diff % 60;
                            $durasi = "{$hours}j {$minutes}m";
                        }
                        $statusClass = match ($attendance->status) {
                            'present' => 'status-present',
                            'late' => 'status-late',
                            default => 'status-absent',
                        };
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $identity->kind }}</td>
                        <td>{{ $nama }}</td>
                        <td>{{ $identity->device_pin }}</td>
                        <td>{{ $firstIn }}</td>
                        <td>{{ $lastOut }}</td>
                        <td>{{ $durasi }}</td>
                        <td class="{{ $statusClass }}">{{ ucfirst($attendance->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">Tidak ada data absensi untuk tanggal ini.</div>
    @endif

    <div class="footer">
        Dicetak pada: {{ now()->format('d F Y H:i:s') }} | {{ config('app.name') }}
    </div>
</body>

</html>
