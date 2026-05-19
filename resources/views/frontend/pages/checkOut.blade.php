@extends('frontend/layouts/masterLayout')

@section('content')
<div class="container py-5">
    <h4 class="mb-4">Checkout</h4>

    <form method="POST" action="{{ route('checkout.store') }}">
        @csrf
        <div class="row g-4">

            {{-- Shipping Info --}}
            <div class="col-md-7">

                <div class="card mb-4">
                    <div class="card-header fw-500">Shipping তথ্য</div>
                    <div class="card-body">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">নাম *</label>
                                <input type="text" name="shipping_name"
                                       value="{{ old('shipping_name', $user->name) }}"
                                       class="form-control @error('shipping_name') is-invalid @enderror">
                                @error('shipping_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">ফোন *</label>
                                <input type="text" name="shipping_phone"
                                       value="{{ old('shipping_phone', $user->phone) }}"
                                       class="form-control @error('shipping_phone') is-invalid @enderror">
                                @error('shipping_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">শহর *</label>
                                <input type="text" name="shipping_city"
                                       value="{{ old('shipping_city') }}"
                                       class="form-control @error('shipping_city') is-invalid @enderror"
                                       placeholder="ঢাকা, চট্টগ্রাম...">
                                @error('shipping_city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">সম্পূর্ণ ঠিকানা *</label>
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
                <div class="card mb-4">
                    <div class="card-header fw-500">Payment পদ্ধতি</div>
                    <div class="card-body">
                        @foreach([
                            ['value' => 'cod',   'label' => '💵 ক্যাশ অন ডেলিভারি',  'desc' => 'পণ্য পেলে পরিশোধ করুন'],
                            ['value' => 'bkash', 'label' => '📱 bKash',               'desc' => 'bKash-এ পেমেন্ট করুন'],
                            ['value' => 'nagad', 'label' => '📱 Nagad',               'desc' => 'Nagad-এ পেমেন্ট করুন'],
                        ] as $method)
                            <div class="form-check mb-3 p-3 border rounded
                                        {{ old('payment_method', 'cod') === $method['value'] ? 'border-primary bg-light' : '' }}"
                                 style="cursor:pointer"
                                 onclick="selectPayment('{{ $method['value'] }}', this)">
                                <input class="form-check-input" type="radio"
                                       name="payment_method"
                                       id="pay_{{ $method['value'] }}"
                                       value="{{ $method['value'] }}"
                                       {{ old('payment_method', 'cod') === $method['value'] ? 'checked' : '' }}>
                                <label class="form-check-label w-100"
                                       for="pay_{{ $method['value'] }}"
                                       style="cursor:pointer">
                                    <span class="fw-500">{{ $method['label'] }}</span>
                                    <br>
                                    <small class="text-muted">{{ $method['desc'] }}</small>
                                </label>
                            </div>
                        @endforeach

                        @error('payment_method')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Notes --}}
                <div class="card mb-4">
                    <div class="card-header fw-500">বিশেষ নির্দেশনা (ঐচ্ছিক)</div>
                    <div class="card-body">
                        <textarea name="notes" rows="2" class="form-control"
                                  placeholder="ডেলিভারি সংক্রান্ত কোনো নির্দেশনা থাকলে লিখুন">{{ old('notes') }}</textarea>
                    </div>
                </div>

            </div>

            {{-- Order Summary --}}
            <div class="col-md-5">
                <div class="card sticky-top" style="top:20px">
                    <div class="card-header fw-500">Order সারসংক্ষেপ</div>
                    <div class="card-body">

                        {{-- Items --}}
                        @foreach($items as $item)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <span class="small">{{ $item->product->name }}</span>
                                    @if($item->variant)
                                        <br>
                                        <span class="badge bg-light text-dark border"
                                              style="font-size:10px">
                                            {{ $item->variant->attributeValues->pluck('value')->join(' / ') }}
                                        </span>
                                    @endif
                                    <span class="text-muted small"> × {{ $item->quantity }}</span>
                                </div>
                                <span class="small fw-500">
                                    ৳{{ number_format($item->subtotal) }}
                                </span>
                            </div>
                        @endforeach

                        <hr>

                        <table class="table table-sm mb-3">
                            <tr>
                                <td>Subtotal</td>
                                <td class="text-end">৳{{ number_format($subtotal) }}</td>
                            </tr>
                            <tr>
                                <td>Shipping</td>
                                <td class="text-end">
                                    @if($shipping == 0)
                                        <span class="text-success">বিনামূল্যে</span>
                                    @else
                                        ৳{{ number_format($shipping) }}
                                    @endif
                                </td>
                            </tr>
                            <tr class="fw-bold fs-6">
                                <td>সর্বমোট</td>
                                <td class="text-end text-primary">
                                    ৳{{ number_format($total) }}
                                </td>
                            </tr>
                        </table>

                        <button type="submit" class="btn btn-primary w-100 py-2">
                            Order দিন →
                        </button>

                        <a href="{{ route('cart.index') }}"
                           class="btn btn-outline-secondary w-100 mt-2">
                            ← Cart-এ ফিরুন
                        </a>

                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
function selectPayment(value, el) {
    document.querySelectorAll('.form-check').forEach(c => {
        c.classList.remove('border-primary', 'bg-light');
    });
    el.classList.add('border-primary', 'bg-light');
    document.getElementById('pay_' + value).checked = true;
}
</script>
@endsection