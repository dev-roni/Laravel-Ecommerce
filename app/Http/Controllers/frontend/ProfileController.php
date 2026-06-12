<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        $user         = auth()->user();
        $recentOrders = $user->orders()->latest()->limit(5)->get();
        $totalOrders  = $user->orders()->count();
        $totalSpent   = $user->orders()
                             ->where('payment_status', 'paid')
                             ->sum('total');

        return view('frontend.pages.profile', compact('user', 'recentOrders', 'totalOrders', 'totalSpent'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'    => 'required|string|max:100',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ], [
            'name.required' => 'নাম দিতে হবে।',
        ]);

        $user->update($request->only('name', 'phone', 'address'));

        return back()->with('success', 'Profile আপডেট হয়েছে।');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'বর্তমান পাসওয়ার্ড দিন।',
            'password.required'         => 'নতুন পাসওয়ার্ড দিন।',
            'password.confirmed'        => 'পাসওয়ার্ড মিলছে না।',
            'password.min'              => 'পাসওয়ার্ড কমপক্ষে ৮ অক্ষর হতে হবে।',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'বর্তমান পাসওয়ার্ড ভুল।']);
        }

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'পাসওয়ার্ড পরিবর্তন হয়েছে।');
    }
}
