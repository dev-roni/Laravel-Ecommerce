
@extends('frontend.layouts.masterLayout')
@section('title', 'আমার Orders')

@section('content')

<div class="container py-5">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('profile.edit') }}"
           class="btn btn-outline-secondary btn-sm">← Profile</a>
        <h4 class="mb-0">আমার Orders</h4>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($orders->isEmpty())
        <div class="text-center py-5">
            <div style="font-size:60px">📦</div>
            <p class="text-muted mt-3">এখনো কোনো order করেননি।</p>
            <a href="{{ route('shop.index') }}" class="btn btn-primary">
                কেনাকাটা শুরু করুন
            </a>
        </div>
    @else
        <div class="d-flex flex-column gap-3">
            @foreach($orders as $order)
                <div class="card order-card border-0 shadow-sm">
                    <a href="{{ route('orders.show', $order) }}"
                    class="card-header bg-light d-flex justify-content-between align-items-center text-decoration-none text-dark">

                        <div>
                            <span class="fw-500">{{ $order->order_number }}</span>

                            <small class="text-muted ms-2">
                                {{ $order->created_at->format('d M Y') }}
                            </small>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            Status:
                            <span class="badge bg-{{ $order->status_color }}">
                                {{ $order->status_label }}
                            </span>
                        </div>

                    </a>

                    <div class="card-body">

                        <div class="order-content">

                            {{-- Product Preview --}}

                            <div class="product-group">

                                @foreach($order->items->take(4) as $item)
                                

                                    @if($item->product_variant_id && $item->variant?->image)

                                        <img src="{{ Storage::url($item->variant->image) }}"
                                            class="product-thumb"
                                            alt="{{ $item->product_name }}">

                                    @elseif($item->product_image)

                                        <img src="{{ Storage::url($item->product_image) }}"
                                            class="product-thumb"
                                            alt="{{ $item->product_name }}">

                                    @else

                                        <div style="width:42px;height:42px; border-radius:10px;
                                                            background:#f1f5f9;
                                                            display:flex; align-items:center;
                                                            justify-content:center; font-size:20px">
                                                    📦
                                                </div>

                                    @endif

                                @endforeach

                                @if($order->items->count() > 4)

                                    <div class="product-thumb more">
                                        +{{ $order->items->count() - 4 }}
                                    </div>

                                @endif

                            </div>


                            {{-- Total + Actions --}}
                            <div class="order-actions">

                                <div class="order-total">
                                   TOTAL : <span class="text-muted">({{$order->items->count()}} Items)</span>
                                    ৳{{ number_format($order->total) }}
                                </div>

                                <div class="d-flex gap-2 justify-content-end">

                                    <a href="{{ route('orders.show',$order) }}"
                                    class="btn btn-outline-primary btn-sm">

                                        বিস্তারিত

                                    </a>

                                    @if($order->status === 'pending')

                                        <form method="POST"
                                            action="{{ route('orders.cancel',$order) }}"
                                            onsubmit="return confirm('Order বাতিল করবেন?')">

                                            @csrf

                                            <button class="btn btn-outline-danger btn-sm">
                                                বাতিল
                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    @endif

</div>
@endsection