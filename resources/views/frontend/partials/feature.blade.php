{{-- ══════════════════════════════════════
     FEATURES STRIP
══════════════════════════════════════ --}}
<div class="features-strip">
  <div class="container-xl">
    <div class="row g-0">
      @foreach([
        ['icon'=>'fa-solid fa-truck',         'title'=>'Free Delivery',  'desc'=>'On all orders over ৳1,500 across Bangladesh'],
        ['icon'=>'fa-solid fa-rotate-left',   'title'=>'Easy Returns',   'desc'=>'30-day hassle-free return policy'],
        ['icon'=>'fa-solid fa-shield-halved', 'title'=>'Secure Payment', 'desc'=>'100% protected transactions'],
        ['icon'=>'fa-solid fa-headset',       'title'=>'24/7 Support',   'desc'=>'Expert help whenever you need it'],
      ] as $i => $f)
        <div class="col-6 col-md-3 {{ $i < 3 ? 'feat-col-border' : '' }} py-4 px-4">
          <div class="d-flex align-items-start gap-3">
            <div class="feat-icon-box"><i class="{{ $f['icon'] }}"></i></div>
            <div>
              <div class="feat-title">{{ $f['title'] }}</div>
              <div class="feat-desc mt-1">{{ $f['desc'] }}</div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>