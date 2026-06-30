
@extends('frontend.layouts.masterLayout')
@section('title', 'আমার Wishlist')

@section('content')
<div class="container py-5">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0" style="color:var(--primary)">আমার Wishlist</h4>
            <p class="text-muted small mb-0">{{ $items->count() }}টি পণ্য</p>
        </div>
        @if($items->count())
            <form method="POST" action="{{ route('wishlist.all-to-cart') }}"
                  onsubmit="return confirm('সব পণ্য Cart-এ যোগ করবেন?')">
                @csrf
                <button class="btn btn-primary btn-sm px-4">
                    <i class="bi bi-bag-plus me-1"></i>
                    সব Cart-এ যোগ করুন
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($items->isEmpty())
        <div class="text-center py-5">
            <div style="font-size:5rem;opacity:.2">🤍</div>
            <h5 class="mt-3 mb-2" style="color:var(--primary)">Wishlist খালি আছে</h5>
            <p class="text-muted mb-4">পছন্দের পণ্য heart icon-এ click করে save করুন।</p>
            <a href="{{ route('shop.index') }}" class="btn btn-primary px-5">
                কেনাকাটা শুরু করুন
            </a>
        </div>
    @else
        <div class="row g-4">
            @foreach($items as $item)
                @php $product = $item->product; @endphp
                <div class="col-xl-3 col-md-6" id="wish-{{ $item->id }}">
                    <div class="card border-0 shadow-sm h-100"
                         style="border-radius:12px;overflow:hidden;transition:transform .2s,box-shadow .2s"
                         onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 32px rgba(10,37,64,.12)'"
                         onmouseout="this.style.transform='';this.style.boxShadow=''">

                        {{-- Image --}}
                        <div style="position:relative;overflow:hidden;aspect-ratio:1">
                            <a href="{{ route('shop.product', $product->slug) }}">
                                @if($product->primaryImage)
                                    <img src="{{ Storage::url($product->primaryImage->image) }}"
                                         alt="{{ $product->name }}"
                                         style="width:100%;height:100%;object-fit:cover;
                                                transition:transform .5s ease">
                                @else
                                    <div style="width:100%;height:100%;background:var(--background);
                                                display:flex;align-items:center;justify-content:center;
                                                font-size:4rem">
                                        📦
                                    </div>
                                @endif
                            </a>

                            {{-- Remove button --}}
                            <form method="POST"
                                  action="{{ route('wishlist.remove', $item) }}"
                                  style="position:absolute;top:.6rem;right:.6rem">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        title="Wishlist থেকে সরান"
                                        style="width:32px;height:32px;border-radius:50%;
                                               background:rgba(255,255,255,.92);
                                               backdrop-filter:blur(6px);
                                               border:1px solid var(--border);
                                               color:var(--error);font-size:.85rem;
                                               display:flex;align-items:center;justify-content:center;
                                               cursor:pointer;transition:all .2s"
                                        onmouseover="this.style.background='var(--error)';this.style.color='#fff'"
                                        onmouseout="this.style.background='rgba(255,255,255,.92)';this.style.color='var(--error)'">
                                    ✕
                                </button>
                            </form>

                            {{-- Stock badge --}}
                            @if($product->total_stock <= 0)
                                <span style="position:absolute;top:.6rem;left:.6rem;
                                             background:var(--error);color:#fff;
                                             font-size:.62rem;font-weight:700;
                                             padding:.2rem .55rem;border-radius:4px">
                                    Stock নেই
                                </span>
                            @elseif($product->total_stock <= 5)
                                <span style="position:absolute;top:.6rem;left:.6rem;
                                             background:var(--accent);color:#fff;
                                             font-size:.62rem;font-weight:700;
                                             padding:.2rem .55rem;border-radius:4px">
                                    মাত্র {{ $product->total_stock }}টি
                                </span>
                            @endif
                        </div>

                        {{-- Body --}}
                        <div class="p-3 d-flex flex-column" style="flex:1">
                            <div style="font-size:.65rem;letter-spacing:.1em;text-transform:uppercase;
                                        color:var(--secondary);font-weight:600;margin-bottom:.3rem">
                                {{ $product->category->name ?? '' }}
                            </div>

                            <a href="{{ route('shop.product', $product->slug) }}"
                               style="font-size:.95rem;font-weight:600;color:var(--primary);
                                      text-decoration:none;line-height:1.35;
                                      display:-webkit-box;-webkit-line-clamp:2;
                                      -webkit-box-orient:vertical;overflow:hidden;
                                      margin-bottom:.5rem;flex:1">
                                {{ $product->name }}
                            </a>

                            {{-- Price --}}
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span style="font-size:1.05rem;font-weight:700;color:var(--primary)">
                                    ৳{{ number_format($product->current_price) }}
                                </span>
                                @if($product->sale_price)
                                    <span style="font-size:.82rem;color:var(--text-secondary);
                                                 text-decoration:line-through">
                                        ৳{{ number_format($product->base_price) }}
                                    </span>
                                    <span style="font-size:.65rem;font-weight:700;
                                                 background:rgba(239,68,68,.1);color:var(--error);
                                                 padding:.15rem .5rem;border-radius:4px">
                                        -{{ $product->discount_percent }}%
                                    </span>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div class="d-flex gap-2">
                                @if($product->has_variants)
                                    <a href="{{ route('shop.product', $product->slug) }}"
                                       class="btn btn-primary btn-sm flex-grow-1">
                                        বেছে নিন
                                    </a>
                                @else
                                    <form method="POST"
                                          action="{{ route('wishlist.to-cart', $item) }}"
                                          class="flex-grow-1">
                                        @csrf
                                        <button class="btn btn-primary btn-sm w-100"
                                                {{ $product->total_stock <= 0 ? 'disabled' : '' }}>
                                            <i class="bi bi-bag-plus me-1"></i>
                                            Cart-এ যোগ
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection