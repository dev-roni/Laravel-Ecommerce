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
            <div class="card mb-4">
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

            {{-- Google connect --}}
            <div class="card mb-4">
                <div class="card-header fw-500">Connected Accounts</div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            {{-- Google Icon --}}
                            <svg width="22" height="22" viewBox="0 0 48 48">
                                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                            </svg>
                            <div>
                                <div style="font-size:.88rem;font-weight:600">Google</div>
                                <div style="font-size:.75rem;color:var(--text-secondary)">
                                    {{ auth()->user()->google_id ? 'Connected' : 'Not connected' }}
                                </div>
                            </div>
                        </div>

                        @if(auth()->user()->google_id)
                            <span class="badge bg-success">Connected ✓</span>
                        @else
                            <a href="{{ route('auth.google') }}"
                            class="btn btn-outline-secondary btn-sm">
                                Connect
                            </a>
                        @endif
                    </div>
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