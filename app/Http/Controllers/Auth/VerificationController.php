<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    // ════════════════════════════════════════
    // EMAIL OTP (Customer)
    // ════════════════════════════════════════

    public function showOtp()
    {
        if (!session('auth_user_id')
            || session('auth_method') !== 'email_otp') {
            return redirect()->route('login');
        }

        $user        = User::findOrFail(session('auth_user_id'));
        $maskedEmail = $this->maskEmail($user->email);

        return view('auth.verify.otp', compact('maskedEmail'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ], [
            'otp.required' => 'Code দিতে হবে।',
            'otp.digits'   => '৬ সংখ্যার code দিন।',
        ]);

        $userId = session('auth_user_id');

        if (!$userId || session('auth_method') !== 'email_otp') {
            return redirect()->route('login');
        }

        $user = User::findOrFail($userId);

        // Attempt limit
        if ($user->otp_attempts >= 5) {
            $this->clearAuthSession();
            return redirect()->route('login')
                ->with('error', 'অনেক বেশি চেষ্টা। আবার login করুন।');
        }

        if (!$user->verifyOtp($request->otp)) {
            AuditService::log('auth.otp_failed', $user, [], [
                'attempts' => $user->fresh()->otp_attempts,
            ]);

            $remaining = 5 - $user->fresh()->otp_attempts;

            return back()->withErrors([
                'otp' => "Code সঠিক নয়। আর {$remaining} বার চেষ্টা করতে পারবেন।",
            ]);
        }

        return $this->loginUser($request, $user);
    }
}
