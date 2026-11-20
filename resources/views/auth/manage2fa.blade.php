
@extends('frontend.layouts.masterLayout')
@section('title', '2FA Management')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">

      @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
      @endif

      <div class="card"
           style="border:none;border-radius:16px;
                  box-shadow:0 4px 24px rgba(10,37,64,.1)">
        <div class="card-body p-4">

          {{-- Status --}}
          <div class="text-center mb-4 pb-4"
               style="border-bottom:1px solid var(--border)">
            <div style="width:64px;height:64px;border-radius:50%;
                        background:rgba(34,197,94,.1);
                        border:2px solid var(--success);
                        display:flex;align-items:center;
                        justify-content:center;font-size:1.8rem;
                        margin:0 auto 12px">
              ✅
            </div>
            <h5 style="color:var(--primary)">2FA চালু আছে</h5>
            <p class="text-muted small mb-0">
              চালু হয়েছে:
              {{ auth()->user()->two_factor_confirmed_at?->format('d M Y') }}
            </p>
          </div>

          {{-- Info --}}
          <div class="mb-4 p-3 rounded"
               style="background:rgba(29,161,168,.06);
                      border:1px solid rgba(29,161,168,.2);
                      font-size:.83rem;line-height:1.65">
            <i class="bi bi-shield-check me-2"
               style="color:var(--secondary)"></i>
            Login-এ Google Authenticator-এর
            6-digit code দিতে হবে।
          </div>

          {{-- Disable --}}
          @if($errors->any())
            <div class="alert alert-danger py-2 mb-3 small">
              {{ $errors->first() }}
            </div>
          @endif

          <form method="POST"
                action="{{ route('2fa.disable') }}"
                onsubmit="return confirm('নিশ্চিতভাবে 2FA বন্ধ করবেন?')">
            @csrf
            <label class="form-label small fw-600">
              বন্ধ করতে পাসওয়ার্ড দিন
            </label>
            <input type="password"
                   name="password"
                   class="form-control mb-3
                          @error('password') is-invalid @enderror"
                   placeholder="আপনার পাসওয়ার্ড">
            @error('password')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <button type="submit"
                    class="btn btn-outline-danger w-100">
              2FA বন্ধ করুন
            </button>
          </form>

        </div>
      </div>

    </div>
  </div>
</div>
@endsection