<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Happy Birthday</title>
</head>

<body style="margin:0;padding:0;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;">

<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f1f5f9">
<tr>
<td align="center" style="padding:40px 15px;">

    <!-- MAIN CARD -->
    <table width="680" border="0" cellspacing="0" cellpadding="0"
           style="max-width:680px;background:#ffffff;border-radius:16px;overflow:hidden;">

        <!-- HEADER -->
        <tr>
            <td align="center"
                style="background:linear-gradient(135deg,#0f172a,#1e3a8a);padding:50px 30px;color:#ffffff;">

                <div style="font-size:12px;letter-spacing:2px;font-weight:bold;opacity:0.85;">
                    SPECIAL OCCASION
                </div>

                <div style="font-size:38px;font-weight:bold;margin-top:14px;line-height:44px;">
                    Happy Birthday 🎉
                </div>

                <div style="font-size:16px;opacity:0.9;margin-top:12px;line-height:24px;">
                    Wishing you happiness, success & beautiful memories
                </div>

                <!-- AVATAR -->
                <table border="0" cellspacing="0" cellpadding="0" align="center" style="margin-top:30px;">
                    <tr>
                        <td align="center">

                            <img
                                src="{{ $user->avatar ? asset('storage/'.$user->avatar) : asset('images/default-avatar.png') }}"
                                width="110"
                                height="110"
                                alt="{{ trim($user->firstname.' '.$user->lastname) }}"
                                style="display:block;border-radius:50%;border:5px solid #ffffff;object-fit:cover;background:#ffffff;"
                            >

                        </td>
                    </tr>
                </table>

            </td>
        </tr>

        <!-- BLUE LINE -->
        <tr>
            <td height="5" bgcolor="#2563eb"></td>
        </tr>

        <!-- CONTENT -->
        <tr>
            <td style="padding:45px 40px;color:#334155;font-size:15px;line-height:28px;">

                <p style="margin-top:0;">
                    Dear
                    <span style="font-size:18px;font-weight:bold;color:#1e3a8a;">
                        {{ trim($user->firstname.' '.$user->lastname) }}
                    </span>,
                </p>

                <p>
                    Today we celebrate not only your birthday but also your dedication,
                    positivity, professionalism, and the incredible value you bring to our organization.
                </p>

                <p>
                    Your contributions truly make a difference every day, and we are grateful
                    to have you as an important part of our professional family.
                </p>

                <!-- QUOTE -->
                <table width="100%" border="0" cellspacing="0" cellpadding="0"
                       style="margin-top:35px;background:#f8fafc;border-left:4px solid #2563eb;border-radius:8px;">

                    <tr>
                        <td style="padding:24px;font-size:14px;line-height:26px;color:#475569;font-style:italic;">

                            “The more you praise and celebrate your life,
                            the more there is in life to celebrate.” ✨

                        </td>
                    </tr>

                </table>

                <!-- CLOSING -->
                <p style="margin-top:35px;">
                    We look forward to many more achievements, celebrations,
                    and successful moments together.
                </p>

                <p>
                    Enjoy your special day and create beautiful memories! 🥳
                </p>

            </td>
        </tr>

        <!-- FOOTER -->
        <tr>
            <td align="center"
                style="background:#f8fafc;padding:30px 20px;border-top:1px solid #e2e8f0;">

                <div style="font-size:20px;font-weight:bold;color:#1e3a8a;">
                    {{ $companyName }}
                </div>

                <div style="font-size:13px;color:#64748b;margin-top:8px;line-height:22px;">
                    Warm wishes from the entire team.<br>
                    Thank you for being a valuable part of our organization.
                </div>

                <div style="font-size:11px;color:#94a3b8;margin-top:18px;">
                    © {{ date('Y') }} {{ $companyName }}. All rights reserved.
                </div>

            </td>
        </tr>

    </table>

</td>
</tr>
</table>

</body>
</html>