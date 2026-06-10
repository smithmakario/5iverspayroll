<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Welcome to 5ivers Payroll</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
</head>
<body style="margin:0;padding:0;background-color:#f8f9ff;-webkit-font-smoothing:antialiased;">
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#f8f9ff;">
    <tr>
        <td align="center" style="padding:24px 12px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="600" style="max-width:600px;width:100%;background-color:#ffffff;border:1px solid #e5ebeb;">

                {{-- Header --}}
                <tr>
                    <td style="padding:20px 24px;background-color:#ffffff;border-bottom:1px solid #e5ebeb;">
                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td align="left" valign="middle">
                                    <img src="{{ $logoUrl }}" alt="5ivers Foods" width="140" style="display:block;border:0;outline:none;text-decoration:none;max-width:140px;height:auto;">
                                </td>
                                <td align="right" valign="middle" style="font-family:'Work Sans',Arial,Helvetica,sans-serif;font-size:14px;color:#5d5e61;">
                                    5iversPayroll Portal
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- Hero --}}
                <tr>
                    <td style="padding:0;background-color:#0b1c30;background-image:linear-gradient(rgba(11,28,48,0.85),rgba(11,28,48,0.95));">
                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td style="padding:48px 24px;">
                                    <p style="margin:0 0 8px;font-family:'Work Sans',Arial,Helvetica,sans-serif;font-size:12px;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#ffb59d;">Employee Onboarding</p>
                                    <h1 style="margin:0;font-family:'Plus Jakarta Sans',Arial,Helvetica,sans-serif;font-size:28px;line-height:1.2;font-weight:700;color:#fffbff;">Welcome 5ivers Family</h1>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- Greeting --}}
                <tr>
                    <td style="padding:32px 24px 16px;background-color:#ffffff;">
                        <p style="margin:0 0 8px;font-family:'Plus Jakarta Sans',Arial,Helvetica,sans-serif;font-size:22px;font-weight:700;color:#0b1c30;">Welcome to the family, {{ $name }}</p>
                        <p style="margin:0;font-family:'Work Sans',Arial,Helvetica,sans-serif;font-size:16px;line-height:1.6;color:#5d5e61;">
                            Your employee account for <strong style="color:#0b1c30;">5ivers Payroll</strong> has been created. Complete the steps below to access your payslips, profile, and self-service portal.
                        </p>
                    </td>
                </tr>

                {{-- Employee ID --}}
                <tr>
                    <td style="padding:0 24px 24px;">
                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#eff4ff;border:1px solid #e5ebeb;border-radius:8px;">
                            <tr>
                                <td style="padding:16px 20px;">
                                    <p style="margin:0 0 4px;font-family:'Work Sans',Arial,Helvetica,sans-serif;font-size:12px;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#5c4037;">Your Employee ID</p>
                                    <p style="margin:0;font-family:'Plus Jakarta Sans',Arial,Helvetica,sans-serif;font-size:20px;font-weight:700;color:#a83300;">{{ $employeeNumber }}</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- Benefit card 1 --}}
                <tr>
                    <td style="padding:0 24px 12px;">
                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="border:1px solid #e5ebeb;border-radius:12px;background-color:#ffffff;">
                            <tr>
                                <td width="56" valign="top" style="padding:16px 0 16px 16px;">
                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffdbd0;border-radius:8px;">
                                        <tr>
                                            <td style="padding:10px;font-size:20px;line-height:1;">&#128196;</td>
                                        </tr>
                                    </table>
                                </td>
                                <td valign="top" style="padding:16px 16px 16px 8px;">
                                    <p style="margin:0 0 4px;font-family:'Plus Jakarta Sans',Arial,Helvetica,sans-serif;font-size:16px;font-weight:700;color:#0b1c30;">Payslip Access</p>
                                    <p style="margin:0;font-family:'Work Sans',Arial,Helvetica,sans-serif;font-size:14px;line-height:1.4;color:#454749;">View and download your payslips securely from the employee self-service portal.</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- Benefit card 2 --}}
                <tr>
                    <td style="padding:0 24px 12px;">
                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="border:1px solid #e5ebeb;border-radius:12px;background-color:#ffffff;">
                            <tr>
                                <td width="56" valign="top" style="padding:16px 0 16px 16px;">
                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffdbd0;border-radius:8px;">
                                        <tr>
                                            <td style="padding:10px;font-size:20px;line-height:1;">&#128274;</td>
                                        </tr>
                                    </table>
                                </td>
                                <td valign="top" style="padding:16px 16px 16px 8px;">
                                    <p style="margin:0 0 4px;font-family:'Plus Jakarta Sans',Arial,Helvetica,sans-serif;font-size:16px;font-weight:700;color:#0b1c30;">Secure Portal</p>
                                    <p style="margin:0;font-family:'Work Sans',Arial,Helvetica,sans-serif;font-size:14px;line-height:1.4;color:#454749;">Set your password and sign in to manage leave requests, attendance, and payroll information.</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- Benefit card 3 --}}
                <tr>
                    <td style="padding:0 24px 24px;">
                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="border:1px solid #e5ebeb;border-radius:12px;background-color:#ffffff;">
                            <tr>
                                <td width="56" valign="top" style="padding:16px 0 16px 16px;">
                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffdbd0;border-radius:8px;">
                                        <tr>
                                            <td style="padding:10px;font-size:20px;line-height:1;">&#9881;</td>
                                        </tr>
                                    </table>
                                </td>
                                <td valign="top" style="padding:16px 16px 16px 8px;">
                                    <p style="margin:0 0 4px;font-family:'Plus Jakarta Sans',Arial,Helvetica,sans-serif;font-size:16px;font-weight:700;color:#0b1c30;">Profile Management</p>
                                    <p style="margin:0;font-family:'Work Sans',Arial,Helvetica,sans-serif;font-size:14px;line-height:1.4;color:#454749;">Confirm your bank details, tax ID, and contact information so payroll runs smoothly.</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- Steps --}}
                <tr>
                    <td style="padding:0 24px 8px;">
                        <p style="margin:0 0 12px;font-family:'Plus Jakarta Sans',Arial,Helvetica,sans-serif;font-size:16px;font-weight:700;color:#0b1c30;">Get started in two steps</p>
                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td style="padding:0 0 8px;font-family:'Work Sans',Arial,Helvetica,sans-serif;font-size:15px;line-height:1.5;color:#5d5e61;">
                                    <strong style="color:#a83300;">1.</strong> Set your login password using the button below.
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0;font-family:'Work Sans',Arial,Helvetica,sans-serif;font-size:15px;line-height:1.5;color:#5d5e61;">
                                    <strong style="color:#a83300;">2.</strong> Add two guarantors and confirm your profile in the self-service portal.
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- CTA buttons --}}
                <tr>
                    <td align="center" style="padding:24px;">
                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td align="center" style="padding-bottom:12px;">
                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td align="center" style="border-radius:8px;background-color:#a83300;">
                                                <a href="{{ $passwordUrl }}" target="_blank" style="display:inline-block;padding:14px 32px;font-family:'Work Sans',Arial,Helvetica,sans-serif;font-size:16px;font-weight:600;color:#ffffff;text-decoration:none;border-radius:8px;">Set Your Password</a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td align="center" style="border-radius:8px;border:2px solid #0b1c30;">
                                                <a href="{{ $profileUrl }}" target="_blank" style="display:inline-block;padding:12px 32px;font-family:'Work Sans',Arial,Helvetica,sans-serif;font-size:16px;font-weight:600;color:#0b1c30;text-decoration:none;border-radius:8px;">Confirm Your Profile</a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- Expiry note --}}
                <tr>
                    <td style="padding:0 24px 32px;">
                        <p style="margin:0;font-family:'Work Sans',Arial,Helvetica,sans-serif;font-size:13px;line-height:1.5;color:#907065;text-align:center;">
                            This password link expires in {{ $expireMinutes }} minutes. If you did not expect this email, please contact your HR administrator.
                        </p>
                    </td>
                </tr>

                {{-- Footer --}}
                <tr>
                    <td style="padding:32px 24px;background-color:#eff4ff;border-top:1px solid #e5ebeb;text-align:center;">
                        <p style="margin:0 0 16px;font-family:'Work Sans',Arial,Helvetica,sans-serif;font-size:12px;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#0b1c30;">5ivers Foods</p>
                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin-bottom:16px;">
                            <tr>
                                <td style="padding:0 12px;font-family:'Work Sans',Arial,Helvetica,sans-serif;font-size:14px;color:#454749;">Privacy Policy</td>
                                <td style="padding:0 12px;font-family:'Work Sans',Arial,Helvetica,sans-serif;font-size:14px;color:#454749;">Supply Chain Standards</td>
                                <td style="padding:0 12px;font-family:'Work Sans',Arial,Helvetica,sans-serif;font-size:14px;color:#454749;">Contact Us</td>
                            </tr>
                        </table>
                        <p style="margin:0;font-family:'Work Sans',Arial,Helvetica,sans-serif;font-size:14px;line-height:1.5;color:#5d5e61;">
                            &copy; {{ date('Y') }} 5ivers Foods. The Efficient Epicurean. All rights reserved.
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>
