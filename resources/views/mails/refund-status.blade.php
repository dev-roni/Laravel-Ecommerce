
<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family:'Helvetica Neue',Arial,sans-serif; background:#F8FAFC; color:#1F2933; }
  .wrapper { max-width:580px; margin:0 auto; padding:28px 16px; }
  .card { background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 2px 16px rgba(10,37,64,.08); }
  .header { background:linear-gradient(135deg,#0A2540,#1DA1A8); padding:30px; text-align:center; color:#fff; }
  .header .brand { font-size:20px; font-weight:700; }
  .body { padding:28px 32px; }
  .status-box { text-align:center; margin:16px 0 24px; }
  .status-badge { display:inline-block; padding:8px 24px; border-radius:20px; font-size:14px; font-weight:700; }
  .info-row { display:flex; justify-content:space-between; padding:9px 0; border-bottom:1px solid #F3F4F6; font-size:13px; }
  .info-row .label { color:#6B7280; }
  .info-row .value { font-weight:600; color:#0A2540; }
  .note-box { background:#F8FAFC; border-left:3px solid #1DA1A8; padding:12px 14px; margin:16px 0; border-radius:0 6px 6px 0; font-size:13px; line-height:1.65; }
  .footer { text-align:center; padding:20px; font-size:11px; color:#6B7280; }
</style>
</head>
<body>
<div class="wrapper">
<div class="card">
  <div class="header">
    <div class="brand">🛒 পবনবাহিকা</div>
  </div>
  <div class="body">
    <p style="font-size:15px;font-weight:600;color:#0A2540;margin-bottom:4px">
      আপনার Refund Request আপডেট হয়েছে
    </p>
    <p style="font-size:13px;color:#6B7280">
      প্রিয় {{ $refund->user->name }},
    </p>

    @php
      $colors = [
        'approved'  => ['bg'=>'rgba(29,161,168,.1)', 'color'=>'#1DA1A8'],
        'rejected'  => ['bg'=>'rgba(239,68,68,.1)',  'color'=>'#991b1b'],
        'completed' => ['bg'=>'rgba(34,197,94,.1)',  'color'=>'#166534'],
      ];
      $c = $colors[$refund->status] ?? ['bg'=>'#f3f4f6','color'=>'#374151'];
    @endphp

    <div class="status-box">
      <span class="status-badge"
            style="background:{{ $c['bg'] }};color:{{ $c['color'] }}">
        {{ $refund->status_label }}
      </span>
    </div>

    <div style="background:#F8FAFC;border-radius:8px;padding:14px;border:1px solid #E5E7EB">
      <div class="info-row">
        <span class="label">Order &nbsp;</span>
        <span class="value">{{ $refund->order->order_number }}</span>
      </div>
      <div class="info-row">
        <span class="label">Refund পরিমাণ &nbsp;</span>
        <span class="value">৳{{ number_format($refund->amount) }}</span>
      </div>
      <div class="info-row">
        <span class="label">Method &nbsp;</span>
        <span class="value">{{ strtoupper($refund->refund_method) }}: {{ $refund->refund_account }}</span>
      </div>
      @if($refund->transaction_id)
        <div class="info-row" style="border-bottom:none">
          <span class="label">Transaction ID &nbsp; </span>
          <span class="value" style="color:#22C55E">{{ $refund->transaction_id }}</span>
        </div>
      @endif
    </div>

    @if($refund->admin_note)
      <div class="note-box">
        <strong style="color:#0A2540">Note:</strong><br>
        {{ $refund->admin_note }}
      </div>
    @endif

    @if($refund->status === 'completed')
      <div style="background:rgba(34,197,94,.06);border:1px solid rgba(34,197,94,.2);border-radius:8px;padding:12px;margin-top:14px;font-size:13px;color:#166534;text-align:center">
        ✅ আপনার টাকা পাঠানো হয়েছে। ব্যাংক বা MFS-এ ১–২ কার্যদিবসের মধ্যে পাবেন।
      </div>
    @elseif($refund->status === 'rejected')
      <div style="background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.2);border-radius:8px;padding:12px;margin-top:14px;font-size:13px;color:#991b1b;text-align:center">
        কোনো প্রশ্ন থাকলে আমাদের সাথে যোগাযোগ করুন।
      </div>
    @endif
  </div>
  <div class="footer">
    © {{ date('Y') }} পবনবাহিকা | 📞 01700-000000 | ✉ info@pobonbahika.com
  </div>
</div>
</div>
</body>
</html>