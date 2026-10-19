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
}