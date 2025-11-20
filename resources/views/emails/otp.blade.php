{{-- resources/views/emails/otp.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $emailSubject }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
            border: 1px solid #e1e1e1;
            border-top: none;
        }
        .otp-code {
            background: #667eea;
            color: white;
            font-size: 32px;
            font-weight: bold;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            letter-spacing: 8px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e1e1e1;
            color: #666;
            font-size: 12px;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name', 'Quiz App') }}</h1>
        <p>Your Verification Code</p>
    </div>

    <div class="content">
        <h2>Hello, {{ $user->full_name }}!</h2>

        <p>You are receiving this email because we received a {{ $purpose }} request for your account.</p>

        <p>Please use the following verification code to complete your request:</p>

        <div class="otp-code">
            {{ $otp }}
        </div>

        <div class="warning">
            <strong>Important:</strong>
            <ul>
                <li>This code will expire in {{ $expiryMinutes }} minutes</li>
                <li>Do not share this code with anyone</li>
                <li>If you didn't request this code, please ignore this email</li>
            </ul>
        </div>

        <p>If you're having trouble with the code, you can:</p>
        <ul>
            <li>Request a new code</li>
            <li>Contact our support team</li>
            <li>Verify your email address directly in the app</li>
        </ul>

        <p>Thank you for using {{ config('app.name', 'our application') }}!</p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name', 'Quiz App') }}. All rights reserved.</p>
        <p>
            This is an automated message. Please do not reply to this email.<br>
            If you need assistance, please contact our support team.
        </p>
    </div>
</body>
</html>
