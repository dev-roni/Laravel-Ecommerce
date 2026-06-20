
@extends('backend.layouts.masterLayout')
@section('title', 'নতুন Coupon')

@section('content')
<div class="container py-4" style="max-width:600px">

    <h4 class="mb-4">নতুন Coupon তৈরি করুন</h4>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.coupons.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Coupon Code *</label>
                    <input type="text" name="code"
                           value="{{ old('code') }}"
                           class="form-control"
                           style="text-transform:uppercase"
                           placeholder="EID2025">
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">ধরন *</label>
                        <select name="type" class="form-select" id="couponType">
                            <option value="fixed">নির্দিষ্ট টাকা</option>
                            <option value="percent">শতাংশ (%)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">মূল্য *</label>
                        <input type="number" name="value" step="0.01"
                               value="{{ old('value') }}"
                               class="form-control">
                    </div>
                </div>

                <div class="row g-3 mb-3" id="maxDiscountField" style="display:none">
                    <div class="col-12">
                        <label class="form-label">সর্বোচ্চ ছাড় (Percent-এর জন্য)</label>
                        <input type="number" name="max_discount" step="0.01"
                               value="{{ old('max_discount') }}"
                               class="form-control"
                               placeholder="৫০০">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">ন্যূনতম Order পরিমাণ</label>
                    <input type="number" name="min_order_amount" step="0.01"
                           value="{{ old('min_order_amount') }}"
                           class="form-control"
                           placeholder="১০০০">
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">মোট ব্যবহার সীমা</label>
                        <input type="number" name="usage_limit"
                               value="{{ old('usage_limit') }}"
                               class="form-control"
                               placeholder="খালি = সীমাহীন">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">একজন কতবার *</label>
                        <input type="number" name="per_user_limit"
                               value="{{ old('per_user_limit', 1) }}"
                               class="form-control">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">শুরুর তারিখ</label>
                        <input type="date" name="starts_at"
                               value="{{ old('starts_at') }}"
                               class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">শেষ তারিখ</label>
                        <input type="date" name="expires_at"
                               value="{{ old('expires_at') }}"
                               class="form-control">
                    </div>
                </div>

                <div class="form-check form-switch mb-4">
                    <input type="hidden" name="is_active" value="0">
                    <input class="form-check-input" type="checkbox"
                           name="is_active" value="1" checked>
                    <label class="form-check-label">সক্রিয় রাখুন</label>
                </div>

                <button class="btn btn-primary w-100">Coupon তৈরি করুন</button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('couponType').addEventListener('change', function() {
    document.getElementById('maxDiscountField').style.display =
        this.value === 'percent' ? 'block' : 'none';
});
</script>
@endsection