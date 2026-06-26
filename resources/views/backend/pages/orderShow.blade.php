 @extends('backend/layouts/masterLayout')

@section('content')
@php
    use App\Models\Order;
@endphp
<div class="container py-4" style="max-width:900px">

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.orders.index') }}"
           class="btn btn-outline-secondary btn-sm">← ফিরুন</a>
        <h5 class="mb-0">{{ $order->order_number }}</h5>
        <span class="badge bg-{{ $order->status_color }} ms-2">
            {{ $order->status_label }}
        </span>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- বাম --}}
        <div class="col-md-8">

            {{-- Order Items --}}
            <div class="card mb-4">
                <div class="card-header fw-500">Order Items</div>
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th style="width:80px">দাম</th>
                                <th style="width:70px">পরিমাণ</th>
                                <th style="width:90px">মোট</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($item->product_image)
                                                <img src="{{ Storage::url($item->product_image) }}"
                                                     style="width:40px;height:40px;
                                                            object-fit:cover;border-radius:6px">
                                            @endif
                                            <div>
                                                <div>{{ $item->product_name }}</div>
                                                @if($item->variant_label)
                                                    <small class="text-muted badge bg-light text-dark border">
                                                        {{ $item->variant_label }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>৳{{ number_format($item->unit_price) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>৳{{ number_format($item->subtotal) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end">Subtotal</td>
                                <td>৳{{ number_format($order->subtotal) }}</td>
                            </tr>
                            @if($order->shipping_charge > 0)
                                <tr>
                                    <td colspan="3" class="text-end">Shipping</td>
                                    <td>৳{{ number_format($order->shipping_charge) }}</td>
                                </tr>
                            @endif
                            @if($order->discount > 0)
                                <tr>
                                    <td colspan="3" class="text-end text-danger">ছাড়</td>
                                    <td class="text-danger">
                                        -৳{{ number_format($order->discount) }}
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="3" class="text-end fw-bold">সর্বমোট</td>
                                <td class="fw-bold">৳{{ number_format($order->total) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Notes --}}
            @if($order->notes)
                <div class="card mb-4">
                    <div class="card-header fw-500">Customer-এর নোট</div>
                    <div class="card-body">{{ $order->notes }}</div>
                </div>
            @endif

        </div>

        {{-- ডান --}}
        <div class="col-md-4">

            {{-- Status Update --}}
            <div class="card mb-4">
                <div class="card-header fw-500">Status পরিবর্তন</div>
                <div class="card-body">
                    <form method="POST"
                          action="{{ route('admin.orders.status', $order) }}">
                        @csrf
                        <select name="status" class="form-select mb-2">
                            @foreach(Order::STATUS_LABELS as $key => $val)
                                <option value="{{ $key }}"
                                    {{ $order->status === $key ? 'selected' : '' }}>
                                    {{ $val['label'] }}
                                </option>
                            @endforeach
                        </select>
                        <button class="btn btn-primary w-100">আপডেট করুন</button>
                    </form>
                </div>
            </div>

            {{-- Payment Update --}}
            <div class="card mb-4">
                <div class="card-header fw-500">Payment Status</div>
                <div class="card-body">
                    <div class="mb-2">
                        <span class="badge bg-{{ $order->payment_color }}">
                            {{ $order->payment_label }}
                        </span>
                        <small class="text-muted ms-1">
                            {{ $order->payment_method_label }}
                        </small>
                    </div>
                    <form method="POST"
                          action="{{ route('admin.orders.payment', $order) }}">
                        @csrf
                        <select name="payment_status" class="form-select mb-2">
                            @foreach(Order::PAYMENT_LABELS as $key => $val)
                                <option value="{{ $key }}"
                                    {{ $order->payment_status === $key ? 'selected' : '' }}>
                                    {{ $val['label'] }}
                                </option>
                            @endforeach
                        </select>
                        <button class="btn btn-outline-primary w-100">
                            Payment আপডেট
                        </button>
                    </form>
                </div>
            </div>

            {{-- Shipping Info --}}
            <div class="card mb-4">
                <div class="card-header fw-500">Shipping তথ্য</div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td class="text-muted">নাম</td>
                            <td>{{ $order->shipping_name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">ফোন</td>
                            <td>{{ $order->shipping_phone }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">শহর</td>
                            <td>{{ $order->shipping_city }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">ঠিকানা</td>
                            <td>{{ $order->shipping_address }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Customer Info --}}
            <div class="card">
                <div class="card-header fw-500">Customer</div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td class="text-muted">নাম</td>
                            <td>{{ $order->user->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Email</td>
                            <td>{{ $order->user->email }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Order করেছেন</td>
                            <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        @if($order->delivered_at)
                            <tr>
                                <td class="text-muted">পৌঁছেছে</td>
                                <td>{{ $order->delivered_at->format('d M Y') }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

        </div>

        {{-- Order invoice download --}}
        <div class="d-flex justify-content-center">
            <a href="{{ route('admin.orders.invoice', $order) }}"
            class="btn btn-outline-primary mt-2"
            target="_blank">
                📄 Invoice Download
            </a>
        </div>
    </div>
</div>
@endsection