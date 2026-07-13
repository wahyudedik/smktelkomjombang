<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fa;
            color: #333333;
        }

        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }

        .email-header {
            padding: 30px 40px;
            text-align: center;

            @if ($type === 'success')
                background: linear-gradient(135deg, #10b981, #059669);
            @elseif($type === 'warning')
                background: linear-gradient(135deg, #f59e0b, #d97706);
            @elseif($type === 'error')
                background: linear-gradient(135deg, #ef4444, #dc2626);
            @else
                background: linear-gradient(135deg, #3b82f6, #2563eb);
            @endif
        }

        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }

        .email-body {
            padding: 40px;
        }

        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
            color: #374151;
        }

        .message {
            font-size: 15px;
            line-height: 1.7;
            color: #4b5563;
            margin-bottom: 30px;
        }

        .cta-button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #3b82f6;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .info-box {
            background-color: #f0f9ff;
            border-left: 4px solid #3b82f6;
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 0 8px 8px 0;
        }

        .info-box.warning {
            background-color: #fffbeb;
            border-left-color: #f59e0b;
        }

        .info-box.error {
            background-color: #fef2f2;
            border-left-color: #ef4444;
        }

        .info-box.success {
            background-color: #f0fdf4;
            border-left-color: #10b981;
        }

        .info-box p {
            margin: 0;
            font-size: 14px;
            color: #4b5563;
        }

        .divider {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 25px 0;
        }

        .footer {
            padding: 25px 40px;
            background-color: #f9fafb;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }

        .footer p {
            margin: 5px 0;
            font-size: 12px;
            color: #9ca3af;
        }

        .footer a {
            color: #6b7280;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <!-- Header -->
        <div class="email-header">
            <h1>{{ $appName }}</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p class="greeting">Halo, {{ $userName }} 👋</p>

            <div class="info-box {{ $type }}">
                <p><strong>{{ $title }}</strong></p>
            </div>

            <div class="message">
                {!! nl2br(e($message)) !!}
            </div>

            @if (isset($metadata['action_url']) && $metadata['action_url'])
                <a href="{{ $metadata['action_url'] }}" class="cta-button">
                    {{ $metadata['action_text'] ?? 'Lihat Detail' }}
                </a>
            @endif

            <hr class="divider">

            <p style="font-size: 13px; color: #9ca3af;">
                Jika Anda tidak merasa melakukan permintaan ini, silakan abaikan email ini atau hubungi administrator
                sekolah.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>{{ $appName }}</strong></p>
            <p>Sistem Informasi SMK Telekomunikasi Darul Ulum</p>
            <p>&copy; {{ $year }} {{ $appName }}. All rights reserved.</p>
            <p style="margin-top: 10px;">
                <a href="{{ $appUrl }}">{{ $appUrl }}</a>
            </p>
        </div>
    </div>
</body>

</html>
