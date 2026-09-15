
@extends('frontend.layouts.masterLayout')
@section('title', 'Email Verification')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-4">

      <div class="text-center mb-4">
        <div style="font-size:3rem">📧</div>
        <h4 style="color:var(--primary)">Email Verify করুন</h4>
        <p class="text-muted small">
          <strong>{{ $maskedEmail }}</strong>-এ<br>
          একটি 6-digit code পাঠানো হয়েছে।
        </p>
      </div>

      <div class="card"
           style="border:none;border-radius:16px;
                  box-shadow:0 4px 24px rgba(10,37,64,.1)">
        <div class="card-body p-4">

          @foreach(['success','error'] as $msg)
            @if(session($msg))
              <div class="alert alert-{{ $msg === 'success' ? 'success' : 'danger' }}
                          py-2 mb-3 text-center"
                   style="font-size:.85rem">
                {{ session($msg) }}
              </div>
            @endif
          @endforeach

          @if($errors->any())
            <div class="alert alert-danger py-2 mb-3 text-center"
                 style="font-size:.85rem">
              {{ $errors->first() }}
            </div>
          @endif

          <form method="POST"
                action="{{ route('verify.otp.post') }}"
                id="otpForm">
            @csrf
            <div class="mb-4">
              <input type="text"
                     name="otp"
                     id="otpInput"
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

          {{-- Countdown + Resend --}}
          <div class="text-center mt-3">
            <p class="small text-muted mb-1">
              Code expire হবে
              <span id="countdown"
                    style="font-weight:700;color:var(--primary)">
                10:00
              </span>
              পরে
            </p>
            <form method="POST" action="{{ route('verify.resend') }}">
              @csrf
              <button type="submit"
                      id="resendBtn"
                      class="btn btn-link btn-sm p-0"
                      style="font-size:.82rem;color:var(--text-secondary)"
                      disabled>
                নতুন Code পাঠান
              </button>
            </form>
          </div>

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
        Inbox-এ না পেলে Spam folder দেখুন।
      </p>

    </div>
  </div>
</div>

@push('scripts')
<script>
document.getElementById('otpInput').addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
    if (this.value.length === 6) {
        document.getElementById('verifyBtn').disabled = true;
        document.getElementById('verifyBtn').innerHTML =
            '<span class="spinner-border spinner-border-sm me-2"></span>Verifying...';
        document.getElementById('otpForm').submit();
    }
});

// Countdown
let seconds = 600;
const el     = document.getElementById('countdown');
const resend = document.getElementById('resendBtn');

setInterval(() => {
    seconds--;
    const m = String(Math.floor(seconds / 60)).padStart(2, '0');
    const s = String(seconds % 60).padStart(2, '0');
    el.textContent = `${m}:${s}`;

    if (seconds <= 60) el.style.color = 'var(--error)';
    if (seconds <= 0)  el.textContent = 'মেয়াদ শেষ';
    if (seconds <= 570) {
        resend.disabled = false;
        resend.style.color = 'var(--secondary)';
    }
}, 1000);
</script>
@endpush
@endsection