<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Kelulusan - {{ $kelulusan->nama }}</title>
    <style>
        .certificate-actions {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            display: flex;
            gap: 10px;
        }

        .btn-action {
            padding: 12px 24px;
            background: #2C5F7C;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .btn-action:hover {
            background: #1a3f52;
        }

        .btn-action.btn-print {
            background: #C4A962;
        }

        .btn-action.btn-print:hover {
            background: #9d8649;
        }

        @media print {
            .certificate-actions {
                display: none;
            }
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: A4 portrait;
            margin: 0;
        }

        body {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Georgia', serif;
            background: #F8F6F0;
        }

        html {
            height: 100%;
        }

        .certificate-container {
            width: 210mm;
            height: 297mm;
            padding: 60px;
            position: relative;
            background: #F8F6F0;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }

        .border-outer {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 3px solid #2C5F7C;
            pointer-events: none;
        }

        .border-inner {
            position: absolute;
            top: 30px;
            left: 30px;
            right: 30px;
            bottom: 30px;
            border: 1px solid #2C5F7C;
            pointer-events: none;
        }

        .corner-decoration {
            position: absolute;
            width: 60px;
            height: 60px;
        }

        .corner-decoration.top-left {
            top: 15px;
            left: 15px;
        }

        .corner-decoration.top-right {
            top: 15px;
            right: 15px;
            transform: rotate(90deg);
        }

        .corner-decoration.bottom-left {
            bottom: 15px;
            left: 15px;
            transform: rotate(-90deg);
        }

        .corner-decoration.bottom-right {
            bottom: 15px;
            right: 15px;
            transform: rotate(180deg);
        }

        .content {
            position: relative;
            z-index: 1;
            text-align: center;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 40px 20px 20px 20px;
        }

        .institution-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            letter-spacing: 2px;
            color: #333333;
        }

        .school-subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .school-jurusan {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        .certificate-title {
            font-size: 42px;
            font-weight: bold;
            margin: 30px 0;
            letter-spacing: 4px;
            color: #2C5F7C;
        }

        .awarded-to {
            font-size: 18px;
            font-style: italic;
            margin-bottom: 20px;
            color: #333333;
        }

        .recipient-name {
            font-size: 48px;
            font-weight: bold;
            margin: 20px 0;
            border-bottom: 2px solid #2C5F7C;
            padding-bottom: 10px;
            display: inline-block;
            min-width: 400px;
            color: #2C5F7C;
            text-transform: uppercase;
        }

        .achievement-text {
            font-size: 18px;
            margin: 20px 0;
            line-height: 1.6;
            color: #333333;
        }

        .student-details {
            font-size: 16px;
            margin: 15px 0;
            color: #333333;
        }

        .program-name {
            font-size: 24px;
            font-weight: bold;
            margin: 15px 0;
            color: #2C5F7C;
        }

        .date-section {
            font-size: 16px;
            margin: 30px 0 20px 0;
            color: #333333;
        }

        .signatures {
            display: flex;
            justify-content: space-around;
            margin-top: 40px;
            padding: 0 50px;
        }

        .signature-block {
            text-align: center;
            min-width: 200px;
        }

        .signature-line {
            border-top: 2px solid #333333;
            margin-top: 60px;
            padding-top: 10px;
            font-weight: bold;
            font-size: 16px;
            color: #333333;
        }

        .signature-title {
            font-size: 14px;
            font-style: italic;
            margin-top: 5px;
            color: #333333;
        }

        .seal-decoration {
            position: absolute;
            bottom: 80px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 100px;
            opacity: 0.1;
        }

        @media print {
            body {
                background: white;
            }

            .certificate-container {
                box-shadow: none;
                width: 210mm;
                height: 297mm;
            }
        }
    </style>
</head>

<body>
    <div class="certificate-actions">
        <button onclick="window.print()" class="btn-action btn-print">
            🖨️ Print
        </button>
    </div>

    <div class="certificate-container" id="certificate">
        <div class="border-outer"></div>
        <div class="border-inner"></div>
        
        <!-- Corner Decorations -->
        <svg class="corner-decoration top-left" viewBox="0 0 60 60" fill="none">
            <path d="M0 0 L60 0 L60 5 L5 5 L5 60 L0 60 Z" fill="#C4A962" />
            <circle cx="15" cy="15" r="3" fill="#C4A962" />
        </svg>
        
        <svg class="corner-decoration top-right" viewBox="0 0 60 60" fill="none">
            <path d="M0 0 L60 0 L60 5 L5 5 L5 60 L0 60 Z" fill="#C4A962" />
            <circle cx="15" cy="15" r="3" fill="#C4A962" />
        </svg>
        
        <svg class="corner-decoration bottom-left" viewBox="0 0 60 60" fill="none">
            <path d="M0 0 L60 0 L60 5 L5 5 L5 60 L0 60 Z" fill="#C4A962" />
            <circle cx="15" cy="15" r="3" fill="#C4A962" />
        </svg>
        
        <svg class="corner-decoration bottom-right" viewBox="0 0 60 60" fill="none">
            <path d="M0 0 L60 0 L60 5 L5 5 L5 60 L0 60 Z" fill="#C4A962" />
            <circle cx="15" cy="15" r="3" fill="#C4A962" />
        </svg>
        
        <!-- Seal Decoration -->
        <svg class="seal-decoration" viewBox="0 0 100 100">
            <circle cx="50" cy="50" r="45" stroke="#2C5F7C" stroke-width="3" fill="none" />
            <circle cx="50" cy="50" r="35" stroke="#C4A962" stroke-width="2" fill="none" />
            <path d="M50 15 L55 35 L75 35 L60 47 L65 65 L50 53 L35 65 L40 47 L25 35 L45 35 Z" fill="#C4A962" />
        </svg>

        <div class="content">
            <div>
                <div class="institution-name">
                    SEKOLAH MENENGAH ATAS
                </div>
                <div class="school-subtitle">
                    Portal Sekolah - Sistem Informasi Kelulusan
                </div>
                @if($kelulusan->jurusan)
                    <div class="school-jurusan">
                        Jurusan: {{ $kelulusan->jurusan }}
                    </div>
                @endif

                <div class="certificate-title">
                    SERTIFIKAT KELULUSAN
                </div>

                <div class="awarded-to">
                    Diberikan kepada
                </div>

                <div class="recipient-name">
                    {{ $kelulusan->nama }}
                </div>

                <div class="student-details">
                    NISN: {{ $kelulusan->nisn }}
                    @if($kelulusan->nis)
                        | NIS: {{ $kelulusan->nis }}
                    @endif
                    | Tahun Ajaran: {{ $kelulusan->tahun_ajaran }}
                </div>

                <div class="achievement-text">
                    @if ($kelulusan->status === 'lulus')
                        Telah menyelesaikan semua persyaratan untuk kelulusan di Sekolah Menengah Atas
                        @if($kelulusan->tanggal_lulus)
                            pada tanggal {{ $kelulusan->tanggal_lulus->format('d F Y') }}
                        @endif
                        dan dinyatakan <strong>LULUS</strong>.
                    @elseif($kelulusan->status === 'tidak_lulus')
                        Dinyatakan <strong>TIDAK LULUS</strong> dan perlu mengulang pada tahun ajaran berikutnya.
                    @else
                        Sedang dalam proses <strong>MENGULANG</strong>.
                    @endif
                </div>

                @if($kelulusan->jurusan)
                    <div class="program-name">
                        {{ $kelulusan->jurusan }}
                    </div>
                @endif

                <div class="date-section">
                    @if($kelulusan->tanggal_lulus)
                        {{ $kelulusan->tanggal_lulus->format('d F Y') }}
                    @else
                        {{ now()->format('d F Y') }}
                    @endif
                </div>
            </div>

            <div class="signatures">
                <div class="signature-block">
                    <div class="signature-line">
                        Kepala Sekolah
                    </div>
                    <div class="signature-title">
                        (Headmaster)
                    </div>
                </div>

                <div class="signature-block">
                    <div class="signature-line">
                        Wali Kelas
                    </div>
                    <div class="signature-title">
                        (Class Teacher)
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
