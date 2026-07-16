<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    // Google-এ redirect
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
                        ->scopes(['openid', 'profile', 'email'])
                        ->redirect();
    }

    // Google callback
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                             ->with('error', 'Google login ব্যর্থ হয়েছে। আবার চেষ্টা করুন।');
        }

        // Email দিয়ে user খোঁজো
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // আগে থেকে account আছে
            if ($user->is_banned) {
                return redirect()->route('login')
                                 ->with('error', 'আপনার account নিষ্ক্রিয় করা হয়েছে।');
            }

            // Google ID আপডেট করো যদি না থাকে
            if (!$user->google_id) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar(),
                ]);
            }

        } else {
            // নতুন user তৈরি করো
            $user = User::create([
                'name'           => $googleUser->getName(),
                'email'          => $googleUser->getEmail(),
                'google_id'      => $googleUser->getId(),
                'avatar'         => $googleUser->getAvatar(),
                'password'       => null,
                'role'           => 'customer',
                'email_verified' => true,
            ]);
        }

        // Login করাও
        Auth::login($user, remember: true);

        return redirect()->intended(route('shop.index'))
                         ->with('success', 'স্বাগতম, ' . $user->name . '!');
    }
}