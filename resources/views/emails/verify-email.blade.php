<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email – InkForge Solutions</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background-color: #030712; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        .wrapper { max-width: 560px; margin: 40px auto; padding: 20px; }
        .card { background-color: #111827; border: 1px solid #1f2937; border-radius: 16px; overflow: hidden; }
        .header { background-color: #111827; padding: 32px 40px 24px; border-bottom: 1px solid #1f2937; text-align: center; }
        .logo { display: inline-flex; align-items: center; gap: 10px; }
        .logo-icon { width: 40px; height: 40px; background-color: #f97316; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; color: white; }
        .logo-text { font-size: 18px; font-weight: 700; color: #ffffff; }
        .body { padding: 36px 40px; }
        .icon-wrap { width: 56px; height: 56px; background-color: rgba(249,115,22,0.1); border: 1px solid rgba(249,115,22,0.2); border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
        h1 { font-size: 22px; font-weight: 700; color: #ffffff; text-align: center; margin-bottom: 10px; }
        .subtitle { font-size: 14px; color: #9ca3af; text-align: center; line-height: 1.6; margin-bottom: 28px; }
        .btn-wrap { text-align: center; margin-bottom: 28px; }
        .btn { display: inline-block; background-color: #f97316; color: #ffffff; font-size: 14px; font-weight: 600; padding: 14px 32px; border-radius: 10px; text-decoration: none; }
        .divider { border: none; border-top: 1px solid #1f2937; margin: 24px 0; }
        .fallback { font-size: 12px; color: #6b7280; line-height: 1.6; }
        .fallback a { color: #f97316; word-break: break-all; }
        .footer { padding: 20px 40px; border-top: 1px solid #1f2937; text-align: center; }
        .footer p { font-size: 12px; color: #4b5563; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            {{-- Header --}}
            <div class="header">
                <div class="logo">
                    <div class="logo-icon">IF</div>
                    <span class="logo-text">InkForge Solutions</span>
                </div>
            </div>

            {{-- Body --}}
            <div class="body">
                <div class="icon-wrap">
                    <svg width="26" height="26" fill="none" stroke="#f97316" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>

                <h1>Verify your email address</h1>
                <p class="subtitle">
                    Thanks for signing up with InkForge Solutions!<br>
                    Please click the button below to verify your email address and activate your account.
                </p>

                <div class="btn-wrap">
                    <a href="{{ $url }}" class="btn">Verify Email Address</a>
                </div>

                <p class="subtitle" style="font-size:13px;">
                    This link will expire in <strong style="color:#ffffff;">60 minutes</strong>.<br>
                    If you did not create an account, no action is required.
                </p>

                <hr class="divider">

                <p class="fallback">
                    If the button above doesn't work, copy and paste this URL into your browser:<br>
                    <a href="{{ $url }}">{{ $url }}</a>
                </p>
            </div>

            {{-- Footer --}}
            <div class="footer">
                <p>© {{ date('Y') }} InkForge Solutions. All rights reserved.<br>
                Custom T-Shirt Printing Manufacturing Execution System</p>
            </div>
        </div>
    </div>
</body>
</html>
