<x-guest-layout>
<div class="text-center mb-3">
        <h3 class="auth-title">Forgot Password</h3>
        <p class="auth-subtitle">We will send reset link</p>
    </div>

    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control">
            @error('email')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn btn-primary-custom w-100">
            Send Reset Link
        </button>
    </form>

    <p class="text-center mt-3">
        <a href="{{ route('login') }}" class="auth-link">Back to Login</a>
    </p>
</x-guest-layout>
