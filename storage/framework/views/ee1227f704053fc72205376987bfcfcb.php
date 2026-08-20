<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Summary Absensi</title>
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
            font-size: 8px;
        }

        table td {
            border: 1px solid #e2e8f0;
            padding: 6px 4px;
            font-size: 8px;
        }

        table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .pct-high {
            color: #16a34a;
            font-weight: bold;
        }

        .pct-mid {
            color: #d97706;
            font-weight: bold;
        }

        .pct-low {
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
        <h2>Summary Kehadiran</h2>
        <p><?php echo e(config('app.name', 'SMK Telekomunikasi Darul Ulum')); ?></p>
    </div>

    <div class="info-bar">
        <span>Dari: <strong><?php echo e(\Carbon\Carbon::parse($startDate)->format('d F Y')); ?></strong></span>
        <span>Sampai: <strong><?php echo e(\Carbon\Carbon::parse($endDate)->format('d F Y')); ?></strong></span>
        <span>Total User: <strong><?php echo e(count($data) - 1); ?></strong></span>
    </div>

    <?php if(count($data) > 1): ?>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis</th>
                    <th>Nama</th>
                    <th>PIN</th>
                    <th>Total Hari</th>
                    <th>Hadir</th>
                    <th>Tidak Hadir</th>
                    <th>Persentase</th>
                    <th>Avg Masuk</th>
                    <th>Avg Pulang</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = array_slice($data, 1); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $pct = (int) str_replace('%', '', $row[6]);
                        $pctClass = $pct >= 80 ? 'pct-high' : ($pct >= 50 ? 'pct-mid' : 'pct-low');
                    ?>
                    <tr>
                        <td><?php echo e($i + 1); ?></td>
                        <td><?php echo e($row[0]); ?></td>
                        <td><?php echo e($row[1]); ?></td>
                        <td><?php echo e($row[2]); ?></td>
                        <td><?php echo e($row[3]); ?></td>
                        <td><?php echo e($row[4]); ?></td>
                        <td><?php echo e($row[5]); ?></td>
                        <td class="<?php echo e($pctClass); ?>"><?php echo e($row[6]); ?></td>
                        <td><?php echo e($row[7]); ?></td>
                        <td><?php echo e($row[8]); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-data">Tidak ada data untuk periode ini.</div>
    <?php endif; ?>

    <div class="footer">
        Dicetak pada: <?php echo e(now()->format('d F Y H:i:s')); ?> | <?php echo e(config('app.name')); ?>

    </div>
</body>

</html>
<?php /**PATH E:\PROJEKU\telkom\resources\views\attendance\pdf\summary.blade.php ENDPATH**/ ?>