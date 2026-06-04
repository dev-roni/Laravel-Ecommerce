<x-guest-layout>
<h3 class="auth-title mb-3">Verify Email</h3>

    <p class="auth-subtitle">
        We sent verification link to your email
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success">
            New link sent!
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf

        <button class="btn btn-primary-custom w-100 mb-3">
            Resend Email
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button class="btn btn-outline-secondary w-100">
            Logout
        </button>
    </form>
</x-guest-layout>
