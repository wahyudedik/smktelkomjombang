<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Portal Sekolah</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #3b82f6;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #3b82f6;
            margin-bottom: 10px;
        }

        .welcome {
            font-size: 28px;
            color: #1f2937;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #6b7280;
            font-size: 16px;
        }

        .content {
            margin-bottom: 30px;
        }

        .credentials {
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #3b82f6;
            margin: 20px 0;
        }

        .credential-item {
            margin-bottom: 10px;
            font-size: 16px;
        }

        .credential-label {
            font-weight: bold;
            color: #374151;
            display: inline-block;
            width: 120px;
        }

        .credential-value {
            color: #1f2937;
            font-family: 'Courier New', monospace;
            background-color: #e5e7eb;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .button {
            display: inline-block;
            background-color: #3b82f6;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 20px 0;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #2563eb;
        }

        .security-note {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }

        .security-note h4 {
            color: #92400e;
            margin-top: 0;
            margin-bottom: 10px;
        }

        .security-note p {
            color: #92400e;
            margin: 0;
            font-size: 14px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }

        .role-badge {
            display: inline-block;
            background-color: #dbeafe;
            color: #1e40af;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo">🏫 Portal Sekolah</div>
            <div class="welcome">Welcome, {{ $user->name }}!</div>
            <div class="subtitle">Your account has been created successfully</div>
        </div>

        <div class="content">
            <p>Hello <strong>{{ $user->name }}</strong>,</p>

            <p>You have been invited to join <strong>Portal Sekolah</strong> as a <span
                    class="role-badge">{{ $user->roles->first() ? ucfirst($user->roles->first()->name) : 'User' }}</span>.</p>

            <p>Your account has been created and you can now access the portal using the credentials below:</p>

            <div class="credentials">
                <div class="credential-item">
                    <span class="credential-label">Email:</span>
                    <span class="credential-value">{{ $user->email }}</span>
                </div>
                <div class="credential-item">
                    <span class="credential-label">Password:</span>
                    <span class="credential-value">{{ $tempPassword }}</span>
                </div>
                <div class="credential-item">
                    <span class="credential-label">Role:</span>
                    <span class="credential-value">{{ $user->roles->first() ? ucfirst($user->roles->first()->name) : 'No Role' }}</span>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ $loginUrl }}" class="button">Login to Portal</a>
            </div>

            <div class="security-note">
                <h4>🔒 Security Notice</h4>
                <p>
                    <strong>Important:</strong> For security reasons, please change your password immediately after your
                    first login.
                    This temporary password should not be shared with anyone.
                </p>
            </div>

            <h3>What's Next?</h3>
            <ul>
                <li>Click the "Login to Portal" button above</li>
                <li>Use your credentials to sign in</li>
                <li>Change your password in the profile settings</li>
                <li>Explore the features available for your role</li>
            </ul>

            <p>If you have any questions or need assistance, please contact your system administrator.</p>
        </div>

        <div class="footer">
            <p>This is an automated message from Portal Sekolah.</p>
            <p>© {{ date('Y') }} Portal Sekolah. All rights reserved. wahyu dedik developer</p>
            <p><small>If you did not expect this invitation, please ignore this email.</small></p>
        </div>
    </div>
</body>

</html>
