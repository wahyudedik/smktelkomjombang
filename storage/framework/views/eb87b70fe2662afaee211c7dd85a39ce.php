<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rekap Absensi Periode</title>
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

        .info-bar {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 8px 12px;
            background-color: #f8fafc;
            border-radius: 6px;
            font-size: 9px;
            color: #475569;
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

        .date-group {
            background-color: #e2e8f0;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Rekap Absensi Periode</h2>
        <p><?php echo e(config('app.name', 'SMK Telekomunikasi Darul Ulum')); ?></p>
    </div>

    <div class="info-bar">
        <span>Dari: <strong><?php echo e(\Carbon\Carbon::parse($startDate)->format('d F Y')); ?></strong></span>
        <span>Sampai: <strong><?php echo e(\Carbon\Carbon::parse($endDate)->format('d F Y')); ?></strong></span>
        <span>Total Data: <strong><?php echo e($attendances->count()); ?></strong></span>
    </div>

    <?php if($attendances->count() > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
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
                <?php $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
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
                    ?>
                    <tr>
                        <td><?php echo e($i + 1); ?></td>
                        <td><?php echo e($attendance->date->format('d/m/Y')); ?></td>
                        <td><?php echo e($identity->kind); ?></td>
                        <td><?php echo e($nama); ?></td>
                        <td><?php echo e($identity->device_pin); ?></td>
                        <td><?php echo e($firstIn); ?></td>
                        <td><?php echo e($lastOut); ?></td>
                        <td><?php echo e($durasi); ?></td>
                        <td class="<?php echo e($statusClass); ?>"><?php echo e(ucfirst($attendance->status)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-data">Tidak ada data absensi untuk periode ini.</div>
    <?php endif; ?>

    <div class="footer">
        Dicetak pada: <?php echo e(now()->format('d F Y H:i:s')); ?> | <?php echo e(config('app.name')); ?>

    </div>
</body>

</html>
<?php /**PATH E:\PROJEKU\telkom\resources\views\attendance\pdf\period.blade.php ENDPATH**/ ?>