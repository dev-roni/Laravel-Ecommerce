
@extends('frontend.layouts.masterLayout')
@section('title', 'Buy Now — Checkout')

@section('content')
<div class="container py-5">

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('buy-now.order') }}"
          id="buyNowForm">
        @csrf
        <input type="hidden" name="idempotency_key"
               value="{{ session('checkout_key', Str::uuid()) }}">
        <input type="hidden" name="website"> {{-- Honeypot --}}

        <div class="row g-4">

            {{-- বাম: Shipping Form --}}
            <div class="col-lg-7">
                <div class="card mb-4"
                     style="border:none;border-radius:12px;
                            box-shadow:0 2px 16px rgba(10,37,64,.07)">
                    <div class="card-header bg-transparent fw-600"
                         style="border-bottom:1px solid var(--border)">
                        Shipping তথ্য
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-500 small">নাম *</label>
                                <input type="text" name="shipping_name"
                                       value="{{ old('shipping_name', $user->name) }}"
                                       class="form-control @error('shipping_name') is-invalid @enderror">
                                @error('shipping_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-500 small">ফোন *</label>
                                <input type="text" name="shipping_phone"
                                       value="{{ old('shipping_phone', $user->phone) }}"
                                       class="form-control @error('shipping_phone') is-invalid @enderror"
                                       placeholder="01XXXXXXXXX">
                                @error('shipping_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-500 small">শহর *</label>
                                <input type="text" name="shipping_city"
                                       value="{{ old('shipping_city') }}"
                                       class="form-control @error('shipping_city') is-invalid @enderror"
                                       placeholder="ঢাকা, চট্টগ্রাম...">
                                @error('shipping_city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-500 small">ঠিকানা *</label>
                                <textarea name="shipping_address" rows="3"
                                          class="form-control @error('shipping_address') is-invalid @enderror"
                                          placeholder="বাসা নং, রাস্তা, এলাকা">{{ old('shipping_address', $user->address) }}</textarea>
                                @error('shipping_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment Method --}}
                <div class="card mb-4"
                     style="border:none;border-radius:12px;
                            box-shadow:0 2px 16px rgba(10,37,64,.07)">
                    <div class="card-header bg-transparent fw-600"
                         style="border-bottom:1px solid var(--border)">
                        Payment পদ্ধতি
                    </div>
                    <div class="card-body p-4">
                        @foreach([
                            ['value'=>'cod',    'label'=>'💵 Cash on Delivery', 'desc'=>'পণ্য পেলে পরিশোধ করুন'],
                            ['value'=>'online', 'label'=>'💳 Online Payment',   'desc'=>'bKash, Nagad, Card'],
                        ] as $method)
                            <div class="form-check p-3 mb-2 border rounded"
                                 style="cursor:pointer;border-radius:8px !important;
                                        transition:border-color .2s;
                                        {{ old('payment_method','cod') === $method['value']
                                            ? 'border-color:var(--secondary)!important;
                                               background:rgba(29,161,168,.04)'
                                            : '' }}"
                                 onclick="selectPayment('{{ $method['value'] }}', this)">
                                <input class="form-check-input"
                                       type="radio"
                                       name="payment_method"
                                       id="pm_{{ $method['value'] }}"
                                       value="{{ $method['value'] }}"
                                       {{ old('payment_method','cod') === $method['value'] ? 'checked' : '' }}>
                                <label class="form-check-label w-100"
                                       for="pm_{{ $method['value'] }}"
                                       style="cursor:pointer">
                                    <span class="fw-500">{{ $method['label'] }}</span><br>
                                    <small class="text-muted">{{ $method['desc'] }}</small>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Notes --}}
                <div class="card"
                     style="border:none;border-radius:12px;
                            box-shadow:0 2px 16px rgba(10,37,64,.07)">
                    <div class="card-body p-4">
                        <label class="form-label fw-500 small">বিশেষ নির্দেশনা (ঐচ্ছিক)</label>
                        <textarea name="notes" rows="2"
                                  class="form-control"
                                  placeholder="Delivery সংক্রান্ত কোনো নির্দেশনা...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ডান: Order Summary --}}
            <div class="col-lg-5">
                <div class="card"
                     style="border:none;border-radius:12px;
                            box-shadow:0 2px 16px rgba(10,37,64,.07);
                            position:sticky;top:90px">
                    <div class="card-header bg-transparent fw-600"
                         style="border-bottom:1px solid var(--border)">
                        Order Summary
                    </div>
                    <div class="card-body p-4">

                        {{-- Product --}}
                        <div class="d-flex gap-3 mb-4 pb-4"
                             style="border-bottom:1px solid var(--border)">
                            @if($product->primaryImage)
                                <img src="{{ Storage::url($product->primaryImage->image) }}"
                                     style="width:70px;height:70px;
                                            object-fit:cover;border-radius:8px;
                                            flex-shrink:0">
                            @else
                                <div style="width:70px;height:70px;border-radius:8px;
                                            background:var(--background);display:flex;
                                            align-items:center;justify-content:center;
                                            font-size:1.8rem;flex-shrink:0">
                                    📦
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <div style="font-size:.9rem;font-weight:600;
                                            color:var(--primary);line-height:1.3">
                                    {{ $product->name }}
                                </div>
                                @if($variant)
                                    <div class="mt-1">
                                        <span style="font-size:.72rem;
                                                     background:var(--background);
                                                     border:1px solid var(--border);
                                                     border-radius:4px;padding:.2rem .5rem">
                                            {{ $variant->attributeValues->pluck('value')->join(' / ') }}
                                        </span>
                                    </div>
                                @endif
                                <div style="font-size:.82rem;color:var(--text-secondary);margin-top:.3rem">
                                    ৳{{ number_format($unitPrice) }} × {{ $quantity }}
                                </div>
                            </div>
                            <div style="font-size:.95rem;font-weight:700;
                                        color:var(--primary);white-space:nowrap">
                                ৳{{ number_format($subtotal) }}
                            </div>
                        </div>

                        {{-- Price Breakdown --}}
                        <table style="width:100%;font-size:.88rem">
                            <tr>
                                <td style="padding:.4rem 0;color:var(--text-secondary)">Subtotal</td>
                                <td style="text-align:right">৳{{ number_format($subtotal) }}</td>
                            </tr>
                            <tr>
                                <td style="padding:.4rem 0;color:var(--text-secondary)">Shipping</td>
                                <td style="text-align:right">
                                    @if($shipping == 0)
                                        <span style="color:var(--success)">বিনামূল্যে</span>
                                    @else
                                        ৳{{ number_format($shipping) }}
                                    @endif
                                </td>
                            </tr>
                            <tr style="border-top:1px solid var(--border)">
                                <td style="padding:.75rem 0 0;font-weight:700;font-size:1rem;color:var(--primary)">
                                    সর্বমোট
                                </td>
                                <td style="padding:.75rem 0 0;text-align:right;font-weight:700;
                                           font-size:1.15rem;color:var(--primary)">
                                    ৳{{ number_format($total) }}
                                </td>
                            </tr>
                        </table>

                        {{-- CTA --}}
                        <button type="submit"
                                id="placeOrderBtn"
                                class="btn btn-primary w-100 py-2 mt-4"
                                style="font-size:.88rem;letter-spacing:.04em">
                            Order দিন →
                        </button>

                        <a href="{{ url()->previous() }}"
                           class="btn btn-outline-secondary w-100 mt-2 btn-sm">
                            ← ফিরে যান
                        </a>

                        <p class="text-center mt-3 mb-0"
                           style="font-size:.72rem;color:var(--text-secondary)">
                            <i class="bi bi-shield-check me-1"
                               style="color:var(--success)"></i>
                            নিরাপদ Checkout
                        </p>

                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
function selectPayment(val, el) {
    document.querySelectorAll('.form-check').forEach(c => {
        c.style.borderColor = '';
        c.style.background  = '';
    });
    el.style.borderColor = 'var(--secondary)';
    el.style.background  = 'rgba(29,161,168,.04)';
    document.getElementById('pm_' + val).checked = true;
}

// Double submit prevent
document.getElementById('buyNowForm').addEventListener('submit', function() {
    const btn = document.getElementById('placeOrderBtn');
    if (btn.dataset.submitted) { event.preventDefault(); return; }
    btn.dataset.submitted = 'true';
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>প্রক্রিয়াধীন...';
});
</script>
@endsection
