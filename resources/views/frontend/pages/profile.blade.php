{{-- resources/views/shop/profile.blade.php --}}
@extends('frontend.layouts.masterLayout')
@section('title', 'আমার Profile')

@section('content')
<div class="container py-5" style="max-width:800px">

    <h4 class="mb-4">আমার Account</h4>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-4">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fs-3 fw-bold text-primary">{{ $totalOrders }}</div>
                <div class="text-muted small">মোট Order</div>
            </div>
        </div>
        <div class="col-4">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fs-3 fw-bold text-success">
                    ৳{{ number_format($totalSpent) }}
                </div>
                <div class="text-muted small">মোট খরচ</div>
            </div>
        </div>
        <div class="col-4">
            <div class="card border-0 shadow-sm text-center p-3">
                <a href="{{ route('orders.index') }}"
                   class="text-decoration-none">
                    <div class="fs-3 fw-bold text-warning">
                        {{ auth()->user()->orders()->where('status','pending')->count() }}
                    </div>
                    <div class="text-muted small">Pending Orders</div>
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- বাম: Profile Edit --}}
        <div class="col-md-6">

            {{-- Profile Info --}}
            <div class="card mb-4">
                <div class="card-header fw-500">ব্যক্তিগত তথ্য</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf @method('PATCH')

                        <div class="mb-3">
                            <label class="form-label">নাম *</label>
                            <input type="text" name="name"
                                   value="{{ old('name', $user->name) }}"
                                   class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" value="{{ $user->email }}"
                                   class="form-control bg-light" disabled>
                            <small class="text-muted">Email পরিবর্তন করা যাবে না।</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ফোন</label>
                            <input type="text" name="phone"
                                   value="{{ old('phone', $user->phone) }}"
                                   class="form-control"
                                   placeholder="01XXXXXXXXX">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ঠিকানা</label>
                            <textarea name="address" rows="3"
                                      class="form-control"
                                      placeholder="বাসা, রাস্তা, এলাকা, শহর">{{ old('address', $user->address) }}</textarea>
                        </div>

                        <button class="btn btn-primary w-100">
                            তথ্য আপডেট করুন
                        </button>
                    </form>
                </div>
            </div>

            {{-- Password Change --}}
            <div class="card">
                <div class="card-header fw-500">পাসওয়ার্ড পরিবর্তন</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.password') }}">
                        @csrf @method('PATCH')

                        <div class="mb-3">
                            <label class="form-label">বর্তমান পাসওয়ার্ড</label>
                            <input type="password" name="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">নতুন পাসওয়ার্ড</label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">পাসওয়ার্ড নিশ্চিত করুন</label>
                            <input type="password" name="password_confirmation"
                                   class="form-control">
                        </div>

                        <button class="btn btn-outline-primary w-100">
                            পাসওয়ার্ড পরিবর্তন করুন
                        </button>
                    </form>
                </div>
            </div>

        </div>

        {{-- ডান: Recent Orders --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-500">সাম্প্রতিক Orders</span>
                    <a href="{{ route('orders.index') }}"
                       class="btn btn-sm btn-outline-primary">
                        সব দেখুন
                    </a>
                </div>
                <div class="card-body p-0">
                    @forelse($recentOrders as $order)
                        <a href="{{ route('orders.show', $order) }}"
                           class="d-flex justify-content-between align-items-center
                                  p-3 text-decoration-none text-dark
                                  {{ !$loop->last ? 'border-bottom' : '' }}
                                  hover-bg">
                            <div>
                                <div class="fw-500 small">
                                    {{ $order->order_number }}
                                </div>
                                <div class="text-muted" style="font-size:12px">
                                    {{ $order->created_at->format('d M Y') }}
                                    · {{ $order->items->count() }}টি item
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-500 text-primary small">
                                    ৳{{ number_format($order->total) }}
                                </div>
                                <span class="badge bg-{{ $order->status_color }}"
                                      style="font-size:10px">
                                    {{ $order->status_label }}
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <p class="mb-2">এখনো কোনো order নেই।</p>
                            <a href="{{ route('shop.index') }}"
                               class="btn btn-primary btn-sm">
                                কেনাকাটা শুরু করুন
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection