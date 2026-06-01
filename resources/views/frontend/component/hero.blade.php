{{--
  resources/views/partials/hero.blade.php
  Variables: $featured (Collection), $latest (Collection)
--}}

<section id="pbn-hero" style="height:400px;overflow:hidden;border-bottom:1px solid var(--border);position:relative">

<style>
/* ── Carousel track ── */
#pbn-hero .pbn-track{display:flex;height:400px;transition:transform .6s cubic-bezier(.77,0,.18,1)}
#pbn-hero .pbn-slide{min-width:100%;height:100%;position:relative;display:grid;grid-template-columns:1fr 1fr;align-items:center;padding:0}

/* ── Copy panel ── */
#pbn-hero .pbn-copy{position:relative;z-index:2;padding:0 3rem 0 0;
  opacity:0;transform:translateY(14px);transition:opacity .5s .15s,transform .5s .15s}
#pbn-hero .pbn-slide.active .pbn-copy{opacity:1;transform:translateY(0)}

#pbn-hero .pbn-tag{font-size:.65rem;letter-spacing:.16em;text-transform:uppercase;font-weight:500;
  display:flex;align-items:center;gap:.5rem;margin-bottom:.9rem}
#pbn-hero .pbn-tag::before{content:'';width:20px;height:1.5px;background:currentColor;opacity:.7;flex-shrink:0}

#pbn-hero .pbn-title{font-family:'Cormorant Garamond',serif;font-weight:300;
  font-size:clamp(1.8rem,2.8vw,2.9rem);line-height:1.1;margin-bottom:.85rem}
#pbn-hero .pbn-title em{font-style:italic}

#pbn-hero .pbn-desc{font-size:.8rem;line-height:1.72;max-width:34ch;opacity:.72;margin-bottom:1.3rem}

/* ── Visual panel ── */
#pbn-hero .pbn-visual{position:relative;z-index:2;display:flex;align-items:center;justify-content:center;
  opacity:0;transform:scale(.93);transition:opacity .55s .25s,transform .55s .25s}
#pbn-hero .pbn-slide.active .pbn-visual{opacity:1;transform:scale(1)}

/* ── Mini product card ── */
#pbn-hero .pbn-card{background:#fff;border-radius:14px;overflow:hidden;width:175px;
  box-shadow:0 16px 48px rgba(10,37,64,.22)}
#pbn-hero .pbn-card-img{height:125px;display:flex;align-items:center;justify-content:center;
  font-size:3.2rem;position:relative;overflow:hidden}
#pbn-hero .pbn-card-img img{width:100%;height:100%;object-fit:cover}
#pbn-hero .pbn-card-badge{position:absolute;top:.5rem;left:.5rem;font-size:.55rem;
  letter-spacing:.06em;text-transform:uppercase;font-weight:700;padding:.22rem .55rem;border-radius:3px;color:#fff}
#pbn-hero .pbn-card-body{padding:.7rem .85rem .85rem}
#pbn-hero .pbn-card-cat{font-size:.58rem;letter-spacing:.1em;text-transform:uppercase;
  color:var(--secondary);font-weight:500;margin-bottom:.2rem}
#pbn-hero .pbn-card-name{font-family:'Cormorant Garamond',serif;font-size:.9rem;font-weight:600;
  color:var(--primary);line-height:1.2;margin-bottom:.5rem}
#pbn-hero .pbn-card-price{font-size:.85rem;font-weight:700;color:var(--primary)}
#pbn-hero .pbn-card-price-old{font-size:.7rem;color:var(--text-secondary);text-decoration:line-through;margin-left:.3rem}
#pbn-hero .pbn-card-add{width:24px;height:24px;border-radius:50%;border:none;color:#fff;
  display:flex;align-items:center;justify-content:center;font-size:.75rem;cursor:pointer;transition:transform .2s}
#pbn-hero .pbn-card-add:hover{transform:scale(1.15)}

/* ── Float chips ── */
#pbn-hero .pbn-chip{position:absolute;background:#fff;border-radius:30px;padding:.4rem .8rem;
  display:flex;align-items:center;gap:.4rem;font-size:.66rem;font-weight:500;color:var(--primary);
  box-shadow:0 6px 22px rgba(10,37,64,.18);white-space:nowrap;z-index:3}

/* ── Progress bar ── */
#pbn-hero .pbn-progress{position:absolute;bottom:0;left:0;height:2px;
  background:rgba(255,255,255,.55);z-index:10;transition:width .1s linear}

/* ── Mobile ── */
@media(max-width:767px){
  #pbn-hero,.pbn-track{height:280px!important}
  #pbn-hero .pbn-slide{grid-template-columns:1fr}
  #pbn-hero .pbn-visual{display:none}
  #pbn-hero .pbn-copy{padding:0}
}
</style>

{{-- Bootstrap container wraps track --}}
<div class="container-fluid h-100 position-relative">

  <div class="pbn-track" id="pbn-track">

    {{-- ════ SLIDE 1 — Main Collection ════ --}}
    <div class="pbn-slide active" id="pbn-s0">
      {{-- bg --}}
      <div class="position-absolute inset-0"
           style="inset:0;background:linear-gradient(115deg,#0A2540 0%,#0d3060 55%,#1DA1A8 130%)"></div>

      {{-- Copy --}}
      <div class="pbn-copy">
        <div class="pbn-tag" style="color:#1DA1A8">নতুন কালেকশন ২০২৫</div>
        <h1 class="pbn-title" style="color:#fff">
          Crafted for<br>
          <em style="color:#1DA1A8">Modern Living</em>
        </h1>
        <p class="pbn-desc" style="color:rgba(255,255,255,.7)">
          সেরা মানের পণ্য, সেরা দামে। আপনার জীবনধারার জন্য বাছাই করা সংগ্রহ।
        </p>
        <div class="d-flex align-items-center gap-3">
          <a href="{{ route('shop.search') }}"
             class="btn btn-sm d-inline-flex align-items-center gap-2"
             style="background:var(--accent);color:#fff;border-radius:5px;
                    font-size:.72rem;letter-spacing:.08em;text-transform:uppercase;
                    font-weight:500;padding:.58rem 1.3rem">
            <i class="fa-solid fa-bag-shopping"></i> Shop Now
          </a>
          <a href="#categories"
             class="d-inline-flex align-items-center gap-1"
             style="font-size:.72rem;letter-spacing:.08em;text-transform:uppercase;
                    font-weight:500;color:rgba(255,255,255,.6);text-decoration:none">
            Browse <i class="fa-solid fa-arrow-right" style="font-size:.6rem"></i>
          </a>
        </div>
      </div>

      {{-- Visual --}}
      <div class="pbn-visual">
        @php $p1 = $featured->first() ?? null; @endphp
        <div class="pbn-card">
          <div class="pbn-card-img" style="background:linear-gradient(135deg,#0d3060,#1DA1A8)">
            @if($p1?->primaryImage)
              <img src="{{ Storage::url($p1->primaryImage->image) }}" alt="{{ $p1->name }}">
            @else 🛋️ @endif
            <span class="pbn-card-badge"
                  style="background:var(--accent)">
              {{ $p1?->discount_percent ? '−'.$p1->discount_percent.'%' : 'New' }}
            </span>
          </div>
          <div class="pbn-card-body">
            <div class="pbn-card-cat">{{ $p1?->category?->name ?? 'Featured' }}</div>
            <div class="pbn-card-name">{{ $p1 ? Str::limit($p1->name,22) : 'Nordic Lounge Chair' }}</div>
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <span class="pbn-card-price">৳{{ $p1 ? number_format($p1->current_price) : '8,500' }}</span>
                @if($p1?->sale_price)
                  <span class="pbn-card-price-old">৳{{ number_format($p1->base_price) }}</span>
                @endif
              </div>
              <button class="pbn-card-add"
                      style="background:var(--primary)"
                      @if($p1) onclick="addToCart({{ $p1->id }})" @endif>
                <i class="fa-solid fa-plus"></i>
              </button>
            </div>
          </div>
        </div>
        {{-- chips --}}
        <div class="pbn-chip" style="top:1rem;right:.5rem">
          <span style="color:var(--warning);font-size:.78rem">★★★★★</span>
          <strong style="font-size:.68rem">4.9</strong>
        </div>
        <div class="pbn-chip" style="bottom:1rem;left:.2rem">
          <i class="fa-solid fa-truck" style="color:var(--secondary);font-size:.72rem"></i>
          Free shipping ৳1,500+
        </div>
      </div>
    </div>{{-- /slide 1 --}}

    {{-- ════ SLIDE 2 — Summer Sale ════ --}}
    <div class="pbn-slide" id="pbn-s1">
      <div class="position-absolute"
           style="inset:0;background:linear-gradient(115deg,#c95c0a 0%,#FF7A18 50%,#0A2540 130%)"></div>

      <div class="pbn-copy">
        <div class="pbn-tag" style="color:var(--warning)">Summer Sale ২০২৫</div>
        <h1 class="pbn-title" style="color:#fff">
          Up to 40% Off<br>
          <em style="color:var(--warning)">Sitewide Deals</em>
        </h1>
        <p class="pbn-desc" style="color:rgba(255,255,255,.7)">
          গ্রীষ্মের সেরা অফার মিস করবেন না। সীমিত সময়ের জন্য বিশেষ ছাড়।
        </p>
        <div class="d-flex align-items-center gap-3">
          <a href="{{ route('shop.search') }}"
             class="btn btn-sm"
             style="background:#fff;color:var(--primary);border-radius:5px;
                    font-size:.72rem;letter-spacing:.08em;text-transform:uppercase;
                    font-weight:500;padding:.58rem 1.3rem">
            Claim Offer →
          </a>
          <a href="{{ route('shop.search') }}"
             class="d-inline-flex align-items-center gap-1"
             style="font-size:.72rem;letter-spacing:.08em;text-transform:uppercase;
                    font-weight:500;color:rgba(255,255,255,.6);text-decoration:none">
            All Deals <i class="fa-solid fa-arrow-right" style="font-size:.6rem"></i>
          </a>
        </div>
      </div>

      <div class="pbn-visual">
        <div class="pbn-card">
          <div class="pbn-card-img" style="background:linear-gradient(135deg,#FF7A18,#0A2540)">
            🪴
            <span class="pbn-card-badge" style="background:var(--warning);color:#0A2540">−40%</span>
          </div>
          <div class="pbn-card-body">
            <div class="pbn-card-cat">Decor</div>
            <div class="pbn-card-name">Premium Plant Set</div>
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <span class="pbn-card-price">৳3,200</span>
                <span class="pbn-card-price-old">৳5,400</span>
              </div>
              <button class="pbn-card-add" style="background:var(--accent)">
                <i class="fa-solid fa-plus"></i>
              </button>
            </div>
          </div>
        </div>
        <div class="pbn-chip" style="top:1rem;right:.5rem">
          <i class="fa-solid fa-fire" style="color:var(--accent);font-size:.76rem"></i>
          <strong style="font-size:.68rem">Hot Deal</strong>
        </div>
        <div class="pbn-chip" style="bottom:1rem;left:.2rem">
          <i class="fa-solid fa-clock" style="color:var(--accent);font-size:.72rem"></i>
          Limited time offer
        </div>
      </div>
    </div>{{-- /slide 2 --}}

    {{-- ════ SLIDE 3 — New Arrivals ════ --}}
    <div class="pbn-slide" id="pbn-s2">
      <div class="position-absolute"
           style="inset:0;background:linear-gradient(115deg,#085041 0%,#0F6E56 50%,#1DA1A8 130%)"></div>

      <div class="pbn-copy">
        <div class="pbn-tag" style="color:#9FE1CB">নতুন আগমন</div>
        <h1 class="pbn-title" style="color:#fff">
          Fresh Arrivals<br>
          <em style="color:#9FE1CB">Just In Store</em>
        </h1>
        <p class="pbn-desc" style="color:rgba(255,255,255,.7)">
          এই সপ্তাহের নতুন পণ্য দেখুন। প্রতি সপ্তাহে নতুন আইটেম যোগ হচ্ছে।
        </p>
        <div class="d-flex align-items-center gap-3">
          <a href="{{ route('shop.search') }}"
             class="btn btn-sm d-inline-flex align-items-center gap-2"
             style="background:#22C55E;color:#fff;border-radius:5px;
                    font-size:.72rem;letter-spacing:.08em;text-transform:uppercase;
                    font-weight:500;padding:.58rem 1.3rem">
            <i class="fa-solid fa-star"></i> New Arrivals
          </a>
          <a href="{{ route('shop.search') }}"
             class="d-inline-flex align-items-center gap-1"
             style="font-size:.72rem;letter-spacing:.08em;text-transform:uppercase;
                    font-weight:500;color:rgba(255,255,255,.6);text-decoration:none">
            Catalogue <i class="fa-solid fa-arrow-right" style="font-size:.6rem"></i>
          </a>
        </div>
      </div>

      <div class="pbn-visual">
        @php $p3 = $latest->first() ?? null; @endphp
        <div class="pbn-card">
          <div class="pbn-card-img" style="background:linear-gradient(135deg,#085041,#1DA1A8)">
            @if($p3?->primaryImage)
              <img src="{{ Storage::url($p3->primaryImage->image) }}" alt="{{ $p3->name }}">
            @else 🪞 @endif
            <span class="pbn-card-badge" style="background:#22C55E">New</span>
          </div>
          <div class="pbn-card-body">
            <div class="pbn-card-cat">{{ $p3?->category?->name ?? 'Bedroom' }}</div>
            <div class="pbn-card-name">{{ $p3 ? Str::limit($p3->name,22) : 'Arch Floor Mirror' }}</div>
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <span class="pbn-card-price">৳{{ $p3 ? number_format($p3->current_price) : '6,800' }}</span>
              </div>
              <button class="pbn-card-add"
                      style="background:#0F6E56"
                      @if($p3) onclick="addToCart({{ $p3->id }})" @endif>
                <i class="fa-solid fa-plus"></i>
              </button>
            </div>
          </div>
        </div>
        <div class="pbn-chip" style="top:1rem;right:.5rem">
          <span style="color:var(--warning);font-size:.78rem">★★★★★</span>
          <strong style="font-size:.68rem">5.0</strong>
        </div>
        <div class="pbn-chip" style="bottom:1rem;left:.2rem">
          <i class="fa-solid fa-star" style="color:#22C55E;font-size:.72rem"></i>
          Just arrived this week
        </div>
      </div>
    </div>{{-- /slide 3 --}}

  </div>{{-- /track --}}

  {{-- ── Prev / Next ── --}}
  <button id="pbn-prev" aria-label="Previous"
          class="btn position-absolute top-50 translate-middle-y d-flex align-items-center justify-content-center rounded-circle p-0"
          style="left:12px;width:32px;height:32px;background:rgba(255,255,255,.9);
                 color:var(--primary);box-shadow:0 2px 12px rgba(10,37,64,.14);z-index:10">
    <i class="fa-solid fa-chevron-left" style="font-size:.8rem"></i>
  </button>

  <button id="pbn-next" aria-label="Next"
          class="btn position-absolute top-50 translate-middle-y d-flex align-items-center justify-content-center rounded-circle p-0"
          style="right:12px;width:32px;height:32px;background:rgba(255,255,255,.9);
                 color:var(--primary);box-shadow:0 2px 12px rgba(10,37,64,.14);z-index:10">
    <i class="fa-solid fa-chevron-right" style="font-size:.8rem"></i>
  </button>

  {{-- ── Dots ── --}}
  <div class="position-absolute bottom-0 start-50 translate-middle-x d-flex gap-1 pb-2"
       style="z-index:10" id="pbn-dots">
    <button class="pbn-dot active border-0 p-0" data-i="0"
            style="width:5px;height:5px;border-radius:50%;background:rgba(255,255,255,.9);
                   transition:all .3s;cursor:pointer"></button>
    <button class="pbn-dot border-0 p-0" data-i="1"
            style="width:5px;height:5px;border-radius:50%;background:rgba(255,255,255,.35);
                   transition:all .3s;cursor:pointer"></button>
    <button class="pbn-dot border-0 p-0" data-i="2"
            style="width:5px;height:5px;border-radius:50%;background:rgba(255,255,255,.35);
                   transition:all .3s;cursor:pointer"></button>
  </div>

  {{-- ── Progress ── --}}
  <div class="pbn-progress" id="pbn-progress"></div>

</div>{{-- /container-xl --}}
</section>

@push('scripts')
<script>
(function(){
  const track = document.getElementById('pbn-track');
  const dots  = document.querySelectorAll('.pbn-dot');
  const prog  = document.getElementById('pbn-progress');
  const N = 3, TICK = 50, DUR = 5000;
  let cur = 0, timer, val = 0;

  function goTo(n){
    document.getElementById('pbn-s'+cur).classList.remove('active');
    cur = ((n%N)+N)%N;
    track.style.transform = 'translateX(-'+(cur*100)+'%)';
    dots.forEach((d,i)=>{
      d.style.width      = i===cur ? '18px' : '5px';
      d.style.borderRadius = i===cur ? '3px' : '50%';
      d.style.background = i===cur ? 'rgba(255,255,255,.9)' : 'rgba(255,255,255,.35)';
    });
    document.getElementById('pbn-s'+cur).classList.add('active');
    start();
  }

  function start(){
    clearInterval(timer); val=0; prog.style.width='0%';
    timer = setInterval(()=>{
      val += (TICK/DUR)*100;
      prog.style.width = Math.min(val,100)+'%';
      if(val>=100) goTo(cur+1);
    }, TICK);
  }

  document.getElementById('pbn-prev').onclick = ()=>goTo(cur-1);
  document.getElementById('pbn-next').onclick = ()=>goTo(cur+1);
  dots.forEach(d=>d.addEventListener('click',()=>goTo(+d.dataset.i)));

  const sec = document.getElementById('pbn-hero');
  sec.addEventListener('mouseenter',()=>clearInterval(timer));
  sec.addEventListener('mouseleave', start);

  let tx=0;
  sec.addEventListener('touchstart',e=>tx=e.touches[0].clientX,{passive:true});
  sec.addEventListener('touchend',e=>{
    const d=tx-e.changedTouches[0].clientX;
    if(Math.abs(d)>45) goTo(d>0?cur+1:cur-1);
  },{passive:true});

  start();
})();
</script>
@endpush