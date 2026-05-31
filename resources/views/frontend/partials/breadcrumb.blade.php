{{-- ══════════════════════════════════════
     BREADCRUMB — @section('breadcrumb') দিলে দেখাবে
══════════════════════════════════════ --}}
@hasSection('breadcrumb')
  <div style="background:#fff;border-bottom:1px solid var(--border);padding:.6rem 0">
    <div class="container-xl">
      <nav><ol class="breadcrumb mb-0" style="font-size:.8rem">
        @yield('breadcrumb')
      </ol></nav>
    </div>
  </div>
@endif