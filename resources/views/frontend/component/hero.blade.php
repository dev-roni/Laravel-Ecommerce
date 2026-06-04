
<section id="hero-section" class="position-relative">

<style>
  /* ── Hero Container ── */

#heroCarousel{
    touch-action: pan-y;
}

#hero-section,
#hero-section .carousel,
#hero-section .carousel-inner,
#hero-section .carousel-item{
    min-height:400px;
}

.carousel-item{
    touch-action: pan-y;
}
#hero-section {
  background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 50%, var(--accent) 100%);
  overflow: hidden;
}


#hero-section .carousel {
  border-radius: 0;
}

#hero-section .carousel-inner {
  height: 100%;
}


/* ── Carousel Content ── */
.hero-content {
  height: 100%;
  display: flex;
  align-items: center;
  position: relative;
  z-index: 2;
  padding-top: 0;
  padding-bottom: 0;
}

.hero-text {
  padding-right: 3rem;
}

.hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.4rem 1rem;
  background: rgba(255,255,255,0.15);
  backdrop-filter: blur(10px);
  border-radius: 50px;
  font-size: 0.75rem;
  font-weight: 600;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: rgba(255,255,255,0.95);
  margin-bottom: 1.2rem;
  border: 1px solid rgba(255,255,255,0.2);
}

.hero-badge i {
  font-size: 0.8rem;
}

.hero-title {
  font-family: 'Cormorant Garamond', 'Georgia', serif;
  font-size: clamp(2.2rem, 4vw, 3.5rem);
  font-weight: 300;
  line-height: 1.15;
  color: #fff;
  margin-bottom: 1rem;
}

.hero-title em {
  font-style: italic;
  color: var(--accent);
}

.hero-description {
  font-size: 1rem;
  line-height: 1.7;
  color: rgba(255,255,255,0.85);
  max-width: 420px;
  margin-bottom: 1.8rem;
}

.hero-buttons {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
}

.hero-btn-primary {
  padding: 0.85rem 2rem;
  border-radius: 50px;
  font-size: 0.85rem;
  font-weight: 600;
  letter-spacing: 0.05em;
  text-transform: uppercase;
  background: var(--accent);
  color: #fff;
  border: none;
  transition: all 0.3s ease;
  box-shadow: 0 8px 25px rgba(255,122,24,0.4);
}

.hero-btn-primary:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 35px rgba(255,122,24,0.5);
  color: #fff;
  background: #e66a14;
}

.hero-btn-outline {
  padding: 0.85rem 2rem;
  border-radius: 50px;
  font-size: 0.85rem;
  font-weight: 600;
  letter-spacing: 0.05em;
  text-transform: uppercase;
  background: transparent;
  color: #fff;
  border: 2px solid rgba(255,255,255,0.8);
  transition: all 0.3s ease;
}

.hero-btn-outline:hover {
  background: #fff;
  color: var(--primary);
  border-color: #fff;
  transform: translateY(-3px);
}

/* ── Hero Image Panel ── */
.hero-image-panel {
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}

.hero-product-card {
  background: #fff;
  border-radius: 20px;
  overflow: hidden;
  width: 220px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.25);
  animation: floatCard 6s ease-in-out infinite;
}

@keyframes floatCard {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-15px); }
}

.hero-card-img {
  height: 160px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #0d3060, var(--secondary));
  position: relative;
  overflow: hidden;
}

.hero-card-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.4s ease;
}

.hero-product-card:hover .hero-card-img img {
  transform: scale(1.1);
}

.hero-card-emoji {
  font-size: 4rem;
}

.hero-card-badge {
  position: absolute;
  top: 12px;
  left: 12px;
  padding: 0.35rem 0.7rem;
  border-radius: 6px;
  font-size: 0.68rem;
  font-weight: 700;
  letter-spacing: 0.06em;
  text-transform: uppercase;
  color: #fff;
}

.hero-card-body {
  padding: 1rem 1.2rem;
}

.hero-card-cat {
  font-size: 0.68rem;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--secondary);
  font-weight: 600;
  margin-bottom: 0.4rem;
}

.hero-card-name {
  font-family: 'Cormorant Garamond', serif;
  font-size: 1.05rem;
  font-weight: 600;
  color: var(--primary);
  line-height: 1.3;
  margin-bottom: 0.6rem;
}

.hero-card-price-wrap {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.hero-card-price {
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--primary);
}

