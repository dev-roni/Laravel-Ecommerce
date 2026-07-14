
@extends('backend.layouts.masterLayout')
@section('title', $coupon->exists ? 'Edit Coupon: '.$coupon->code : 'Coupon Create')

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

    @php
        $isEdit = $coupon->exists;
        $route= $isEdit ? route('admin.coupons.update',$coupon->id) : route('admin.coupons.store') ;
    @endphp
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ $route }}">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label class="form-label">Coupon Code *</label>
                    <input type="text" name="code"
                           value="{{ old('code',$coupon->code) }}"
                           class="form-control @error('code') is-invalid @enderror"
                           style="text-transform:uppercase"
                           placeholder="EID2025">
                    @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">ধরন *</label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" id="couponType">
                            <option value="fixed" {{ old('type',$coupon->type) == $coupon->type ? 'selected' : '' }}>নির্দিষ্ট টাকা</option>
                            <option value="percent" {{ old('type',$coupon->type) == $coupon->type ? 'selected' : '' }}>শতাংশ (%)</option>
                        </select>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">মূল্য *</label>
                        <input type="number" name="value" step="0.01"
                               value="{{ old('value',$coupon->value) }}"
                               class="form-control @error('value') is-invalid @enderror">
                        @error('value') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3" id="maxDiscountField" style="display:none">
                    <div class="col-12">
                        <label class="form-label">সর্বোচ্চ ছাড় (Percent-এর জন্য)</label>
                        <input type="number" name="max_discount" step="0.01"
                               value="{{ old('max_discount',$coupon->max_discount) }}"
                               class="form-control @error('max_discount') is-invalid @enderror"
                               placeholder="৫০০">
                        @error('max_discount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">ন্যূনতম Order পরিমাণ</label>
                    <input type="number" name="min_order_amount" step="0.01"
                           value="{{ old('min_order_amount',$coupon->min_order_amount) }}"
                           class="form-control @error('min_order_amount') is-invalid @enderror"
                           placeholder="১০০০">
                    @error('min_order_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">মোট ব্যবহার সীমা</label>
                        <input type="number" name="usage_limit"
                               value="{{ old('usage_limit',$coupon->usage_limit) }}"
                               class="form-control @error('usage_limit') is-invalid @enderror"
                               placeholder="খালি = সীমাহীন">
                        @error('usage_limit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">একজন কতবার *</label>
                        <input type="number" name="per_user_limit"
                               value="{{ old('per_user_limit',$coupon->per_user_limit) }}"
                               class="form-control @error('per_user_limit') is-invalid @enderror">
                        @error('per_user_limit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">শুরুর তারিখ</label>
                        <input type="date" name="starts_at"
                               value="{{ old('starts_at',$coupon->starts_at) }}"
                               class="form-control @error('starts_at') is-invalid @enderror">
                        @error('starts_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">শেষ তারিখ</label>
                        <input type="date" name="expires_at"
                               value="{{ old('expires_at',$coupon->expires_at) }}"
                               class="form-control @error('expires_at') is-invalid @enderror">
                        @error('expires_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
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