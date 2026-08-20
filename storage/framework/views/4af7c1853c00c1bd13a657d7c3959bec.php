<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Sarana - <?php echo e($sarana->kode_inventaris); ?></title>
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
                <h2><?php echo e($sarana->kode_inventaris); ?></h2>
                <p>Tanggal: <?php echo e($sarana->formatted_tanggal); ?></p>
            </div>
        </div>

        <div class="info-section">
            <div class="info-row">
                <div class="info-label">Ruang:</div>
                <div class="info-value"><?php echo e($sarana->ruang->nama_ruang ?? '-'); ?> (<?php echo e($sarana->ruang->kode_ruang ?? '-'); ?>)</div>
            </div>
            <div class="info-row">
                <div class="info-label">Sumber Dana:</div>
                <div class="info-value"><?php echo e($sarana->sumber_dana ?? '-'); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Kode Sumber Dana:</div>
                <div class="info-value"><?php echo e($sarana->kode_sumber_dana ?? '-'); ?></div>
            </div>
            <?php if($sarana->catatan): ?>
            <div class="info-row">
                <div class="info-label">Catatan:</div>
                <div class="info-value"><?php echo e($sarana->catatan); ?></div>
            </div>
            <?php endif; ?>
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
                <?php
                    $grandTotal = 0;
                ?>
                <?php $__currentLoopData = $sarana->barang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $barang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
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
                    ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td><?php echo e($barang->nama_barang); ?></td>
                        <td><?php echo e($barang->kode_barang); ?></td>
                        <td><?php echo e($barang->kategori->nama_kategori ?? '-'); ?></td>
                        <td class="text-center"><?php echo e($jumlah); ?></td>
                        <td class="text-center"><?php echo e($kondisiText); ?></td>
                        <td class="text-right">
                            <?php if($hargaSatuan > 0): ?>
                                Rp <?php echo e(number_format($hargaSatuan, 0, ',', '.')); ?>

                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td class="text-right" style="font-weight: bold;">
                            <?php if($totalItem > 0): ?>
                                Rp <?php echo e(number_format($totalItem, 0, ',', '.')); ?>

                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="text-right" style="font-weight: bold;">Total Jumlah Barang:</td>
                    <td class="text-center" style="font-weight: bold;"><?php echo e($sarana->total_jumlah); ?></td>
                    <td></td>
                </tr>
                <tr style="background-color: #f5f5f5;">
                    <td colspan="6" class="text-right" style="font-weight: bold; font-size: 14px;">GRAND TOTAL:</td>
                    <td colspan="2" class="text-right" style="font-weight: bold; font-size: 14px;">
                        Rp <?php echo e(number_format($grandTotal, 0, ',', '.')); ?>

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

<?php /**PATH E:\PROJEKU\telkom\resources\views\sarpras\sarana\invoice.blade.php ENDPATH**/ ?>