.hero-card-price-old {
  font-size: 0.85rem;
  color: var(--text-secondary);
  text-decoration: line-through;
  margin-left: 0.5rem;
}

.hero-card-add {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  border: none;
  background: var(--primary);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.9rem;
  cursor: pointer;
  transition: all 0.2s ease;
}

.hero-card-add:hover {
  transform: scale(1.15);
  background: var(--accent);
}

/* ── Floating Elements ── */
.hero-chip {
  position: absolute;
  background: #fff;
  border-radius: 30px;
  padding: 0.5rem 1rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.72rem;
  font-weight: 600;
  color: var(--primary);
  box-shadow: 0 8px 28px rgba(10,37,64,0.2);
  white-space: nowrap;
  z-index: 3;
  pointer-events:none;
}

.hero-chip-1 { top: 2rem; right: 1rem; }
.hero-chip-2 { bottom: 2rem; left: 1rem; }

/* ── Carousel Controls ── */
.carousel-control-prev, .carousel-control-next {
    width: 44px;
    height: 44px;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0;
    transition: opacity 0.25s ease;
  }
  
  #hero-section:hover .carousel-control-prev,
  #hero-section:hover .carousel-control-next {
    opacity: 1;
  }
  
  .carousel-control-prev { left: 20px; }
  .carousel-control-next { right: 20px; }
  
  .carousel-control-prev-icon,
  .carousel-control-next-icon {
    width: 24px;
    height: 24px;
    background-color: rgba(0,0,0,0.5);
    border-radius: 50%;
    padding: 12px;
    background-size: 60%;
  }

/* ── Carousel Indicators ── */
#hero-section .carousel-indicators {
  bottom: 10px;
  z-index: 10;
  margin: 0;
}

#hero-section .carousel-indicators button {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  border: none;
  background: rgba(255,255,255,0.5);
  margin: 0 0.5rem;
  transition: all 0.3s ease;
}

#hero-section .carousel-indicators button.active {
  width: 35px;
  border-radius: 5px;
  background: #fff;
}

/* ── Mobile ── */
@media (max-width: 991px) {
  #hero-section,
  #hero-section .carousel,
  #hero-section .carousel-inner,
  #hero-section .carousel-item {
    height: 480px !important;
  }
  
  .hero-content {
    padding: 2rem 0;
  }
  
  .hero-text {
    padding-right: 0;
    text-align: center;
  }
  
  .hero-badge,
  .hero-title,
  .hero-description {
    margin-left: auto;
    margin-right: auto;
  }
  
  .hero-description {
    max-width: 100%;
    font-size: 0.95rem;
  }
  
  .hero-buttons {
    justify-content: center;
  }
  
  .hero-image-panel {
    display: none;
  }
  
  #hero-section .carousel-control-prev { left: 10px; }
  #hero-section .carousel-control-next { right: 10px; }
}

@media (max-width: 575px) {

  #hero-section,
  #hero-section .carousel,
  #hero-section .carousel-inner,
  #hero-section .carousel-item {
    height: 320px !important;
    min-height: 320px;
  }

  .hero-content {
    height: 100%;
    padding: .75rem 0;
    align-items: center !important;
  }

  .hero-text {
    padding-top: 0;
  }

  .hero-badge {
    margin-bottom: .75rem;
    padding: .35rem .8rem;
    font-size: .7rem;
    justify-content: center;
  }

  .hero-title {
    font-size: 1.6rem;
    line-height: 1.15;
    margin-bottom: .5rem;
  }

  .hero-description {
    font-size: .88rem;
    line-height: 1.5;
    margin-bottom: .75rem;
    max-width: 100%;
  }

  .hero-buttons {
    gap: .5rem;
    flex-direction: column;
    align-items: center;
  }

  .hero-btn-primary,
  .hero-btn-outline {
    width: 100%;
    max-width: 220px;
    padding: .65rem 1.5rem;
    font-size: .8rem;
  }

  .hero-chip-1 {
    top: .75rem;
    right: .75rem;
    font-size: .65rem;
    padding: .35rem .65rem;
  }

  .hero-chip-2 {
    bottom: .75rem;
    left: .75rem;
    font-size: .65rem;
    padding: .35rem .65rem;
  }

  .hero-chip {
    pointer-events: none;
  }

  #hero-section .carousel-indicators {
    bottom: 4px;
  }

  #hero-section .carousel-control-prev,
  #hero-section .carousel-control-next {
    width: 35px;
    height: 35px;
    opacity: 1 !important;
    z-index: 100;
  }

  #hero-section .carousel-control-prev {
    left: 5px;
  }

  #hero-section .carousel-control-next {
    right: 5px;
  }
}
</style>

