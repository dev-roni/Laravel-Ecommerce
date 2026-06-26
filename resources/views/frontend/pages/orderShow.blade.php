{{-- resources/views/shop/orders/show.blade.php --}}
@extends('frontend.layouts.masterLayout')
@section('title', $order->order_number)

@section('content')
<div class="container py-5" style="max-width:800px">

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('orders.index') }}"
           class="btn btn-outline-secondary btn-sm">← Orders</a>
        <h5 class="mb-0">{{ $order->order_number }}</h5>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Status Timeline --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                @foreach(['pending' => 'অপেক্ষমাণ', 'processing' => 'প্রক্রিয়াধীন',
                          'shipped' => 'পাঠানো', 'delivered' => 'পৌঁছেছে'] as $status => $label)
                    @php
                        $statuses = ['pending', 'processing', 'shipped', 'delivered'];
                        $currentIndex = array_search($order->status, $statuses);
                        $thisIndex    = array_search($status, $statuses);
                        $isDone       = $currentIndex >= $thisIndex && $order->status !== 'cancelled';
                    @endphp
                    <div class="text-center">
                        <div style="width:36px;height:36px;border-radius:50%;
                                    background:{{ $isDone ? '#1DA1A8' : '#e2e8f0' }};
                                    color:{{ $isDone ? '#fff' : '#94a3b8' }};
                                    display:flex;align-items:center;
                                    justify-content:center;margin:0 auto;font-size:14px">
                            {{ $isDone ? '✓' : '' }}
                        </div>
                        <small class="d-block mt-1
                                      {{ $isDone ? 'text-primary fw-500' : 'text-muted' }}">
                            {{ $label }}
                        </small>
                    </div>
                    @if(!$loop->last)
                        <div style="flex:1;height:2px;
                        background:#e2e8f0;
                        margin-bottom:20px;min-width:20px">
                        </div>
                     @endif
                @endforeach
            </div>

            @if($order->status === 'cancelled')
                <div class="alert alert-danger mt-3 mb-0 py-2 text-center">
                    এই order বাতিল করা হয়েছে।
                </div>
            @endif
        </div>
    </div>

    <div class="row g-4">

        {{-- Order Items --}}
        <div class="col-md-7">
            <div class="card mb-4">
                <div class="card-header fw-500">
                    Order Items ({{ $order->items->count() }}টি)
                </div>
                <div class="card-body p-0">
                    @foreach($order->items as $item)
                        <div class="d-flex gap-3 p-3
                                    {{ !$loop->last ? 'border-bottom' : '' }}">
                            @if($item->product_image)
                                <img src="{{ Storage::url($item->product_image) }}"
                                     style="width:60px;height:60px;
                                            object-fit:cover;border-radius:8px">
                            @else
                                <div style="width:60px;height:60px;background:#f1f5f9;
                                            border-radius:8px;display:flex;
                                            align-items:center;justify-content:center;
                                            font-size:24px">
                                    📦
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <div class="fw-500">{{ $item->product_name }}</div>
                                @if($item->variant_label)
                                    <span class="badge bg-light text-dark border small">
                                        {{ $item->variant_label }}
                                    </span>
                                @endif
                                <div class="text-muted small mt-1">
                                    ৳{{ number_format($item->unit_price) }}
                                    × {{ $item->quantity }}
                                </div>
                            </div>
                            <div class="fw-500">
                                ৳{{ number_format($item->subtotal) }}
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="card-footer">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td class="text-muted">Subtotal</td>
                            <td class="text-end">৳{{ number_format($order->subtotal) }}</td>
                        </tr>
                        @if($order->shipping_charge > 0)
                            <tr>
                                <td class="text-muted">Shipping</td>
                                <td class="text-end">
                                    ৳{{ number_format($order->shipping_charge) }}
                                </td>
                            </tr>
                        @endif
                        @if($order->discount > 0)
                            <tr>
                                <td class="text-muted text-danger">ছাড়</td>
                                <td class="text-end text-danger">
                                    -৳{{ number_format($order->discount) }}
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td class="fw-bold">সর্বমোট</td>
                            <td class="text-end fw-bold text-primary">
                                ৳{{ number_format($order->total) }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Payment করা হয়নি এবং COD নয় --}}
            @if($order->payment_status === 'unpaid' && $order->payment_method !== 'cod' && $order->status !== 'cancelled')
            <div class="card mb-3"
                style="border:2px solid var(--accent) !important">
                <div class="card-body text-center py-4">
                <div style="font-size:2rem">💳</div>
                <h6 class="mt-2 mb-1" style="color:var(--primary)">
                    Payment বাকি আছে
                </h6>
                <p class="text-muted small mb-3">
                    আপনার order confirm করতে payment করুন।
                </p>
                <form method="POST"
                        action="{{ route('payment.initiate', $order) }}">
                    @csrf
                    <button class="btn btn-warning fw-600 px-4">
                    💳 এখনই Payment করুন
                    </button>
                </form>
                </div>
            </div>
            @endif
            
            {{-- Cancel button --}}
            @if($order->status === 'pending')
                <form method="POST"
                      action="{{ route('orders.cancel', $order) }}"
                      onsubmit="return confirm('নিশ্চিতভাবে বাতিল করবেন?')">
                    @csrf
                    <button class="btn btn-outline-danger w-100">
                        Order বাতিল করুন
                    </button>
                </form>
            @endif
        </div>

        {{-- Order Info --}}
        <div class="col-md-5">

            {{-- Status --}}
            <div class="card mb-3">
                <div class="card-header fw-500">Order তথ্য</div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td class="text-muted">Order নং</td>
                            <td>{{ $order->order_number }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">তারিখ</td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status</td>
                            <td>
                                <span class="badge bg-{{ $order->status_color }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Payment</td>
                            <td>
                                <span class="badge bg-{{ $order->payment_color }}">
                                    {{ $order->payment_label }}
                                </span>
                                <br>
                                <small class="text-muted">
                                    {{ $order->payment_method_label }}
                                </small>
                            </td>
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

            {{-- Shipping --}}
            <div class="card">
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

        </div>
        {{-- Order invoice download--}}
        <div class="d-flex justify-content-center">
            <a href="{{ route('orders.invoice', $order) }}"
            class="btn btn-outline-primary mt-2"
            target="_blank">
                📄 Invoice Download
            </a>
        </div>

    </div>
</div>
@endsection

