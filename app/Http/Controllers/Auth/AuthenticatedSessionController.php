<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Services\AuditService;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(AuthRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = auth()->user();

        // Ban check
        if ($user->is_banned) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'আপনার account নিষ্ক্রিয়।');
        }

        $remember = $request->boolean('remember');
        auth()->logout(); // দুই ক্ষেত্রেই আগে logout

        // ════════════════════════════════════════
        // ADMIN → Google Authenticator
        // ════════════════════════════════════════
        if ($user->isAdmin()) {

            // 2FA setup করা না থাকলে → setup-এ পাঠাও
            if (!$user->hasTwoFactorEnabled()) {
                // Temporarily login করাও শুধু setup-এর জন্য
                auth()->login($user);
                $request->session()->regenerate();

                return redirect()->route('2fa.setup')
                    ->with('warning',
                        'Admin panel ব্যবহার করতে Authenticator setup করুন।'
                    );
            }

            // Authenticator verify-এ পাঠাও
            session([
                'auth_user_id'   => $user->id,
                'auth_remember'  => $remember,
                'auth_method'    => 'authenticator', // ← method mark
            ]);

            AuditService::log('auth.authenticator_required', $user);

            return redirect()->route('verify.authenticator');
        }

        // ════════════════════════════════════════
        // CUSTOMER → Email OTP
        // ════════════════════════════════════════
        $otp = $user->generateOtp();
        Mail::to($user->email)->send(new OtpMail($otp, $user->name));

        session([
            'auth_user_id'  => $user->id,
            'auth_remember' => $remember,
            'auth_method'   => 'email_otp',          // ← method mark
        ]);

        AuditService::log('auth.otp_sent', $user);

        return redirect()->route('verify.otp');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        AuditService::log(
            'user.logout',
            auth()->user()
        );
        
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->back();
    }

}
