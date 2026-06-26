
<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<title>Invoice — {{ $order->order_number }}</title>
<style>
  /* ── Reset ── */
  * { margin:0; padding:0; box-sizing:border-box; }
  body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 11px;
    color: #1F2933;
    background: #fff;
    padding: 32px 40px;
  }

  /* ── Header ── */
  .header { display:table; width:100%; margin-bottom:28px; }
  .header-left  { display:table-cell; vertical-align:top; width:50%; }
  .header-right { display:table-cell; vertical-align:top; width:50%; text-align:right; }

  .brand-name {
    font-size: 22px; font-weight: 700;
    color: #0A2540; letter-spacing: .04em;
    margin-bottom: 4px;
  }
  .brand-sub { font-size: 10px; color: #6B7280; }

  .invoice-title {
    font-size: 26px; font-weight: 700;
    color: #0A2540; margin-bottom: 6px;
  }
  .invoice-number { font-size: 12px; color: #1DA1A8; font-weight: 600; }

  /* ── Divider ── */
  .divider {
    border: none; border-top: 2px solid #0A2540;
    margin: 16px 0 20px;
  }
  .divider-light {
    border: none; border-top: 1px solid #E5E7EB;
    margin: 12px 0;
  }

  /* ── Info Grid ── */
  .info-grid { display:table; width:100%; margin-bottom:24px; }
  .info-col  { display:table-cell; vertical-align:top; width:33.33%; padding-right:16px; }
  .info-col:last-child { padding-right:0; }

  .info-label {
    font-size: 8px; letter-spacing: .1em;
    text-transform: uppercase; color: #6B7280;
    font-weight: 700; margin-bottom: 5px;
  }
  .info-value { font-size: 11px; color: #1F2933; line-height: 1.6; }
  .info-value strong { color: #0A2540; }

  /* ── Status badge ── */
  .status-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
  }
  .status-paid     { background:#dcfce7; color:#166534; }
  .status-unpaid   { background:#fee2e2; color:#991b1b; }
  .status-pending  { background:#fef9c3; color:#854d0e; }
  .status-delivered{ background:#dcfce7; color:#166534; }
  .status-shipped  { background:#dbeafe; color:#1e40af; }
  .status-cancelled{ background:#fee2e2; color:#991b1b; }

  /* ── Items Table ── */
  .items-table { width:100%; border-collapse:collapse; margin-bottom:20px; }
  .items-table thead tr {
    background: #0A2540;
    color: #fff;
  }
  .items-table thead th {
    padding: 9px 10px;
    font-size: 9px;
    letter-spacing: .08em;
    text-transform: uppercase;
    font-weight: 600;
    text-align: left;
  }
  .items-table thead th:last-child { text-align: right; }
  .items-table thead th:nth-child(3),
  .items-table thead th:nth-child(4) { text-align: center; }

  .items-table tbody tr { border-bottom: 1px solid #F3F4F6; }
  .items-table tbody tr:last-child { border-bottom: none; }
  .items-table tbody tr:nth-child(even) { background: #F8FAFC; }

  .items-table tbody td { padding: 9px 10px; font-size: 11px; vertical-align: top; }
  .items-table tbody td:last-child { text-align: right; font-weight: 600; }
  .items-table tbody td:nth-child(3),
  .items-table tbody td:nth-child(4) { text-align: center; }

  .item-name    { font-weight: 600; color: #0A2540; }
  .item-variant { font-size: 9px; color: #6B7280; margin-top: 2px; }

  /* ── Totals ── */
  .totals-wrap { display:table; width:100%; margin-bottom:24px; }
  .totals-left  { display:table-cell; width:55%; vertical-align:top; }
  .totals-right { display:table-cell; width:45%; vertical-align:top; }

  .totals-table { width:100%; border-collapse:collapse; }
  .totals-table td { padding: 5px 8px; font-size: 11px; }
  .totals-table td:last-child { text-align: right; font-weight: 500; }
  .totals-table .total-final td {
    background: #0A2540;
    color: #fff;
    font-size: 13px;
    font-weight: 700;
    padding: 10px 8px;
  }
  .totals-table .discount-row td { color: #EF4444; }

  .note-box {
    background: #F8FAFC;
    border-left: 3px solid #1DA1A8;
    padding: 10px 12px;
    font-size: 10px;
    color: #6B7280;
    line-height: 1.65;
    border-radius: 0 4px 4px 0;
  }

  /* ── Footer ── */
  .footer {
    margin-top: 28px;
    padding-top: 14px;
    border-top: 1px solid #E5E7EB;
    display: table;
    width: 100%;
  }
  .footer-left  { display:table-cell; font-size:9px; color:#6B7280; line-height:1.7; }
  .footer-right { display:table-cell; text-align:right; font-size:9px; color:#6B7280; }

  .thank-you {
    text-align: center;
    margin: 20px 0 8px;
    font-size: 13px;
    font-weight: 600;
    color: #0A2540;
    letter-spacing: .04em;
  }

  /* ── Watermark for cancelled ── */
  @if($order->status === 'cancelled')
  .watermark {
    position: fixed;
    top: 40%;
    left: 10%;
    font-size: 80px;
    font-weight: 900;
    color: rgba(239,68,68,.08);
    transform: rotate(-35deg);
    letter-spacing: .1em;
    z-index: -1;
  }
  @endif
</style>
</head>
<body>

  @if($order->status === 'cancelled')
    <div class="watermark">CANCELLED</div>
  @endif

  {{-- ── Header ── --}}
  <div class="header">
    <div class="header-left">
      <div class="brand-name">🛒 পবনবাহিকা</div>
      <div class="brand-sub" style="margin-top:4px">
        ঢাকা, বাংলাদেশ<br>
        📞 01700-000000<br>
        ✉ info@pobonbahika.com
      </div>
    </div>
    <div class="header-right">
      <div class="invoice-title">INVOICE</div>
      <div class="invoice-number"># {{ $order->order_number }}</div>
      <div style="margin-top:8px;font-size:10px;color:#6B7280">
        তারিখ: <strong>{{ $order->created_at->format('d M Y') }}</strong>
      </div>
      @if($order->delivered_at)
        <div style="font-size:10px;color:#6B7280;margin-top:3px">
          ডেলিভারি: <strong>{{ $order->delivered_at->format('d M Y') }}</strong>
        </div>
      @endif
    </div>
  </div>

  <hr class="divider">

  {{-- ── Info Grid ── --}}
  <div class="info-grid">

    <div class="info-col">
      <div class="info-label">বিল করা হচ্ছে</div>
      <div class="info-value">
        <strong>{{ $order->shipping_name }}</strong><br>
        {{ $order->shipping_phone }}<br>
        {{ $order->user->email }}
      </div>
    </div>

    <div class="info-col">
      <div class="info-label">Delivery ঠিকানা</div>
      <div class="info-value">
        {{ $order->shipping_address }}<br>
        {{ $order->shipping_city }}, বাংলাদেশ
      </div>
    </div>

    <div class="info-col">
      <div class="info-label">Payment তথ্য</div>
      <div class="info-value">
        পদ্ধতি: <strong>{{ $order->payment_method_label }}</strong><br>
        <br>
        <span class="status-badge status-{{ $order->payment_status }}">
          {{ $order->payment_label }}
        </span>
        &nbsp;
        <span class="status-badge status-{{ $order->status }}">
          {{ $order->status_label }}
        </span>
      </div>
    </div>

  </div>

  {{-- ── Items Table ── --}}
  <table class="items-table">
    <thead>
      <tr>
        <th style="width:5%">#</th>
        <th style="width:45%">পণ্য</th>
        <th style="width:15%">একক মূল্য</th>
        <th style="width:10%">পরিমাণ</th>
        <th style="width:15%;text-align:right">মোট</th>
      </tr>
    </thead>
    <tbody>
      @foreach($order->items as $i => $item)
        <tr>
          <td style="color:#6B7280">{{ $i + 1 }}</td>
          <td>
            <div class="item-name">{{ $item->product_name }}</div>
            @if($item->variant_label)
              <div class="item-variant">{{ $item->variant_label }}</div>
            @endif
          </td>
          <td>৳{{ number_format($item->unit_price) }}</td>
          <td>{{ $item->quantity }}</td>
          <td>৳{{ number_format($item->subtotal) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {{-- ── Totals ── --}}
  <div class="totals-wrap">

    <div class="totals-left">
      @if($order->notes)
        <div class="note-box">
          <strong style="color:#0A2540">নোট:</strong><br>
          {{ $order->notes }}
        </div>
      @endif
    </div>

    <div class="totals-right">
      <table class="totals-table">
        <tr>
          <td style="color:#6B7280">Subtotal</td>
          <td>৳{{ number_format($order->subtotal) }}</td>
        </tr>
        <tr>
          <td style="color:#6B7280">Shipping</td>
          <td>
            @if($order->shipping_charge > 0)
              ৳{{ number_format($order->shipping_charge) }}
            @else
              <span style="color:#22C55E">বিনামূল্যে</span>
            @endif
          </td>
        </tr>
        @if($order->discount > 0)
          <tr class="discount-row">
            <td>ছাড়</td>
            <td>-৳{{ number_format($order->discount) }}</td>
          </tr>
        @endif
        <tr class="total-final">
          <td>সর্বমোট</td>
          <td>৳{{ number_format($order->total) }}</td>
        </tr>
      </table>
    </div>

  </div>

  {{-- ── Thank You ── --}}
  <div class="thank-you">
    ধন্যবাদ আপনার কেনাকাটার জন্য! 🙏
  </div>

  <hr class="divider-light">

  {{-- ── Footer ── --}}
  <div class="footer">
    <div class="footer-left">
      পবনবাহিকা | ঢাকা, বাংলাদেশ<br>
      📞 01700-000000 | ✉ info@pobonbahika.com
    </div>
    <div class="footer-right">
      Invoice তৈরি: {{ now()->format('d M Y, h:i A') }}<br>
      এটি একটি computer generated invoice
    </div>
  </div>

</body>
</html>