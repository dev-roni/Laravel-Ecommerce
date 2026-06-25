<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')
                     ->withCount('orders')
                     ->withSum(['orders as total_spent' => function($q) {
                         $q->where('payment_status', 'paid');
                     }], 'total');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%')
                  ->orWhere('phone', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('is_banned', $request->status === 'banned');
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('backend.pages.users', compact('users'));
    }

    public function show(User $user)
    {
        $user->loadCount('orders');
        $orders = $user->orders()->with('items')->latest()->paginate(10);

        $stats = [
            'total_orders'  => $user->orders()->count(),
            'total_spent'   => $user->orders()->where('payment_status', 'paid')->sum('total'),
            'pending_orders'=> $user->orders()->where('status', 'pending')->count(),
            'cancelled'     => $user->orders()->where('status', 'cancelled')->count(),
        ];

        return view('backend.pages.userShow', compact('user', 'orders', 'stats'));
    }

    public function toggleBan(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Admin user ব্যান করা যাবে না।');
        }

        $user->update(['is_banned' => !$user->is_banned]);

        return back()->with('success',
            $user->is_banned ? 'User ব্যান করা হয়েছে।' : 'User-এর ব্যান তুলে নেওয়া হয়েছে।'
        );
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Admin user মুছা যাবে না।');
        }

        if ($user->orders()->exists()) {
            return back()->with('error', 'Order আছে এমন user মুছা যাবে না, ব্যান করুন।');
        }

        $user->delete();

        return redirect()->route('backend.pages.users')
                         ->with('success', 'User মুছে ফেলা হয়েছে।');
    }

    // Admin user list (separate)
    public function managers()
    {
        $users = User::where('role', 'manager')->paginate(20)->withQueryString();
        return view('backend.pages.users', compact('users'));
    }

    public function createAdmin()
    {
        return view('backend.pages.users.create-admin');
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'email.unique' => 'এই email আগে থেকেই আছে।',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
        ]);

        return redirect()->route('backend.pages.users.admins')
                         ->with('success', 'নতুন Admin তৈরি হয়েছে।');
    }
}
