<x-guest-layout>
    <h3 class="auth-title text-center mb-3">
        Confirm Password
    </h3>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn btn-primary-custom w-100">
            Confirm
        </button>
</x-guest-layout>
