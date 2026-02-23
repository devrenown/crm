<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>Welcome to {{ $company->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            background: #ffffff;
            margin: 20px auto;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e5e5e5;
        }

        .header {
            /*background: #0d6efd;
            color: #ffffff;*/
            text-align: center;
            padding: 20px;
        }

        .header img {
            max-width: 120px;
            margin-bottom: 10px;
        }

        .content {
            padding: 25px;
            color: #333;
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            background: #0d6efd;
            color: #ffffff !important;
            padding: 12px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin: 15px 0;
        }

        .footer {
            font-size: 12px;
            color: #888;
            text-align: center;
            padding: 15px;
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- Header -->
        <div class="header">
            <img src="{{  asset($companyLogo ? 'storage/settings/theme/' . $companyLogo : 'images/company-placeholder.png') }}" alt="Logo">
            <h2>Welcome to {{ $company->name }}</h2>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Hi <strong>{{ $user->fullname }}</strong>,</p>

            <p>We’re excited to welcome you aboard! Your account is successfully created in the {{ $company->name }}. To get started, please complete your onboarding process using the link below.</p>

            <a href="{{ $onboarding_url }}" class="btn">Complete Onboarding</a>

            <div style="margin-top: 20px; text-align: center;">
                <strong>If the button above doesn’t work, you can also use this link:</strong>
                <p style="word-break: break-all; color: #0d6efd; margin: 8px 0;">
                    {{ $onboarding_url }}
                </p>
                <p style="color: #555; font-size: 14px;">
                    If you have any problem, copy the link above and paste it directly into your browser’s address bar.
                </p>
            </div>
            

            <p>Here are your login credentials:</p>
            <p>
                <strong>Email:</strong> {{ $user->email }}<br>
                <strong>Password:</strong> REMP1234
            </p>

            <p>For security reasons, please change your password after logging in.</p>

            <p>If you need any help, feel free to reach us at <a href="mailto:{{ $company->email }}">{{ $company->email }}</a>.</p>

            <p>Looking forward to working with you! ✨</p>

            <p>Best Regards,<br>
                <strong>{{ $company->name }} Team</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            © {{ date('Y') }} {{ $company->name }}. All rights reserved.
        </div>
        
    </div>

</body>

</html>
