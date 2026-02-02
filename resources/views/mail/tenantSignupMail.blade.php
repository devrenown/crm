<!DOCTYPE html>
<html lang="en" style="margin:0; padding:0;">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Renown System</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f6f6f6; padding:20px;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:8px; padding:30px;">
                    
                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding-bottom:20px;">
                            <h2 style="margin:0; color:#333;">Welcome to Renown System </h2>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="color:#555; font-size:15px; line-height:1.6;">
                            
                            <p>Hello <strong>{{ $tenant->name }}</strong>,</p>

                            <p>
                                Thank you for registering your organization with 
                                <strong>Renown System</strong>.  
                                We have received your request and your tenant account is currently being reviewed.
                            </p>

                            <p>
                                Our team needs <strong>1–2 business days**</strong> to verify your information and activate your account.
                                Once approved, you will receive another email confirming that your tenant workspace is live.
                            </p>

                            <h4 style="margin-top:25px; color:#333;">Your Tenant Details</h4>

                            <table width="100%" cellpadding="8" cellspacing="0" style="background:#f9f9f9; border-radius:6px;">
                                <tr>
                                    <td><strong>Organization Name:</strong></td>
                                    <td>{{ $tenant->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Domain:</strong></td>
                                    <td>{{ $tenant->domain }}</td>
                                </tr>

                                <tr>
                                    <td><strong>Url:</strong></td>
                                    <td><a href="{{ 'https://' . $tenant->domain }}">{{ 'https://' . $tenant->domain }}</a></td>
                                </tr>

                                <tr>
                                    <td><strong>Plan:</strong></td>
                                    <td>{{ $tenant->plan->name ?? 'Basic' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>Pending Approval</td>
                                </tr>
                            </table>

                            <p style="margin-top:20px;">
                                If you have any questions, feel free to contact us at  
                                <a href="mailto:support@renownsystem.com">support@renownsystem.com</a>.
                            </p>

                            <p>Thank you for choosing Renown System!</p>

                            <p style="margin-top:30px;">
                                Warm regards,<br>
                                <strong>Renown System Team</strong>
                            </p>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="padding-top:30px; color:#888; font-size:12px;">
                            © {{ date('Y') }} Renown System. All rights reserved.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
