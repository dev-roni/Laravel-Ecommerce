 @extends('backend/layouts/masterLayout')


@section('content')

@php
    use App\Models\Order;
@endphp

<div class="container-fluid py-4 px-4">

    <h4 class="mb-4">Order ব্যবস্থাপনা</h4>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        @foreach([
            ['label' => 'অপেক্ষমাণ',   'key' => 'pending',    'color' => 'warning'],
            ['label' => 'প্রক্রিয়াধীন', 'key' => 'processing', 'color' => 'info'],
            ['label' => 'পাঠানো',       'key' => 'shipped',    'color' => 'primary'],
            ['label' => 'আজকের Order',  'key' => 'today',      'color' => 'success'],
        ] as $card)
            <div class="col-md-3">
                <div class="card border-0 shadow-sm border-start border-4
                            border-{{ $card['color'] }}">
                    <div class="card-body">
                        <div class="text-muted small">{{ $card['label'] }}</div>
                        <div class="fs-3 fw-bold">{{ $summary[$card['key']] }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Filter --}}
    <div class="card mb-4">
        <div class="card-body py-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <input type="text" name="search"
                           value="{{ request('search') }}"
                           placeholder="Order নং, নাম বা ফোন"
                           class="form-control">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">সব Status</option>
                        @foreach(Order::STATUS_LABELS as $key => $val)
                            <option value="{{ $key }}"
                                {{ request('status') === $key ? 'selected' : '' }}>
                                {{ $val['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="payment_status" class="form-select">
                        <option value="">সব Payment</option>
                        @foreach(Order::PAYMENT_LABELS as $key => $val)
                            <option value="{{ $key }}"
                                {{ request('payment_status') === $key ? 'selected' : '' }}>
                                {{ $val['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date"
                           value="{{ request('date') }}"
                           class="form-control">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-outline-primary">খুঁজুন</button>
                    <a href="{{ route('admin.orders.index') }}"
                       class="btn btn-outline-secondary">রিসেট</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Order নং</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>মোট</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>তারিখ</th>
                        <th style="width:80px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="fw-500 text-decoration-none">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td>
                                {{ $order->shipping_name }}
                                <br>
                                <small class="text-muted">{{ $order->shipping_phone }}</small>
                            </td>
                            <td>{{ $order->items->count() }}টি</td>
                            <td class="fw-500">৳{{ number_format($order->total) }}</td>
                            <td>
                                <span class="badge bg-{{ $order->payment_color }}">
                                    {{ $order->payment_label }}
                                </span>
                                <br>
                                <small class="text-muted">
                                    {{ $order->payment_method_label }}
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $order->status_color }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td>
                                <small>{{ $order->created_at->format('d M Y') }}</small>
                                <br>
                                <small class="text-muted">
                                    {{ $order->created_at->format('h:i A') }}
                                </small>
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    বিস্তারিত
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                কোনো order নেই।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="card-footer">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection