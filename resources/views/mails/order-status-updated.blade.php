{{-- resources/views/emails/order-status-updated.blade.php --}}
<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<title>Order Update</title>
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family:'Helvetica Neue',Arial,sans-serif; background:#F8FAFC; color:#1F2933; }
  .wrapper { max-width:600px; margin:0 auto; padding:32px 16px; }
  .card { background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 2px 16px rgba(10,37,64,.08); }
  .header { background:linear-gradient(135deg,#0A2540,#1DA1A8); padding:32px; text-align:center; }
  .header .brand { color:#fff; font-size:20px; font-weight:700; }
  .body-section { padding:32px; }
  .status-badge {
    display:inline-block; padding:8px 20px; border-radius:20px;
    font-size:14px; font-weight:700; margin:16px 0;
  }
  .info-row { display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #F3F4F6; font-size:13px; }
  .info-row:last-child { border-bottom:none; }
  .info-row .label { color:#6B7280; }
  .info-row .value { font-weight:600; color:#0A2540; }
  .btn-cta { display:inline-block; background:#0A2540; color:#fff; text-decoration:none; padding:12px 28px; border-radius:6px; font-size:13px; font-weight:600; margin-top:20px; }
  .footer { text-align:center; padding:20px; font-size:11px; color:#6B7280; }
</style>
</head>
<body>
<div class="wrapper">
<div class="card">

  <div class="header">
    <div class="brand">🛒 পবনবাহিকা</div>
  </div>

  <div class="body-section">
    <p style="font-size:15px;font-weight:600;color:#0A2540;margin-bottom:4px">
      আপনার Order আপডেট হয়েছে
    </p>
    <p style="font-size:13px;color:#6B7280;line-height:1.65">
      প্রিয় <strong>{{ $order->shipping_name }}</strong>,<br>
      আপনার order-এর status পরিবর্তন হয়েছে।
    </p>

    @php
      $colors = [
        'pending'    => ['bg' => 'rgba(250,204,21,.15)', 'color' => '#854d0e'],
        'processing' => ['bg' => 'rgba(29,161,168,.1)',  'color' => '#1DA1A8'],
        'shipped'    => ['bg' => 'rgba(10,37,64,.1)',    'color' => '#0A2540'],
        'delivered'  => ['bg' => 'rgba(34,197,94,.1)',   'color' => '#166534'],
        'cancelled'  => ['bg' => 'rgba(239,68,68,.1)',   'color' => '#991b1b'],
      ];
      $c = $colors[$order->status] ?? ['bg' => '#f3f4f6', 'color' => '#374151'];
    @endphp

    <div style="text-align:center;margin:20px 0">
      <span class="status-badge"
            style="background:{{ $c['bg'] }};color:{{ $c['color'] }}">
        {{ $order->status_label }}
      </span>
    </div>

    <div style="background:#F8FAFC;border-radius:8px;padding:16px;border:1px solid #E5E7EB">
      <div class="info-row">
        <span class="label">Order নম্বর</span>
        <span class="value">{{ $order->order_number }}</span>
      </div>
      <div class="info-row">
        <span class="label">মোট পরিমাণ</span>
        <span class="value">৳{{ number_format($order->total) }}</span>
      </div>
      <div class="info-row">
        <span class="label">Payment</span>
        <span class="value">{{ $order->payment_label }}</span>
      </div>
    </div>

    @if($order->status === 'shipped')
      <div style="background:rgba(29,161,168,.06);border:1px solid rgba(29,161,168,.2);border-radius:8px;padding:14px;margin-top:16px;font-size:13px;color:#0A2540">
        🚚 আপনার পণ্য পাঠানো হয়েছে। ২–৩ কার্যদিবসের মধ্যে পৌঁছাবে।
      </div>
    @elseif($order->status === 'delivered')
      <div style="background:rgba(34,197,94,.06);border:1px solid rgba(34,197,94,.2);border-radius:8px;padding:14px;margin-top:16px;font-size:13px;color:#166534">
        ✅ আপনার পণ্য পৌঁছেছে। কেনাকাটার জন্য ধন্যবাদ!
      </div>
    @elseif($order->status === 'cancelled')
      <div style="background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.2);border-radius:8px;padding:14px;margin-top:16px;font-size:13px;color:#991b1b">
        ❌ আপনার order বাতিল হয়েছে। কোনো প্রশ্নের জন্য যোগাযোগ করুন।
      </div>
    @endif

    <div style="text-align:center">
      <a href="{{ route('orders.show', $order) }}" class="btn-cta">
        Order Details দেখুন
      </a>
    </div>
  </div>

  <div class="footer">
    © {{ date('Y') }} পবনবাহিকা<br>
    📞 01700-000000 | ✉️ info@pobonbahika.com
  </div>

</div>
</div>
</body>
</html>