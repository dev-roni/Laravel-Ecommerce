
<body>

@php
  $cartCount = auth()->check()
    ? app(\App\Services\CartService::class)->count()
    : 0;
@endphp
{{-- ══════════════════════════════════════
     NAVBAR
══════════════════════════════════════ --}}
<nav class="site-navbar navbar navbar-expand-lg sticky-top">
  <div class="container-xl">

    {{-- Brand --}}
    <a class="navbar-brand-custom me-3" href="{{ route('shop.index') }}">
      🛒 পবন<span>বাহিকা</span>
    </a>

    {{-- Mobile: Cart icon + Offcanvas toggler --}}
    <div class="d-flex align-items-center gap-2 ms-auto d-lg-none">
      @auth
        <a href="{{ route('cart.index') }}" class="nav-icon-btn position-relative">
          <i class="fa-solid fa-bag-shopping"></i>
          
          @if($cartCount > 0)
            <span class="cart-badge-pill">{{ $cartCount }}</span>
          @endif
        </a>
      @endauth
      <button class="navbar-toggler border-0 p-1"
              type="button"
              data-bs-toggle="offcanvas"
              data-bs-target="#mobileMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>

    {{-- Desktop Nav --}}
    <div class="collapse navbar-collapse" id="navCollapse">

      {{-- Category Dropdown — unlimited depth via recursive partial --}}
      <div class="cat-wrap me-3">
        <button class="cat-btn">
          <i class="fa-solid fa-grip"></i>
          সব Category
          <i class="fa-solid fa-chevron-down cat-chevron"></i>
        </button>
        <div class="cat-dropdown">
          @foreach(\App\Models\Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('order')
            ->get() as $cat)
            @include('frontend.partials._cat_item', ['category' => $cat, 'depth' => 0])
          @endforeach
        </div>
      </div>

      {{-- Search --}}
      <form action="{{ route('shop.search') }}" method="GET"
            class="search-form flex-grow-1 me-3">
        <div class="input-group input-group-sm">
          <input type="text" name="q" class="form-control"
                 value="{{ request('q') }}"
                 placeholder="Product খুঁজুন...">
          <button class="btn btn-search" type="submit">
            <i class="fa-solid fa-magnifying-glass"></i>
          </button>
        </div>
      </form>

      {{-- Right Actions --}}
      <div class="d-flex align-items-center gap-2">
        @auth
          {{-- Cart --}}
          <a href="{{ route('cart.index') }}" class="nav-icon-btn position-relative">
            <i class="fa-solid fa-bag-shopping"></i>
            
            @if($cartCount > 0)
              <span class="cart-badge-pill" id="cart-count">{{ $cartCount }}</span>
            @endif
          </a>
          {{-- User Dropdown --}}
          <div class="dropdown">
            <button class="user-pill-btn dropdown-toggle border-0" data-bs-toggle="dropdown">
              <span class="user-avt">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
              {{ Str::limit(auth()->user()->name, 12) }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <a class="dropdown-item" href="{{ route('orders.index') }}">
                  <i class="fa-solid fa-box me-2"></i>আমার Orders
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                  <i class="fa-regular fa-user me-2"></i>Profile
                </a>
              </li>
              @if(auth()->user()->isAdmin())
                <li><hr class="dropdown-divider"></li>
                <li>
                  <a class="dropdown-item" style="color:var(--secondary)"
                     href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-gauge me-2"></i>Admin Panel
                  </a>
                </li>
              @endif
              <li><hr class="dropdown-divider"></li>
              <li>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button class="dropdown-item text-danger w-100 text-start">
                    <i class="fa-solid fa-right-from-bracket me-2"></i>Logout
                  </button>
                </form>
              </li>
            </ul>
          </div>
        @else
          <a href="{{ route('login') }}" class="btn-nav-login">Login</a>
          <a href="{{ route('register') }}" class="btn-nav-register">Register</a>
        @endauth
      </div>

    </div>{{-- /.navbar-collapse --}}
  </div>
</nav>


{{-- ══════════════════════════════════════
     MOBILE OFFCANVAS MENU
══════════════════════════════════════ --}}
<div class="offcanvas offcanvas-start offcanvas-mobile"
     id="mobileMenu" tabindex="-1">
  <div class="offcanvas-header">
    <span class="offcanvas-title">🛒 পবনবাহিকা</span>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body p-0">

    {{-- Mobile Search --}}
    <div class="mob-search-bar">
      <form action="{{ route('shop.search') }}" method="GET">
        <input type="text" name="q"
               value="{{ request('q') }}"
               placeholder="পণ্য খুঁজুন...">
      </form>
    </div>

    {{-- Categories --}}
    <div class="pt-2">
      <div class="mob-section-label">Categories</div>
      @foreach(\App\Models\Category::whereNull('parent_id')
        ->where('is_active', true)
        ->orderBy('order')
        ->get() as $cat)
        @include('frontend.partials._mob_cat_item', ['category' => $cat, 'depth' => 0])
      @endforeach
    </div>

    <div class="mob-divider"></div>

    {{-- Account --}}
    <div class="pt-2">
      <div class="mob-section-label">Account</div>
      @auth
        <a href="{{ route('cart.index') }}" class="mob-nav-link" data-bs-dismiss="offcanvas">
          <i class="fa-solid fa-bag-shopping"></i> Cart
          @if($cartCount > 0)
            <span class="ms-auto d-flex align-items-center justify-content-center"
                  style="background:var(--error);color:#fff;border-radius:50%;width:18px;height:18px;font-size:.65rem;font-weight:700">
              {{ $cartCount }}
            </span>
          @endif
        </a>
        <a href="{{ route('orders.index') }}" class="mob-nav-link" data-bs-dismiss="offcanvas">
          <i class="fa-solid fa-box"></i> আমার Orders
        </a>
        <a href="{{ route('profile.edit') }}" class="mob-nav-link" data-bs-dismiss="offcanvas">
          <i class="fa-regular fa-user"></i> Profile
        </a>
        @if(auth()->user()->isAdmin())
          <a href="{{ route('admin.dashboard') }}"
             class="mob-nav-link" style="color:var(--secondary)" data-bs-dismiss="offcanvas">
            <i class="fa-solid fa-gauge"></i> Admin Panel
          </a>
        @endif
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="mob-nav-link w-100 border-0 text-start"
                  style="color:rgba(239,68,68,.8)">
            <i class="fa-solid fa-right-from-bracket"></i> Logout
          </button>
        </form>
      @else
        <a href="{{ route('login') }}" class="mob-nav-link" data-bs-dismiss="offcanvas">
          <i class="fa-solid fa-right-to-bracket"></i> Login
        </a>
        <a href="{{ route('register') }}" class="mob-nav-link"
           style="color:var(--accent)" data-bs-dismiss="offcanvas">
          <i class="fa-solid fa-user-plus"></i> Register
        </a>
      @endauth
    </div>

  </div>
</div>