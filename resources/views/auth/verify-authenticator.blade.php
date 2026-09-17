
@extends('frontend.layouts.masterLayout')
@section('title', 'Authenticator Verify')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-4">

      <div class="text-center mb-4">
        <div style="font-size:3rem">🔐</div>
        <h4 style="color:var(--primary)">Authenticator Code</h4>
        <p class="text-muted small">
          Google Authenticator app থেকে<br>
          6-digit code দিন।
        </p>
      </div>

      <div class="card"
           style="border:none;border-radius:16px;
                  box-shadow:0 4px 24px rgba(10,37,64,.1)">
        <div class="card-body p-4">

          @if($errors->any())
            <div class="alert alert-danger py-2 mb-3 text-center"
                 style="font-size:.85rem">
              {{ $errors->first() }}
            </div>
          @endif

          <form method="POST"
                action="{{ route('verify.authenticator.post') }}"
                id="authForm">
            @csrf
            <div class="mb-4">
              <input type="text"
                     name="code"
                     id="codeInput"
                     class="form-control text-center"
                     style="font-size:2.2rem;font-weight:800;
                            letter-spacing:.5em;padding:1rem;
                            border:2px solid var(--border);
                            border-radius:10px"
                     placeholder="000000"
                     maxlength="6"
                     autofocus
                     autocomplete="one-time-code"
                     inputmode="numeric">
            </div>
            <button type="submit"
                    id="verifyBtn"
                    class="btn btn-primary w-100 py-2">
              Verify করুন
            </button>
          </form>

          <div class="text-center mt-3">
            <a href="{{ route('login') }}"
               style="font-size:.8rem;color:var(--text-secondary);
                      text-decoration:none">
              ← Login-এ ফিরুন
            </a>
          </div>

        </div>
      </div>

      <p class="text-center mt-3"
         style="font-size:.75rem;color:var(--text-secondary)">
        <i class="bi bi-info-circle me-1"></i>
        Code প্রতি ৩০ সেকেন্ডে বদলায়।
      </p>

    </div>
  </div>
</div>

@push('scripts')
<script>
document.getElementById('codeInput').addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
    if (this.value.length === 6) {
        document.getElementById('verifyBtn').disabled = true;
        document.getElementById('verifyBtn').innerHTML =
            '<span class="spinner-border spinner-border-sm me-2"></span>Verifying...';
        document.getElementById('authForm').submit();
    }
});
</script>
@endpush
@endsection