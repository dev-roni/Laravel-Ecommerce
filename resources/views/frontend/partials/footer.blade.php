{{-- ══════════════════════════════════════
     FOOTER
══════════════════════════════════════ --}}
<footer class="site-footer pt-5 pb-4">
  <div class="container-xl">
    <div class="row g-5 pb-5" style="border-bottom:1px solid rgba(255,255,255,.1)">

      {{-- Brand --}}
      <div class="col-lg-4 col-md-6">
        <a href="{{ route('shop.index') }}" class="footer-brand-text d-block mb-3">
          🛒 পবন<span>বাহিকা</span>
        </a>
        <p style="font-size:.85rem;line-height:1.72;max-width:26ch">
          Curating timeless pieces for the modern home. Design that lasts.
        </p>
        <div class="d-flex gap-2 mt-4">
          <a href="#" class="soc-btn"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="#" class="soc-btn"><i class="fa-brands fa-instagram"></i></a>
          <a href="#" class="soc-btn"><i class="fa-brands fa-x-twitter"></i></a>
          <a href="#" class="soc-btn"><i class="fa-brands fa-youtube"></i></a>
        </div>
      </div>

      {{-- Shop --}}
      <div class="col-6 col-lg-2 col-md-3">
        <div class="footer-h mb-3">Shop</div>
        @foreach(\App\Models\Category::whereNull('parent_id')
          ->where('is_active', true)->limit(5)->orderBy('order')->get() as $cat)
          <a href="{{ route('shop.category', $cat->slug) }}" class="footer-link">
            {{ $cat->name }}
          </a>
        @endforeach
        <a href="{{ route('shop.search') }}" class="footer-link">সব পণ্য</a>
      </div>

      {{-- Account --}}
      <div class="col-6 col-lg-2 col-md-3">
        <div class="footer-h mb-3">Account</div>
        <a href="{{ route('login') }}"    class="footer-link">Login</a>
        <a href="{{ route('register') }}" class="footer-link">Register</a>
        @auth
          <a href="{{ route('orders.index') }}" class="footer-link">আমার Orders</a>
          <a href="{{ route('profile.edit') }}" class="footer-link">Profile</a>
        @endauth
      </div>

      {{-- Help --}}
      <div class="col-6 col-lg-2 col-md-3">
        <div class="footer-h mb-3">Help</div>
        <a href="#" class="footer-link">Shipping Info</a>
        <a href="#" class="footer-link">Returns</a>
        <a href="#" class="footer-link">Track Order</a>
        <a href="#" class="footer-link">FAQ</a>
        <a href="#" class="footer-link">Privacy Policy</a>
      </div>

      {{-- Contact --}}
      <div class="col-6 col-lg-2 col-md-3">
        <div class="footer-h mb-3">Contact</div>
        <div class="d-flex gap-2 align-items-start mb-2" style="font-size:.82rem">
          <i class="fa-solid fa-phone mt-1" style="color:var(--secondary)"></i>
          +880 1700-000000
        </div>
        <div class="d-flex gap-2 align-items-start mb-2" style="font-size:.82rem">
          <i class="fa-solid fa-envelope mt-1" style="color:var(--secondary)"></i>
          info@pobonbahika.com
        </div>
        <div class="d-flex gap-2 align-items-start" style="font-size:.82rem">
          <i class="fa-solid fa-location-dot mt-1" style="color:var(--secondary)"></i>
          Dhaka, Bangladesh
        </div>
      </div>

    </div>

    <div class="d-flex flex-wrap justify-content-between align-items-center pt-4 gap-3"
         style="font-size:.75rem;letter-spacing:.03em">
      <span>© {{ date('Y') }} পবনবাহিকা. All rights reserved.</span>
      <span>Made with <span style="color:var(--accent)">♡</span> in Bangladesh</span>
    </div>
  </div>
</footer>

<!-- Bootstrap JS -->
<script src="{{asset('frontend_assets/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('frontend_assets/js/script.js')}}"></script>

@stack('scripts')
</body>
</html>
