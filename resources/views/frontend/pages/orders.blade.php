
@extends('frontend.layouts.masterLayout')
@section('title', 'আমার Orders')

@section('content')
<style>
.order-card{
    border-radius:14px;
    overflow:hidden;
    border-left: 3px solid var(--primary) !important;
}

.order-card + .order-card{
    margin-top:0.5rem;
}

.order-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(10, 37, 64, .13) !important;
}
.card-header{
    background: linear-gradient(to right, rgba(29, 161, 168, .14), rgba(26, 155, 159, 0.05));
    padding: 0.4rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

/* Body */

.order-card .card-body{
    padding:0.4rem;
    background-color:white;
}


/* Main */

.order-content{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:1rem;
}


/* Product Images */

.product-group{
    display:flex;
    align-items:center;
}

.product-thumb{
    width:42px;
    height:42px;

    border-radius:10px;

    object-fit:cover;

    margin-right:-8px;

    border:2px solid #fff;

    box-shadow:0 2px 8px rgba(0,0,0,.08);
}

.more{
    display:flex;
    align-items:center;
    justify-content:center;

    background:#eef7f7;

    color:var(--secondary);

    font-size:.72rem;
    font-weight:700;
}


/* Right */

.order-actions{
    text-align:right;
}

.order-total{
    font-size:1.15rem;
    font-weight:700;

    color:var(--primary);

    
}

.order-actions .btn-sm{
    padding:.35rem .75rem;
    font-size:.75rem;
}


/* Mobile */

@media (max-width:576px){

    .order-card .card-header{
        padding:.65rem .85rem;
    }

    .order-card .card-body{
        padding:.55rem;
    }

    .product-thumb{
        width:38px;
        height:38px;
    }

    .order-total{
        font-size:1rem;
    }

    .order-actions .btn-sm{
        padding:.3rem .6rem;
        font-size:.72rem;
    }

    /* সবচেয়ে গুরুত্বপূর্ণ অংশ */
    .order-content{
        flex-direction:row;   /* নিচে নামবে না */
        align-items:center;
    }

    .order-actions{
        text-align:right;
        flex-shrink:0;
    }
}

</style>
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