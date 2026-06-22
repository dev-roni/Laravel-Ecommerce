{{-- resources/views/admin/users/show.blade.php --}}
@extends('backend.layouts.masterLayout')
@section('title', $user->name)

@section('content')
<div class="container py-4" style="max-width:900px">

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">← ফিরুন</a>
        <h5 class="mb-0">{{ $user->name }}</h5>
        @if($user->is_banned)
            <span class="badge bg-danger">ব্যানড</span>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        @foreach([
            ['label'=>'মোট Order','value'=>$stats['total_orders'],'color'=>'primary'],
            ['label'=>'মোট খরচ','value'=>'৳'.number_format($stats['total_spent']),'color'=>'success'],
            ['label'=>'Pending','value'=>$stats['pending_orders'],'color'=>'warning'],
            ['label'=>'বাতিল','value'=>$stats['cancelled'],'color'=>'danger'],
        ] as $s)
            <div class="col-md-3">
                <div class="card border-0 shadow-sm border-start border-4 border-{{ $s['color'] }}">
                    <div class="card-body">
                        <div class="text-muted small">{{ $s['label'] }}</div>
                        <div class="fs-4 fw-bold">{{ $s['value'] }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header fw-500">তথ্য</div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr><td class="text-muted">Email</td><td>{{ $user->email }}</td></tr>
                        <tr><td class="text-muted">ফোন</td><td>{{ $user->phone ?? '—' }}</td></tr>
                        <tr><td class="text-muted">ঠিকানা</td><td>{{ $user->address ?? '—' }}</td></tr>
                        <tr><td class="text-muted">যোগদান</td><td>{{ $user->created_at->format('d M Y') }}</td></tr>
                    </table>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.users.ban', $user) }}"
                  onsubmit="return confirm('নিশ্চিত?')">
                @csrf
                <button class="btn {{ $user->is_banned ? 'btn-outline-success' : 'btn-outline-danger' }} w-100">
                    {{ $user->is_banned ? 'Unban করুন' : 'Ban করুন' }}
                </button>
            </form>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header fw-500">Order History</div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr><th>Order</th><th>মোট</th><th>Status</th><th>তারিখ</th></tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}">
                                            {{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>৳{{ number_format($order->total) }}</td>
                                    <td><span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span></td>
                                    <td><small>{{ $order->created_at->format('d M Y') }}</small></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($orders->hasPages())
                    <div class="card-footer">{{ $orders->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
