<x-guest-layout>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror"
                    required
                    autofocus>
                    @error('email')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror

            </div>

            <div class="mb-3">
                <label class="form-label">
                    Password
                </label>

                <div class="input-group">

                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control @error('password') is-invalid @enderror"
                        required
                        >

                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        onclick="togglePassword()">
                        👁
                    </button>

                </div>
                @error('password')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">

                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="remember"
                        id="remember_me">

                    <label
                        class="form-check-label"
                        for="remember_me">
                        Remember me
                    </label>

                </div>

                @if(Route::has('password.request'))
                    <a
                        href="{{ route('password.request') }}"
                        class="auth-link">
                        Forgot Password?
                    </a>
                @endif

            </div>

            <button
                type="submit"
                class="btn btn-primary-custom w-100">
                Login
            </button>

        </form>

        <div class="d-flex align-items-center gap-3 my-4">
            <hr style="flex:1;border-color:var(--border)">
            <span style="font-size:.75rem;color:var(--text-secondary);white-space:nowrap">
                OR
            </span>
            <hr style="flex:1;border-color:var(--border)">
        </div>

        {{-- google login --}}

        <a href="{{ route('auth.google') }}"
        class="d-flex align-items-center justify-content-center gap-3 w-100 py-2 mb-1"
        style="border:1.5px solid var(--border);border-radius:8px;
                background:#fff;text-decoration:none;color:var(--text-primary);
                font-size:.88rem;font-weight:500;transition:all .2s"
        onmouseover="this.style.borderColor='var(--secondary)';this.style.boxShadow='0 2px 12px rgba(29,161,168,.12)'"
        onmouseout="this.style.borderColor='var(--border)';this.style.boxShadow=''">

            {{-- Google SVG Icon --}}
            <svg width="18" height="18" viewBox="0 0 48 48">
                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                <path fill="none" d="M0 0h48v48H0z"/>
            </svg>

            Login With Google
        </a>

        <a href="{{-- route('auth.facebook') --}}" 
            class="d-flex align-items-center justify-content-center gap-3 w-100 py-2"
            style="border:1.5px solid var(--border);border-radius:8px;
                    background:#fff;text-decoration:none;color:var(--text-primary);
                    font-size:.88rem;font-weight:500;transition:all .2s"
            onmouseover="this.style.borderColor='var(--secondary)';this.style.boxShadow='0 2px 12px rgba(29,161,168,.12)'"
            onmouseout="this.style.borderColor='var(--border)';this.style.boxShadow=''">

            {{-- Facebook SVG Icon --}}
            <svg width="18" height="18" viewBox="0 0 24 24">
                <path fill="#1877F2" d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
            Login with Facebook
        </a>

        <p class="text-center mt-4 mb-0 text-secondary">

            Don't have an account?

            <a
                href="{{ route('register') }}"
                class="auth-link fw-semibold">

                Register

            </a>

        </p>
</x-guest-layout>