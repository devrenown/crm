<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Renown CRM</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 650px;
            background-color: #ffffff;
            margin: 40px auto;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .header {
            background-color: #0d6efd;
            color: #fff;
            padding: 20px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            letter-spacing: 0.5px;
        }
        .body {
            padding: 30px;
            line-height: 1.6;
        }
        .body h2 {
            font-size: 20px;
            margin-top: 0;
            color: #0d6efd;
        }
        .btn {
            display: inline-block;
            background-color: #0d6efd;
            color: #fff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 600;
            margin-top: 15px;
        }
        .footer {
            background-color: #f1f3f4;
            text-align: center;
            padding: 20px;
            font-size: 13px;
            color: #666;
        }
        .footer a {
            color: #0d6efd;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Onboarding Approved !</h1>
    </div>

    <div class="body">
        <h2>Hello {{ $user->name }},</h2>
        <p>We’re excited to let you know that your onboarding process has been successfully <strong>approved</strong>! Welcome officially to <strong>Renown System</strong>.</p>

        <p>You can now access your employee dashboard to manage your profile, view your tasks, and stay up to date with company news.</p>

        <p style="text-align:center;">
            <a href="{{ $loginUrl }}" class="btn">Go to Dashboard</a>
        </p>

        <p>If you have any questions, feel free to reach out to the HR team at 
        <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a>.</p>

        <p>We’re thrilled to have you on board!</p>

        <p>Warm regards,<br>
        <strong>The Renown System Team</strong></p>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} Renown System. All rights reserved.</p>
        <p><a href="{{ url('/') }}">{{ url('/') }}</a></p>
    </div>
</div>

</body>
</html>