<div class="container-fluid px-0">
  <div id="heroCarousel" class="carousel slide pt-2" data-bs-ride="carousel" data-bs-interval="5000" data-bs-touch="true">
    
    {{-- Indicators --}}
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
      <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
      <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
    </div>

    <div class="carousel-inner">
      {{-- ════ SLIDE 1: Featured Collection ════ --}}
      <div class="carousel-item active">
        <div class="hero-content container">
          <div class="row align-items-center w-100">
            {{-- Text Panel --}}
            <div class="col-lg-6 col-md-7">
              <div class="hero-text">
                <div class="hero-badge">
                  <i class="fas fa-sparkles"></i>
                  নতুন কালেকশন ২০২৫
                </div>
                <h1 class="hero-title">
                  Crafted for<br>
                  <em>Modern Living</em>
                </h1>
                <p class="hero-description">
                  সেরা মানের পণ্য, সেরা দামে। আপনার জীবনধারার জন্য বাছাই করা সংগ্রহ।
                </p>
                <div class="hero-buttons">
                  <a href="{{ route('shop.search') }}" class="hero-btn-primary">
                    <i class="fas fa-bag-shopping me-2"></i>Shop Now
                  </a>
                  <a href="#categories" class="hero-btn-outline">
                    Browse Collection
                    <i class="fas fa-arrow-right ms-2"></i>
                  </a>
                </div>
              </div>
            </div>

            {{-- Image Panel --}}
            <div class="col-lg-6 col-md-5">
              <div class="hero-image-panel">
                @php $p1 = $featured->first() ?? null; @endphp
                <div class="hero-product-card">
                  <div class="hero-card-img">
                    @if($p1?->primaryImage)
                      <img src="{{ Storage::url($p1->primaryImage->image) }}" alt="{{ $p1->name }}">
                    @else
                      <span class="hero-card-emoji">🛋️</span>
                    @endif
                    <span class="hero-card-badge" style="background: var(--accent);">
                      {{ $p1?->discount_percent ? '−'.$p1->discount_percent.'%' : 'New' }}
                    </span>
                  </div>
                  <div class="hero-card-body">
                    <div class="hero-card-cat">{{ $p1?->category?->name ?? 'Featured' }}</div>
                    <div class="hero-card-name">
                      {{ $p1 ? Str::limit($p1->name, 25) : 'Nordic Lounge Chair' }}
                    </div>
                    <div class="hero-card-price-wrap">
                      <div>
                        <span class="hero-card-price">৳{{ $p1 ? number_format($p1->current_price) : '8,500' }}</span>
                        @if($p1?->sale_price)
                          <span class="hero-card-price-old">৳{{ number_format($p1->base_price) }}</span>
                        @endif
                      </div>
                      <button class="hero-card-add" aria-label="Add to cart" @if($p1) onclick="addToCart({{ $p1->id }})" @endif>
                        <i class="fas fa-plus"></i>
                      </button>
                    </div>
                  </div>
                </div>

                {{-- Floating Chips --}}
                <div class="hero-chip hero-chip-1">
                  <span style="color: var(--warning);">★★★★★</span>
                  <strong>4.9</strong>
                </div>
                <div class="hero-chip hero-chip-2">
                  <i class="fas fa-truck" style="color: var(--secondary);"></i>
                  Free shipping ৳1,500+
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- ════ SLIDE 2: Summer Sale ════ --}}
      <div class="carousel-item">
        <div class="hero-content container">
          <div class="row align-items-center w-100">
            <div class="col-lg-6 col-md-7">
              <div class="hero-text">
                <div class="hero-badge" style="background: rgba(250, 180, 16, 0.2);">
                  <i class="fas fa-fire" style="color: var(--warning);"></i>
                  Summer Sale ২০২৫
                </div>
                <h1 class="hero-title">
                  Up to 40% Off<br>
                  <em style="color: var(--warning);">Sitewide Deals</em>
                </h1>
                <p class="hero-description">
                  গ্রীষ্মের সেরা অফার মিস করবেন না। সীমিত সময়ের জন্য বিশেষ ছাড়।
                </p>
                <div class="hero-buttons">
                  <a href="{{ route('shop.search') }}" class="hero-btn-primary" style="background: #fff; color: var(--primary);">
                    Claim Offer <i class="fas fa-arrow-right ms-2"></i>
                  </a>
                  <a href="{{ route('shop.search') }}" class="hero-btn-outline">
                    All Deals
                  </a>
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-md-5">
              <div class="hero-image-panel">
                <div class="hero-product-card">
                  <div class="hero-card-img" style="background: linear-gradient(135deg, var(--accent), var(--primary));">
                    <span class="hero-card-emoji">🪴</span>
                    <span class="hero-card-badge" style="background: var(--warning); color: var(--primary);">
                      −40%
                    </span>
                  </div>
                  <div class="hero-card-body">
                    <div class="hero-card-cat">Decor</div>
                    <div class="hero-card-name">Premium Plant Set</div>
                    <div class="hero-card-price-wrap">
                      <div>
                        <span class="hero-card-price">৳3,200</span>
                        <span class="hero-card-price-old">৳5,400</span>
                      </div>
                      <button class="hero-card-add" aria-label="Add to cart" style="background: var(--accent);">
                        <i class="fas fa-plus"></i>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="hero-chip hero-chip-1">
                  <i class="fas fa-fire" style="color: var(--accent);"></i>
                  <strong>Hot Deal</strong>
                </div>
                <div class="hero-chip hero-chip-2">
                  <i class="fas fa-clock" style="color: var(--accent);"></i>
                  Limited time
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- ════ SLIDE 3: New Arrivals ════ --}}
      <div class="carousel-item">
        <div class="hero-content container">
          <div class="row align-items-center w-100">
            <div class="col-lg-6 col-md-7">
              <div class="hero-text">
                <div class="hero-badge" style="background: rgba(34, 197, 94, 0.2);">
                  <i class="fas fa-star" style="color: #22C55E;"></i>
                  নতুন আগমন
                </div>
                <h1 class="hero-title">
                  Fresh Arrivals<br>
                  <em style="color: #9FE1CB;">Just In Store</em>
                </h1>
                <p class="hero-description">
                  এই সপ্তাহের নতুন পণ্য দেখুন। প্রতি সপ্তাহে নতুন আইটেম যোগ হচ্ছে।
                </p>
                <div class="hero-buttons">
                  <a href="{{ route('shop.search') }}" class="hero-btn-primary" style="background: #22C55E;">
                    <i class="fas fa-star me-2"></i>New Arrivals
                  </a>
                  <a href="{{ route('shop.search') }}" class="hero-btn-outline">
                    Catalogue
                  </a>
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-md-5">
              <div class="hero-image-panel">
                @php $p3 = $latest->first() ?? null; @endphp
                <div class="hero-product-card">
                  <div class="hero-card-img" style="background: linear-gradient(135deg, #085041, var(--secondary));">
                    @if($p3?->primaryImage)
                      <img src="{{ Storage::url($p3->primaryImage->image) }}" alt="{{ $p3->name }}">
                    @else
                      <span class="hero-card-emoji">🪞</span>
                    @endif
                    <span class="hero-card-badge" style="background: #22C55E;">New</span>
                  </div>
                  <div class="hero-card-body">
                    <div class="hero-card-cat">{{ $p3?->category?->name ?? 'Bedroom' }}</div>
                    <div class="hero-card-name">
                      {{ $p3 ? Str::limit($p3->name, 25) : 'Arch Floor Mirror' }}
                    </div>
                    <div class="hero-card-price-wrap">
                      <div>
                        <span class="hero-card-price">৳{{ $p3 ? number_format($p3->current_price) : '6,800' }}</span>
                      </div>
                      <button class="hero-card-add" aria-label="Add to cart" style="background: #0F6E56;" @if($p3) onclick="addToCart({{ $p3->id }})" @endif>
                        <i class="fas fa-plus"></i>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="hero-chip hero-chip-1">
                  <span style="color: var(--warning);">★★★★★</span>
                  <strong>5.0</strong>
                </div>
                <div class="hero-chip hero-chip-2">
                  <i class="fas fa-star" style="color: #22C55E;"></i>
                  This week
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    {{-- Controls --}}
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>

  </div>
</div>

</section>

