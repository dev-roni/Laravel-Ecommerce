
@extends('backend.layouts.masterLayout')
@section('title', 'Coupons')

@section('content')
<div class="container-fluid py-4 px-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Coupon ব্যবস্থাপনা</h4>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
            + নতুন Coupon
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Discount</th>
                        <th>Min Order</th>
                        <th>ব্যবহার</th>
                        <th>মেয়াদ</th>
                        <th>Status</th>
                        <th style="width:140px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $coupon)
                        <tr>
                            <td>
                                <code class="fw-bold">{{ $coupon->code }}</code>
                            </td>
                            <td>
                                @if($coupon->type === 'percent')
                                    {{ $coupon->value }}% off
                                    @if($coupon->max_discount)
                                        <br><small class="text-muted">
                                            সর্বোচ্চ ৳{{ number_format($coupon->max_discount) }}
                                        </small>
                                    @endif
                                @else
                                    ৳{{ number_format($coupon->value) }} off
                                @endif
                            </td>
                            <td>
                                {{ $coupon->min_order_amount
                                    ? '৳' . number_format($coupon->min_order_amount)
                                    : '—' }}
                            </td>
                            <td>
                                {{ $coupon->used_count }}
                                @if($coupon->usage_limit)
                                    / {{ $coupon->usage_limit }}
                                @endif
                            </td>
                            <td>
                                @if($coupon->expires_at)
                                    <span class="{{ $coupon->expires_at->isPast() ? 'text-danger' : '' }}">
                                        {{ $coupon->expires_at->format('d M Y') }}
                                    </span>
                                @else
                                    কোনো সীমা নেই
                                @endif
                            </td>
                            <td>
                                <form method="POST"
                                      action="{{ route('admin.coupons.toggle', $coupon) }}">
                                    @csrf
                                    <button class="badge border-0
                                        {{ $coupon->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $coupon->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}"
                                       class="btn btn-sm btn-outline-primary">সম্পাদনা</a>
                                    <form method="POST"
                                          action="{{ route('admin.coupons.destroy', $coupon) }}"
                                          onsubmit="return confirm('মুছবেন?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">মুছুন</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                কোনো coupon নেই।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($coupons->hasPages())
            <div class="card-footer">{{ $coupons->links() }}</div>
        @endif
    </div>
</div>
@endsection