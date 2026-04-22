<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Surat {{ $letter->letter_number }}</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 16pt;
            text-transform: uppercase;
        }

        .header h2 {
            margin: 0;
            font-size: 14pt;
            text-transform: uppercase;
        }

        .header p {
            margin: 0;
            font-size: 10pt;
        }

        .content {
            margin-top: 20px;
        }

        .meta-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .meta-table td {
            vertical-align: top;
        }

        .body-text {
            text-align: justify;
            margin-bottom: 30px;
        }

        .signature {
            float: right;
            width: 200px;
            text-align: center;
            margin-top: 50px;
        }

        .signature-name {
            margin-top: 70px;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>KOP SURAT SEKOLAH</h1>
        <h2>SMK NEGERI CONTOH</h2>
        <p>Jalan Pendidikan No. 123, Kota Pelajar, Telp. (021) 123456</p>
    </div>

    <div class="content">
        <table class="meta-table">
            <tr>
                <td width="15%">Nomor</td>
                <td width="2%">:</td>
                <td width="48%">{{ $letter->letter_number }}</td>
                <td width="35%" style="text-align: right;">{{ $letter->letter_date->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td>:</td>
                <td>-</td>
                <td></td>
            </tr>
            <tr>
                <td>Perihal</td>
                <td>:</td>
                <td><strong>{{ $letter->subject }}</strong></td>
                <td></td>
            </tr>
        </table>

        <div style="margin-top: 30px; margin-bottom: 20px;">
            <p>Kepada Yth.<br>
                <strong>{{ $letter->recipient }}</strong><br>
                di Tempat
            </p>
        </div>

        <div class="body-text">
            <p>Dengan hormat,</p>

            @if ($letter->description)
                <p>{!! nl2br(e($letter->description)) !!}</p>
            @else
                <p>Sehubungan dengan surat ini, kami sampaikan informasi terkait perihal di atas.</p>
            @endif

            <p>Demikian surat ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.</p>
        </div>

        <div class="signature">
            <p>Hormat Kami,<br>Kepala Sekolah</p>
            <div class="signature-name">Nama Kepala Sekolah</div>
            <div>NIP. 19800101 200001 1 001</div>
        </div>
    </div>
</body>

</html>
