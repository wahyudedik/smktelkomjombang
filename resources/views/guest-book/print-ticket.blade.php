<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Kunjungan - {{ $guest->ticket_number }}</title>
    <style>
        @page {
            size: A6 landscape;
            margin: 5mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            color: #1a1a2e;
            margin: 0;
            padding: 20px;
        }

        /* === Print Controls (hidden on print) === */
        .print-controls {
            text-align: center;
            margin-bottom: 24px;
        }

        .print-controls h1 {
            font-size: 18px;
            color: #333;
            margin-bottom: 12px;
        }

        .btn {
            display: inline-block;
            padding: 10px 24px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-print {
            background: #16a34a;
            color: #fff;
        }

        .btn-print:hover {
            background: #15803d;
        }

        .btn-close {
            background: #6b7280;
            color: #fff;
            margin-left: 8px;
        }

        .btn-close:hover {
            background: #4b5563;
        }

        /* === Ticket Card === */
        .ticket-wrapper {
            display: flex;
            justify-content: center;
        }

        .ticket {
            width: 210mm;
            max-width: 100%;
            background: #ffffff;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        }

        /* --- Header --- */
        .ticket-header {
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
            color: #ffffff;
            padding: 18px 24px;
            text-align: center;
        }

        .ticket-header .school-name {
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .ticket-header .ticket-title {
            font-size: 11px;
            font-weight: 400;
            opacity: 0.85;
            margin-top: 2px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* --- Ticket Number --- */
        .ticket-number-section {
            text-align: center;
            padding: 16px 24px 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .ticket-number-label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 4px;
        }

        .ticket-number {
            font-size: 28px;
            font-weight: 800;
            color: #1e3a5f;
            letter-spacing: 2px;
            font-family: 'Courier New', monospace;
        }

        /* --- Guest Info --- */
        .ticket-body {
            padding: 16px 24px;
        }

        .guest-name {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 4px;
        }

        .guest-org {
            font-size: 13px;
            color: #4b5563;
            margin-bottom: 12px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px 20px;
            margin-bottom: 14px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 9px;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        .info-full {
            grid-column: 1 / -1;
        }

        /* --- Status Badge --- */
        .status-badge {
            display: inline-block;
            background: #dcfce7;
            color: #15803d;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 14px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 1px solid #bbf7d0;
        }

        .status-section {
            text-align: center;
            padding: 10px 24px;
            border-top: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
        }

        /* --- Footer --- */
        .ticket-footer {
            padding: 12px 24px;
            text-align: center;
            background: #f9fafb;
        }

        .footer-address {
            font-size: 10px;
            color: #6b7280;
            line-height: 1.5;
        }

        .footer-address strong {
            color: #374151;
        }

        .dashed-divider {
            border: none;
            border-top: 1px dashed #d1d5db;
            margin: 0;
        }

        /* === Print Optimizations === */
        @media print {
            body {
                background: #fff;
                padding: 0;
                margin: 0;
            }

            .print-controls {
                display: none !important;
            }

            .ticket-wrapper {
                display: block;
            }

            .ticket {
                border: 2px dashed #999;
                box-shadow: none;
                width: 100%;
                max-width: none;
                border-radius: 0;
            }

            .ticket-header {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .status-badge {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .status-section {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <!-- Print Controls (hidden when printing) -->
    <div class="print-controls no-print">
        <h1>Preview Tiket Kunjungan</h1>
        <button onclick="window.print()" class="btn btn-print">
            🖨️ Cetak Tiket
        </button>
        <button onclick="window.close()" class="btn btn-close">
            ✕ Tutup
        </button>
    </div>

    <!-- Ticket -->
    <div class="ticket-wrapper">
        <div class="ticket">
            <!-- Header -->
            <div class="ticket-header">
                <div class="school-name">SMK Telekomunikasi Darul Ulum</div>
                <div class="ticket-title">Tiket Kunjungan / Visitor Pass</div>
            </div>

            <!-- Ticket Number -->
            <div class="ticket-number-section">
                <div class="ticket-number-label">Nomor Tiket</div>
                <div class="ticket-number">{{ $guest->ticket_number }}</div>
            </div>

            <!-- Guest Info -->
            <div class="ticket-body">
                <div class="guest-name">{{ $guest->guest_name }}</div>
                @if ($guest->organization)
                    <div class="guest-org">{{ $guest->organization }}{{ $guest->position ? ' — ' . $guest->position : '' }}</div>
                @endif

                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Kategori Kunjungan</span>
                        <span class="info-value">{{ $guest->visit_category ? ucfirst(str_replace('_', ' ', $guest->visit_category)) : '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Tujuan Kunjungan</span>
                        <span class="info-value">{{ $guest->visit_target ?? '-' }}</span>
                    </div>
                    <div class="info-item info-full">
                        <span class="info-label">Keperluan</span>
                        <span class="info-value">{{ $guest->visit_purpose ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Waktu Masuk</span>
                        <span class="info-value">{{ $guest->check_in_at ? $guest->check_in_at->format('d F Y H:i') : '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Dicatat Oleh</span>
                        <span class="info-value">{{ $guest->creator->name ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="status-section">
                <span class="status-badge">✓ CHECK IN</span>
            </div>

            <!-- Footer -->
            <div class="ticket-footer">
                <hr class="dashed-divider">
                <div class="footer-address" style="margin-top: 10px;">
                    <strong>SMK Telekomunikasi Darul Ulum</strong><br>
                    Jl. Raya Jenu, Jenu, Tuban, Jawa Timur<br>
                    Telp: {{ theme_config('phone') ?? '(0356) 123456' }}
                </div>
            </div>
        </div>
    </div>
</body>

</html>
