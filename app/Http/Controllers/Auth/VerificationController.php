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
}
