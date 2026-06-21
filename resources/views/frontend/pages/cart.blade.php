@extends('frontend/layouts/masterLayout')

@section('content')
<div class="container py-5">
    <h4 class="mb-4">আমার Cart</h4>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(session('stock_errors'))
        <div class="alert alert-warning">
            <strong>Stock সমস্যা:</strong>
            <ul class="mb-0 mt-1">
                @foreach(session('stock_errors') as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($items->isEmpty())
        <div class="text-center py-5">
            <div style="font-size:60px">🛒</div>
            <p class="text-muted mt-3">Cart খালি আছে।</p>
            <a href="{{ route('shop.index') }}" class="btn btn-primary">
                কেনাকাটা শুরু করুন
            </a>
        </div>
    @else
        <div class="row g-4">

            {{-- Cart Items --}}
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body p-0">
                        @foreach($items as $item)
                            <div class="d-flex align-items-center gap-3 p-3 border-bottom"
                                 id="cart-item-{{ $item->id }}">

                                {{-- ছবি --}}
                                @if($item->product->primaryImage)
                                    <img src="{{ Storage::url($item->product->primaryImage->image) }}"
                                         style="width:70px;height:70px;object-fit:cover;border-radius:8px">
                                @endif

                                {{-- তথ্য --}}
                                <div class="flex-grow-1">
                                    <div class="fw-500">{{ $item->product->name }}</div>
                                    @if($item->variant)
                                        <small class="badge bg-light text-dark border">
                                            {{ $item->variant->attributeValues->pluck('value')->join(' / ') }}
                                        </small>
                                    @endif
                                    <div class="text-primary mt-1">
                                        ৳{{ number_format($item->price) }}
                                    </div>
                                </div>

                                {{-- Quantity control --}}
                                <div class="d-flex align-items-center gap-2">
                                    <button class="btn btn-outline-secondary btn-sm"
                                            onclick="updateQty({{ $item->id }}, {{ $item->quantity - 1 }})">
                                        −
                                    </button>
                                    <span id="qty-{{ $item->id }}" style="min-width:30px;text-align:center">
                                        {{ $item->quantity }}
                                    </span>
                                    <button class="btn btn-outline-secondary btn-sm"
                                            onclick="updateQty({{ $item->id }}, {{ $item->quantity + 1 }})">
                                        +
                                    </button>
                                </div>

                                {{-- Subtotal --}}
                                <div style="min-width:90px;text-align:right">
                                    <span id="subtotal-{{ $item->id }}" class="fw-500">
                                        ৳{{ number_format($item->subtotal) }}
                                    </span>
                                </div>

                                {{-- Remove --}}
                                <button class="btn btn-outline-danger btn-sm"
                                        onclick="removeItem({{ $item->id }})">✕</button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Summary --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header fw-500">Order সারসংক্ষেপ</div>
                    <div class="card-body">
                        <table class="table table-sm mb-3">
                            <tr>
                                <td>Subtotal</td>
                                <td class="text-end" id="summary-subtotal">
                                    ৳{{ number_format($subtotal) }}
                                </td>
                            </tr>
                            <tr>
                                <td>Shipping</td>
                                <td class="text-end" id="summary-shipping">
                                    @if($shipping == 0)
                                        <span class="text-success">বিনামূল্যে</span>
                                    @else
                                        ৳{{ number_format($shipping) }}
                                    @endif
                                </td>
                            </tr>

                            {{-- Coupon row — session-এ থাকলে দেখাবে --}}
                            <tr id="coupon-row" style="display:{{ session('coupon') ? 'table-row' : 'none' }}">
                                <td>
                                    Coupon
                                    <span class="badge bg-success" id="coupon-code-badge">
                                        {{ session('coupon.code') }}
                                    </span>
                                </td>
                                <td class="text-end text-danger" id="summary-discount">
                                    -৳{{ number_format(session('coupon.discount', 0)) }}
                                </td>
                            </tr>

                            <tr class="fw-bold">
                                <td>সর্বমোট</td>
                                <td class="text-end" id="summary-total">
                                    ৳{{ number_format($subtotal + $shipping - session('coupon.discount', 0)) }}
                                </td>
                            </tr>
                        </table>

                        <div class="mb-3" id="coupon-box">
        @if(session('coupon'))
            <div class="d-flex align-items-center justify-content-between
                        p-2 rounded"
                 style="background:rgba(34,197,94,.08);border:1px solid rgba(34,197,94,.2)">
                <span class="small">
                    ✅ <strong>{{ session('coupon.code') }}</strong> apply হয়েছে
                </span>
                <button class="btn btn-sm btn-outline-danger"
                        onclick="removeCoupon()">
                    সরান
                </button>
            </div>
        @else
            <div class="input-group input-group-sm">
                <input type="text"
                       id="couponInput"
                       class="form-control"
                       placeholder="Coupon Code"
                       style="text-transform:uppercase">
                <button class="btn btn-outline-primary"
                        onclick="applyCoupon()">
                    Apply
                </button>
            </div>
            <div id="couponMsg" class="small mt-1"></div>
        @endif
    </div>

                        @if($shipping == 0)
                            <div class="alert alert-success py-2 small mb-3">
                                🎉 বিনামূল্যে Shipping পেয়েছেন!
                            </div>
                        @else
                            <div class="alert alert-info py-2 small mb-3">
                                ৳{{ number_format(1000 - $subtotal) }} বেশি কিনলে
                                বিনামূল্যে Shipping পাবেন।
                            </div>
                        @endif

                        <a href="{{ route('checkout.index') }}"
                           class="btn btn-primary w-100">
                            Checkout করুন →
                        </a>
                        <a href="{{ route('shop.index') }}"
                           class="btn btn-outline-secondary w-100 mt-2">
                            ← কেনাকাটা চালিয়ে যান
                        </a>
                    </div>
                </div>
            </div>

        </div>
    @endif
</div>

<script>
    function updateQty(itemId, qty) {
        
        fetch(`/cart/${itemId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type':  'application/json',
                'X-CSRF-TOKEN':  document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ quantity: qty }),
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) {
                alert(data.message);
                return;
            }
            if (qty <= 0) {
                document.getElementById('cart-item-' + itemId)?.remove();
            } else {
                document.getElementById('qty-' + itemId).textContent = qty;
                document.getElementById('subtotal-' + itemId).textContent = '৳' + data.subtotal;
            }
            document.getElementById('summary-total').textContent = '৳' + data.total;

            // Cart count update
            const badge = document.getElementById('cart-count');
            if (badge) badge.textContent = data.count;
        });
    }

    function removeItem(itemId) {

        if (!confirm('এই item সরাবেন?')) return;
        fetch(`/cart/${itemId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        })
        .then(r => r.json())
        .then(data => {
            document.getElementById('cart-item-' + itemId)?.remove();
            const badge = document.getElementById('cart-count');
            if (badge) badge.textContent = data.count;
            if (data.count === 0) location.reload();
        });
    }

    function applyCoupon() {

        const code = document.getElementById('couponInput').value.trim();
        const msgEl = document.getElementById('couponMsg');

        if (!code) {
            msgEl.innerHTML = '<span class="text-danger">Coupon code দিন।</span>';
            return;
        }

        fetch('{{ route("cart.coupon.apply") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ code }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                location.reload(); // সহজে পুরো cart page reload
            } else {
                msgEl.innerHTML = `<span class="text-danger">${data.message}</span>`;
            }
        });
    }

    function removeCoupon() {

        fetch('{{ route("cart.coupon.remove") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
        })
        .then(r => r.json())
        .then(() => location.reload());
    }
</script>
@endsection