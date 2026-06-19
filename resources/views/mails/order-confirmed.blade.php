{{-- resources/views/emails/order-confirmed.blade.php --}}
<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Confirmed</title>
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family:'Helvetica Neue',Arial,sans-serif; background:#F8FAFC; color:#1F2933; }
  .wrapper { max-width:600px; margin:0 auto; background:#F8FAFC; padding:32px 16px; }
  .card { background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 2px 16px rgba(10,37,64,.08); }

  /* Header */
  .header { background:linear-gradient(135deg,#0A2540,#1DA1A8); padding:36px 32px; text-align:center; }
  .header .brand { color:#fff; font-size:22px; font-weight:700; letter-spacing:.05em; margin-bottom:4px; }
  .header .subtitle { color:rgba(255,255,255,.7); font-size:13px; }

  /* Success badge */
  .success-badge {
    text-align:center; padding:28px 32px 0;
  }
  .success-icon {
    width:64px; height:64px; border-radius:50%;
    background:rgba(34,197,94,.1); border:2px solid #22C55E;
    display:inline-flex; align-items:center; justify-content:center;
    font-size:28px; margin-bottom:12px;
  }
  .success-title { font-size:20px; font-weight:700; color:#0A2540; margin-bottom:6px; }
  .success-sub { font-size:13px; color:#6B7280; line-height:1.6; }

  /* Order info */
  .section { padding:24px 32px; }
  .section-title {
    font-size:11px; font-weight:700; letter-spacing:.12em;
    text-transform:uppercase; color:#1DA1A8; margin-bottom:14px;
  }
  .info-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
  .info-box {
    background:#F8FAFC; border-radius:8px; padding:12px 14px;
    border:1px solid #E5E7EB;
  }
  .info-label { font-size:10px; letter-spacing:.08em; text-transform:uppercase; color:#6B7280; margin-bottom:4px; }
  .info-value { font-size:14px; font-weight:600; color:#0A2540; }

  /* Items table */
  .items-table { width:100%; border-collapse:collapse; }
  .items-table th {
    font-size:10px; letter-spacing:.08em; text-transform:uppercase;
    color:#6B7280; padding:8px 0; border-bottom:1px solid #E5E7EB;
    text-align:left;
  }
  .items-table td { padding:12px 0; border-bottom:1px solid #F3F4F6; font-size:13px; vertical-align:top; }
  .items-table td:last-child { text-align:right; font-weight:600; }
  .item-name { font-weight:500; color:#1F2933; }
  .item-variant { font-size:11px; color:#6B7280; margin-top:2px; }

  /* Total */
  .total-section { background:#F8FAFC; border-top:1px solid #E5E7EB; padding:16px 32px; }
  .total-row { display:flex; justify-content:space-between; font-size:13px; margin-bottom:8px; color:#6B7280; }
  .total-final { display:flex; justify-content:space-between; font-size:16px; font-weight:700; color:#0A2540; padding-top:10px; border-top:1px solid #E5E7EB; margin-top:4px; }

  /* Shipping */
  .shipping-box { background:#F8FAFC; border:1px solid #E5E7EB; border-radius:8px; padding:14px 16px; font-size:13px; line-height:1.7; color:#1F2933; }

  /* CTA */
  .cta-section { text-align:center; padding:24px 32px; }
  .btn-cta {
    display:inline-block; background:#0A2540; color:#fff;
    text-decoration:none; padding:13px 32px; border-radius:6px;
    font-size:13px; font-weight:600; letter-spacing:.05em;
  }
  .btn-cta:hover { background:#1DA1A8; }

  /* Payment badge */
  .payment-badge {
    display:inline-flex; align-items:center; gap:6px;
    background:rgba(34,197,94,.08); border:1px solid rgba(34,197,94,.2);
    border-radius:20px; padding:4px 12px; font-size:11px;
    font-weight:600; color:#166534; margin-top:8px;
  }

  /* Footer */
  .footer { text-align:center; padding:24px 32px; font-size:11px; color:#6B7280; line-height:1.7; }
  .footer a { color:#1DA1A8; text-decoration:none; }

  @media (max-width:480px) {
    .section { padding:20px; }
    .info-grid { grid-template-columns:1fr; }
    .total-section { padding:16px 20px; }
    .cta-section { padding:20px; }
  }
</style>
</head>
<body>
<div class="wrapper">
<div class="card">

  {{-- Header --}}
  <div class="header">
    <div class="brand">🛒 পবনবাহিকা</div>
    <div class="subtitle">আপনার বিশ্বস্ত অনলাইন শপ</div>
  </div>

  {{-- Success Badge --}}
  <div class="success-badge">
    <div class="success-icon">✅</div>
    <div class="success-title">Order Confirmed!</div>
    <div class="success-sub">
      আপনার order সফলভাবে গ্রহণ করা হয়েছে।<br>
      শীঘ্রই আপনার সাথে যোগাযোগ করা হবে।
    </div>
    @if($order->payment_status === 'paid')
      <div class="payment-badge">✓ Payment Confirmed</div>
    @else
      <div style="display:inline-block;background:rgba(255,122,24,.08);border:1px solid rgba(255,122,24,.2);border-radius:20px;padding:4px 12px;font-size:11px;font-weight:600;color:#9a3412;margin-top:8px">
        💵 Cash on Delivery
      </div>
    @endif
  </div>

  {{-- Order Info --}}
  <div class="section">
    <div class="section-title">Order তথ্য</div>
    <div class="info-grid">
      <div class="info-box">
        <div class="info-label">Order নম্বর</div>
        <div class="info-value">{{ $order->order_number }}</div>
      </div>
      <div class="info-box">
        <div class="info-label">তারিখ</div>
        <div class="info-value">{{ $order->created_at->format('d M Y') }}</div>
      </div>
      <div class="info-box">
        <div class="info-label">Payment পদ্ধতি</div>
        <div class="info-value">{{ $order->payment_method_label }}</div>
      </div>
      <div class="info-box">
        <div class="info-label">অবস্থা</div>
        <div class="info-value">{{ $order->status_label }}</div>
      </div>
    </div>
  </div>

  {{-- Items --}}
  <div class="section" style="padding-top:0">
    <div class="section-title">পণ্যের তালিকা</div>
    <table class="items-table">
      <thead>
        <tr>
          <th>পণ্য</th>
          <th style="text-align:center">পরিমাণ</th>
          <th style="text-align:right">মূল্য</th>
        </tr>
      </thead>
      <tbody>
        @foreach($order->items as $item)
          <tr>
            <td>
              <div class="item-name">{{ $item->product_name }}</div>
              @if($item->variant_label)
                <div class="item-variant">{{ $item->variant_label }}</div>
              @endif
            </td>
            <td style="text-align:center;color:#6B7280">× {{ $item->quantity }}</td>
            <td>৳{{ number_format($item->subtotal) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  {{-- Total --}}
  <div class="total-section">
    <div class="total-row">
      <span>Subtotal</span>
      <span>৳{{ number_format($order->subtotal) }}</span>
    </div>
    @if($order->shipping_charge > 0)
      <div class="total-row">
        <span>Shipping</span>
        <span>৳{{ number_format($order->shipping_charge) }}</span>
      </div>
    @else
      <div class="total-row">
        <span>Shipping</span>
        <span style="color:#22C55E">বিনামূল্যে</span>
      </div>
    @endif
    @if($order->discount > 0)
      <div class="total-row">
        <span>ছাড়</span>
        <span style="color:#EF4444">-৳{{ number_format($order->discount) }}</span>
      </div>
    @endif
    <div class="total-final">
      <span>সর্বমোট</span>
      <span>৳{{ number_format($order->total) }}</span>
    </div>
  </div>

  {{-- Shipping Address --}}
  <div class="section">
    <div class="section-title">Delivery ঠিকানা</div>
    <div class="shipping-box">
      <strong>{{ $order->shipping_name }}</strong><br>
      {{ $order->shipping_phone }}<br>
      {{ $order->shipping_address }}<br>
      {{ $order->shipping_city }}
    </div>
  </div>

  {{-- CTA --}}
  <div class="cta-section">
    <a href="{{ route('orders.show', $order) }}" class="btn-cta">
      Order Track করুন
    </a>
    <p style="font-size:12px;color:#6B7280;margin-top:14px">
      কোনো সমস্যা হলে আমাদের সাথে যোগাযোগ করুন।<br>
      📞 01700-000000 | ✉️ info@pobonbahika.com
    </p>
  </div>

  {{-- Footer --}}
  <div class="footer">
    © {{ date('Y') }} পবনবাহিকা — সর্বস্বত্ব সংরক্ষিত<br>
    <a href="{{ route('shop.index') }}">shop.pobonbahika.com</a>
  </div>

</div>
</div>
</body>
</html>