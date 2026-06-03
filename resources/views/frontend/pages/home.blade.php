
@extends('frontend.layouts.masterLayout')

@section('title', 'পবনবাহিকা — Modern Living')



@section('content')

{{-- HERO --}}

@include('frontend.component.hero', ['featured' => $featured,'latest'   => $latest,])

{{-- ████████████████████████████████
     CATEGORIES
████████████████████████████████ --}}
<section class="py-5" id="categories">
  <div class="container-xl">

    <div class="d-flex align-items-end justify-content-between mb-4">
      <div>
        <div class="sec-eyebrow mb-1">Browse By</div>
        <h2 class="sec-title mb-0">Shop Categories</h2>
      </div>
      <a href="{{ route('shop.search') }}" class="link-viewall">
        All Categories <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>

    @if($categories->count())
      <div class="row g-3">
        {{-- Left big card --}}
        <div class="col-md-4">
          @php $firstCat = $categories->first(); @endphp
          <a href="{{ route('shop.category', $firstCat->slug) }}"
             class="cat-card"
             style="height:100%;min-height:390px">
            @if($firstCat->image)
              <img src="{{ Storage::url($firstCat->image) }}"
                   class="cat-card-bg"
                   style="width:100%;height:100%;object-fit:cover">
            @else
              <div class="cat-card-bg bg-g1"></div>
            @endif
            <div class="cat-card-overlay"></div>
            <div class="cat-icon-wrap">📁</div>
            <div class="cat-info-wrap">
              <div class="cat-title">{{ $firstCat->name }}</div>
              <div class="cat-cnt">{{ $firstCat->products->count() }} products</div>
            </div>
          </a>
        </div>

        {{-- Right 2x2 grid --}}
        <div class="col-md-8">
          <div class="row g-3">
            @php
              $gradients = ['bg-g2','bg-g3','bg-g4','bg-g5'];
            @endphp
            @foreach($categories->skip(1)->take(4) as $i => $cat)
              <div class="col-6">
                <a href="{{ route('shop.category', $cat->slug) }}"
                   class="cat-card"
                   style="height:187px">
                  @if($cat->image)
                    <img src="{{ Storage::url($cat->image) }}"
                         class="cat-card-bg"
                         style="width:100%;height:100%;object-fit:cover">
                  @else
                    <div class="cat-card-bg {{ $gradients[$i % 4] }}"></div>
                  @endif
                  <div class="cat-card-overlay"></div>
                  <div class="cat-icon-wrap">📁</div>
                  <div class="cat-info-wrap">
                    <div class="cat-title">{{ $cat->name }}</div>
                    <div class="cat-cnt">{{ $cat->products->count() }} products</div>
                  </div>
                </a>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    @endif

  </div>
</section>

{{-- ████████████████████████████████
     FEATURED PRODUCTS
████████████████████████████████ --}}
@if($featured->count())
  <section class="py-5" style="padding-top:0!important">
    <div class="container-xl">

      <div class="d-flex align-items-end justify-content-between mb-4">
        <div>
          <div class="sec-eyebrow mb-1">Handpicked For You</div>
          <h2 class="sec-title mb-0">Trending Now</h2>
        </div>
        <a href="{{ route('shop.search') }}" class="link-viewall">
          See All Products <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>

      <div class="row g-4">
        @foreach($featured->take(4) as $i => $product)
          <div class="col-xl-3 col-md-6 anim-up d{{ $i+1 }}">
            @include('frontend.component.product-card', compact('product'))
          </div>
        @endforeach
      </div>

    </div>
  </section>
@endif

{{-- ████████████████████████████████
     PROMO BANNER
████████████████████████████████ --}}
<section class="py-5">
  <div class="container-xl">
    <div class="promo-band p-lg-5 p-4">
      <div class="row align-items-center g-4">
        <div class="col-lg-8" style="position:relative;z-index:1">
          <div class="promo-eyebrow mb-2">✦ Limited Time Offer</div>
          <h2 class="promo-h2 mb-3">
            Summer Sale is
            <em style="font-style:italic;color:var(--accent)">Here</em>
          </h2>
          <p class="promo-desc mb-4">
            Refresh your space with curated pieces from our summer collection.
            Minimal designs, maximum comfort.
          </p>
          <a href="{{ route('shop.search') }}" class="btn-accent-solid">
            Claim Your Discount
          </a>
        </div>
        <div class="col-lg-4">
          <div class="discount-box">
            <div class="discount-big">30%</div>
            <div class="discount-lbl">Off Sitewide</div>
            <div class="coupon-chip">SUMMER30</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ████████████████████████████████
     LATEST PRODUCTS
████████████████████████████████ --}}
@if($latest->count())
  <section class="py-5" style="padding-top:0!important">
    <div class="container-xl">

      <div class="d-flex align-items-end justify-content-between mb-4">
        <div>
          <div class="sec-eyebrow mb-1">Just Arrived</div>
          <h2 class="sec-title mb-0">New Arrivals</h2>
        </div>
        <a href="{{ route('shop.search') }}" class="link-viewall">
          See All <i class="bi bi-arrow-right"></i>
        </a>
      </div>

      <div class="row g-4">
        @foreach($latest->take(8) as $i => $product)
          <div class="col-xl-3 col-md-6 anim-up d{{ ($i % 4) + 1 }}">
            @include('frontend.component.product-card', compact('product'))
          </div>
        @endforeach
      </div>

    </div>
  </section>
@endif

@endsection


