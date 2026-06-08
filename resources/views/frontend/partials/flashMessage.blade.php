{{-- ══════════════════════════════════════
     FLASH MESSAGES
══════════════════════════════════════ --}}
@if(session('success'))
  <div class="flash-alert flash-success" id="flash-success">
    <div class="container-xl d-flex align-items-center justify-content-between">
      <span><i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}</span>
      <button class="flash-close" onclick="this.closest('.flash-alert').remove()">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>
  </div>
@endif
@if(session('status'))
  <div class="flash-alert flash-success" id="flash-success">
    <div class="container-xl d-flex align-items-center justify-content-between">
      <span><i class="fa-solid fa-circle-check me-2"></i>{{ session('status') }}</span>
      <button class="flash-close" onclick="this.closest('.flash-alert').remove()">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>
  </div>
@endif
@if(session('error'))
  <div class="flash-alert flash-error" id="flash-error">
    <div class="container-xl d-flex align-items-center justify-content-between">
      <span><i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }}</span>
      <button class="flash-close" onclick="this.closest('.flash-alert').remove()">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>
  </div>
@endif


{{-- ══════════════════════════════════════
     CART TOAST (Bootstrap native)
══════════════════════════════════════ --}}
<div class="position-fixed bottom-0 end-0 p-3" style="z-index:9999">
  <div id="cart-toast" class="toast align-items-center text-bg-success border-0" role="alert">
    <div class="d-flex">
      <div class="toast-body d-flex align-items-center gap-2">
        <i class="fa-solid fa-circle-check"></i>
        <span id="toast-message">পণ্য কার্টে যোগ হয়েছে!</span>
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto"
              data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>

{{-- Custom toast stack --}}
<div class="toast-stack" id="toast-stack"></div>