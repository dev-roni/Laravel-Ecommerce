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



{{-- Custom toast stack --}}
<div class="toast-stack" id="toast-stack"></div>