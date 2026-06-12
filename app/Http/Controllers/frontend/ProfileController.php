<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}
