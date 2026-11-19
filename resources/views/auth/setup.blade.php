{{-- resources/views/auth/2fa/setup.blade.php --}}
@extends('frontend.layouts.masterLayout')
@section('title', '2FA Setup')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">

      <div class="text-center mb-4">
        <div style="font-size:3rem">🔐</div>
        <h4 style="color:var(--primary)">
          Google Authenticator Setup
        </h4>
        <p class="text-muted small">
          নিচের ধাপগুলো অনুসরণ করুন।
        </p>
      </div>

      @if(session('warning'))
        <div class="alert alert-warning mb-4">
          {{ session('warning') }}
        </div>
      @endif

      <div class="card"
           style="border:none;border-radius:16px;
                  box-shadow:0 4px 24px rgba(10,37,64,.1)">
        <div class="card-body p-4">

          {{-- Step 1 --}}
          <div class="d-flex gap-3 mb-4 pb-4"
               style="border-bottom:1px solid var(--border)">
            <div style="width:32px;height:32px;border-radius:50%;
                        background:var(--primary);color:#fff;
                        display:flex;align-items:center;
                        justify-content:center;font-weight:700;
                        flex-shrink:0">1</div>
            <div>
              <div class="fw-600 mb-2">App Download করুন</div>
              <div class="d-flex gap-2">
                <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2"
                   target="_blank"
                   class="btn btn-outline-secondary btn-sm">
                  Android
                </a>
                <a href="https://apps.apple.com/app/google-authenticator/id388497605"
                   target="_blank"
                   class="btn btn-outline-secondary btn-sm">
                  iOS
                </a>
              </div>
            </div>
          </div>

          {{-- Step 2: QR Code --}}
          <div class="d-flex gap-3 mb-4 pb-4"
               style="border-bottom:1px solid var(--border)">
            <div style="width:32px;height:32px;border-radius:50%;
                        background:var(--primary);color:#fff;
                        display:flex;align-items:center;
                        justify-content:center;font-weight:700;
                        flex-shrink:0">2</div>
            <div class="flex-grow-1">
              <div class="fw-600 mb-2">QR Code Scan করুন</div>
              <div class="text-center p-3 rounded"
                   style="background:var(--background)">
                <img src="data:image/svg+xml;base64,{{ $qrCode }}"
                     alt="QR Code"
                     style="width:180px;height:180px">
              </div>
            </div>
          </div>

          {{-- Step 3: Verify --}}
          <div class="d-flex gap-3">
            <div style="width:32px;height:32px;border-radius:50%;
                        background:var(--primary);color:#fff;
                        display:flex;align-items:center;
                        justify-content:center;font-weight:700;
                        flex-shrink:0">3</div>
            <div class="flex-grow-1">
              <div class="fw-600 mb-3">Code দিয়ে Confirm করুন</div>

              @if($errors->any())
                <div class="alert alert-danger py-2 mb-3 small">
                  {{ $errors->first() }}
                </div>
              @endif

              <form method="POST" action="{{ route('2fa.confirm') }}">
                @csrf
                <input type="text"
                       name="code"
                       class="form-control text-center mb-3"
                       style="font-size:1.8rem;font-weight:700;
                              letter-spacing:.3em;padding:.75rem"
                       placeholder="000000"
                       maxlength="6"
                       autofocus
                       inputmode="numeric"
                       autocomplete="one-time-code">
                <button type="submit"
                        class="btn btn-primary w-100">
                  Confirm ও চালু করুন
                </button>
              </form>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>
@endsection