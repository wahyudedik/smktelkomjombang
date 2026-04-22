<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Offline - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #116E63 0%, #FDA31B 100%);
            color: #fff;
        }

        .offline-container {
            text-align: center;
            padding: 2rem;
            max-width: 500px;
        }

        .offline-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        p {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .btn {
            display: inline-block;
            padding: 0.75rem 2rem;
            background: #fff;
            color: #116E63;
            text-decoration: none;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: transform 0.2s;
        }

        .btn:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<body>
    <div class="offline-container">
        <div class="offline-icon">📡</div>
        <h1>Tidak Ada Koneksi Internet</h1>
        <p>
            Aplikasi sedang berjalan dalam mode offline.
            Halaman yang pernah Anda kunjungi sebelumnya masih dapat diakses.
        </p>
        <a href="/" class="btn">Kembali ke Beranda</a>
    </div>
</body>

</html>
