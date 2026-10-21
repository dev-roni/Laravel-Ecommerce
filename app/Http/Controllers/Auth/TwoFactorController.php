<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class TwoFactorController extends Controller
{
    // ── Setup Page ────────────────────────────────
    public function setup(): View|RedirectResponse
    {
        $user = auth()->user();

        if (!$user->isAdmin()) abort(403);

        // আগে থেকে enabled → manage-এ
        if ($user->hasTwoFactorEnabled()) {
            return redirect()->route('2fa.manage');
        }

        // Secret generate (না থাকলে)
        if (!$user->two_factor_secret) {
            $secret = app('pragmarx.google2fa')->generateSecretKey();
            $user->update(['two_factor_secret' => $secret]);
        }

        // QR Code তৈরি
        $qrCodeUrl = app('pragmarx.google2fa')->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->two_factor_secret
        );

        $qrCode = $this->generateQrCodeSvg($qrCodeUrl);

        return view('auth.2fa.setup', compact('qrCode'));
    }


    // ── Confirm ───────────────────────────────────
    public function confirm(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|digits:6',
        ], [
            'code.required' => 'Code দিতে হবে।',
            'code.digits'   => '৬ সংখ্যার code দিন।',
        ]);

        $user  = auth()->user();
        $valid = app('pragmarx.google2fa')->verifyKey(
            $user->two_factor_secret,
            $request->code
        );

        if (!$valid) {
            return back()->withErrors([
                'code' => 'Code সঠিক নয়। App থেকে নতুন code দেখুন।',
            ]);
        }

        $user->update([
            'two_factor_enabled'      => true,
            'two_factor_confirmed_at' => now(),
        ]);

        AuditService::log('auth.2fa_enabled', $user);

        return redirect()->route('2fa.manage')
            ->with('success', '✅ Google Authenticator সফলভাবে চালু হয়েছে!');
    }
}