{{-- resources/views/emails/otp.blade.php --}}
<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family:'Helvetica Neue',Arial,sans-serif; background:#F8FAFC; }
  .wrapper { max-width:480px; margin:0 auto; padding:28px 16px; }
  .card { background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(10,37,64,.1); }
  .header { background:linear-gradient(135deg,#0A2540,#1DA1A8); padding:28px; text-align:center; }
  .brand { color:#fff; font-size:20px; font-weight:700; }
  .body { padding:32px; text-align:center; }
  .greeting { font-size:15px; color:#1F2933; margin-bottom:8px; }
  .subtitle { font-size:13px; color:#6B7280; line-height:1.65; margin-bottom:28px; }
  .otp-box {
    display:inline-block; background:#F8FAFC;
    border:2px dashed #1DA1A8; border-radius:12px;
    padding:20px 40px; margin-bottom:24px;
  }
  .otp-code {
    font-size:42px; font-weight:900; color:#0A2540;
    letter-spacing:10px; font-family:'Courier New',monospace;
  }
  .otp-label { font-size:11px; color:#6B7280; text-transform:uppercase; letter-spacing:.1em; margin-top:6px; }
  .expire-note {
    background:rgba(255,122,24,.08); border:1px solid rgba(255,122,24,.2);
    border-radius:8px; padding:12px 16px; font-size:13px; color:#9a3412; margin-bottom:20px;
  }
  .warning { font-size:12px; color:#6B7280; line-height:1.65; padding-top:20px; border-top:1px solid #F3F4F6; }
  .footer { text-align:center; padding:16px; font-size:11px; color:#6B7280; }
</style>
</head>
<body>
<div class="wrapper">
<div class="card">
  <div class="header">
    <div class="brand">🛒 পবনবাহিকা</div>
  </div>
  <div class="body">
    <p class="greeting">প্রিয় <strong>{{ $userName }}</strong>,</p>
    <p class="subtitle">
      আপনার login verification code নিচে দেওয়া হলো।<br>
      এই code ব্যবহার করে login সম্পন্ন করুন।
    </p>
    <div class="otp-box">
      <div class="otp-code">{{ $otp }}</div>
      <div class="otp-label">Verification Code</div>
    </div>
    <div class="expire-note">
      ⏱ এই code <strong>১০ মিনিট</strong> পর্যন্ত বৈধ।
    </div>
    <p class="warning">
      আপনি login করার চেষ্টা না করলে এই email ignore করুন
      এবং password পরিবর্তন করুন। এই code কাউকে দেবেন না।
    </p>
  </div>
  <div class="footer">
    © {{ date('Y') }} পবনবাহিকা | 📞 01700-000000
  </div>
</div>
</div>
</body>
</html>
