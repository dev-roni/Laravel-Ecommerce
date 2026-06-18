{{-- resources/views/shop/payment/pending.blade.php --}}
@extends('frontend.layouts.masterLayout')
@section('title', 'Payment')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5 text-center">

      <div style="font-size:3.5rem">💳</div>

      <h4 class="mt-3 mb-2" style="color:var(--primary)">
        Payment করুন
      </h4>
      <p class="text-muted small mb-1">
        Order: <strong>{{ $order->order_number }}</strong>
      </p>
      <p class="mb-4" style="font-size:1.4rem;font-weight:700;color:var(--primary)">
        ৳{{ number_format($order->total) }}
      </p>

      {{-- Auto-submit form --}}
      <form method="POST"
            action="{{ route('payment.initiate', $order) }}"
            id="payForm">
        @csrf
        <button type="submit"
                class="btn btn-primary px-5 py-2 w-100 mb-2">
          <i class="bi bi-credit-card me-2"></i>
          Payment 
        </button>
      </form>

      <p class="text-muted mt-3" style="font-size:.72rem">
        <i class="bi bi-shield-check text-success me-1"></i>
        bKash, Nagad, Card, Net Banking সব সাপোর্ট 
      </p>

    </div>
  </div>
</div>

{{-- Auto redirect --}}
<script>
  // ৩ সেকেন্ড পর auto submit
  let countdown = 0;
  const msg = document.createElement('p');
  msg.className = 'text-muted small mt-2';
  msg.id = 'autoMsg';
  document.getElementById('payForm').after(msg);

  const timer = setInterval(() => {
    msg.textContent = countdown + ' সেকেন্ড পর স্বয়ংক্রিয়ভাবে redirect হবে...';
    if (countdown <= 0) {
      clearInterval(timer);
      document.getElementById('payForm').submit();
    }
    countdown--;
  }, 1000);
</script>
@endsection