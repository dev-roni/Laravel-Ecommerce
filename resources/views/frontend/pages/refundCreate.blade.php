
@extends('frontend.layouts.masterLayout')
@section('title', 'Refund Request')

@section('content')
<div class="container py-5" style="max-width:640px">

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('orders.show', $order) }}"
           class="btn btn-outline-secondary btn-sm">← ফিরুন</a>
        <h4 class="mb-0" style="color:var(--primary)">Refund Request</h4>
    </div>

    {{-- Order Summary --}}
    <div class="card mb-4"
         style="border:none;border-radius:12px;
                border-left:4px solid var(--secondary);
                box-shadow:0 2px 12px rgba(10,37,64,.06)">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-6">
                    <div style="font-size:.7rem;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.08em">Order</div>
                    <div style="font-weight:600;color:var(--primary)">{{ $order->order_number }}</div>
                </div>
                <div class="col-6">
                    <div style="font-size:.7rem;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.08em">পরিমাণ</div>
                    <div style="font-weight:600;color:var(--primary)">৳{{ number_format($order->total) }}</div>
                </div>
                <div class="col-6">
                    <div style="font-size:.7rem;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.08em">তারিখ</div>
                    <div style="font-size:.9rem">{{ $order->created_at->format('d M Y') }}</div>
                </div>
                <div class="col-6">
                    <div style="font-size:.7rem;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.08em">Payment</div>
                    <div style="font-size:.9rem">{{ $order->payment_method_label }}</div>
                </div>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card"
         style="border:none;border-radius:12px;
                box-shadow:0 2px 12px rgba(10,37,64,.06)">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('refunds.store', $order) }}">
                @csrf

                {{-- Amount --}}
                <div class="mb-4">
                    <label class="form-label fw-600">
                        Refund পরিমাণ (সর্বোচ্চ ৳{{ number_format($order->total) }}) *
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">৳</span>
                        <input type="number"
                               name="amount"
                               value="{{ old('amount', $order->total) }}"
                               step="0.01"
                               max="{{ $order->total }}"
                               class="form-control @error('amount') is-invalid @enderror">
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-text">Partial refund-ও দেওয়া যাবে।</div>
                </div>

                {{-- Reason --}}
                <div class="mb-4">
                    <label class="form-label fw-600">কারণ * </label>
                    <textarea name="reason"
                              rows="4"
                              class="form-control @error('reason') is-invalid @enderror"
                              placeholder="কেন refund চাইছেন বিস্তারিত লিখুন...">{{ old('reason') }}</textarea>
                    @error('reason')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Refund Method --}}
                <div class="mb-3">
                    <label class="form-label fw-600">Refund পদ্ধতি *</label>
                    <div class="row g-2">
                        @foreach([
                            ['value'=>'bkash', 'label'=>'bKash',  'icon'=>'📱'],
                            ['value'=>'nagad', 'label'=>'Nagad',  'icon'=>'📱'],
                            ['value'=>'bank',  'label'=>'Bank',   'icon'=>'🏦'],
                        ] as $method)
                            <div class="col-4">
                                <input type="radio"
                                       class="btn-check"
                                       name="refund_method"
                                       id="method_{{ $method['value'] }}"
                                       value="{{ $method['value'] }}"
                                       {{ old('refund_method') === $method['value'] ? 'checked' : '' }}>
                                <label class="btn btn-outline-secondary w-100"
                                       for="method_{{ $method['value'] }}">
                                    {{ $method['icon'] }} {{ $method['label'] }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('refund_method')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Account Number --}}
                <div class="mb-4">
                    <label class="form-label fw-600">Account / Number *</label>
                    <input type="text"
                           name="refund_account"
                           value="{{ old('refund_account') }}"
                           class="form-control @error('refund_account') is-invalid @enderror"
                           placeholder="01XXXXXXXXX অথবা Bank Account Number">
                    @error('refund_account')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Notice --}}
                <div class="alert"
                     style="background:rgba(29,161,168,.06);
                            border:1px solid rgba(29,161,168,.2);
                            border-radius:8px;font-size:.82rem;
                            color:var(--text-primary)">
                    <i class="bi bi-info-circle me-2" style="color:var(--secondary)"></i>
                    Refund request জমার পর ৩–৫ কার্যদিবসের মধ্যে আমরা যোগাযোগ করব।
                    Approved হলে ৭ কার্যদিবসের মধ্যে টাকা পাঠানো হবে।
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 mt-2">
                    Refund Request জমা দিন
                </button>
            </form>
        </div>
    </div>
</div>
@endsection