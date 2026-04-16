<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Faroise – Verification Code</title>
</head>
<body style="margin:0;padding:0;background:#0f172a;font-family:'Figtree',Arial,sans-serif;color:#e2e8f0">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#0f172a;padding:40px 0">
        <tr>
            <td align="center">
                <table width="480" cellpadding="0" cellspacing="0" style="background:#1e293b;border-radius:16px;overflow:hidden;border:1px solid #334155">

                    {{-- Header --}}
                    <tr>
                        <td style="background:#0e7490;padding:28px 40px;text-align:center">
                            <p style="margin:0;font-size:22px;font-weight:700;color:#fff;letter-spacing:-0.5px">🎵 Faroise</p>
                            <p style="margin:6px 0 0;font-size:13px;color:#a5f3fc">Verification Code</p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:36px 40px">
                            <p style="margin:0 0 8px;font-size:15px;color:#94a3b8">
                                @if ($context === 'profile')
                                    You requested to change your password.
                                @else
                                    You requested a password reset.
                                @endif
                                Use the code below — it expires in <strong style="color:#e2e8f0">10 minutes</strong>.
                            </p>

                            {{-- OTP Box --}}
                            <div style="margin:28px 0;text-align:center">
                                <span style="display:inline-block;background:#0f172a;border:2px dashed #0e7490;border-radius:12px;padding:18px 40px;font-size:42px;font-weight:800;letter-spacing:12px;color:#22d3ee;font-family:monospace">{{ $otp }}</span>
                            </div>

                            <p style="margin:0;font-size:13px;color:#64748b;text-align:center">
                                If you didn't request this, you can safely ignore this email.
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:20px 40px;border-top:1px solid #334155;text-align:center">
                            <p style="margin:0;font-size:12px;color:#475569">© {{ date('Y') }} Faroise · White noise for focus &amp; sleep</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
