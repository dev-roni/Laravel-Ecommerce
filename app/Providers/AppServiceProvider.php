<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;

use App\Observers\OrderObserver;
use App\Observers\ProductObserver;
use App\Observers\UserObserver;
use App\Observers\CategoryObserver;

use App\Services\CartService;
use App\Services\CouponService;
use App\Services\RecentlyViewedService;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CartService::class, fn() => new CartService());
        $this->app->singleton(CouponService::class,fn() => new CouponService());
        $this->app->singleton(RecentlyViewedService::class,fn() => new RecentlyViewedService());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Category::observe(CategoryObserver::class);
        Order::observe(OrderObserver::class);
        Product::observe(ProductObserver::class);
        User::observe(UserObserver::class);
        $this->configureRateLimiting();

        View::composer('*', function ($view) {

            $categories = Cache::rememberForever(
                'global_categories',
                fn () => Category::query()
                    ->whereNull('parent_id')
                    ->where('is_active', true)
                    ->with([
                        'children' => fn ($q) => $q
                            ->where('is_active', true)
                            ->orderBy('order')
                    ])
                    ->orderBy('order')
                    ->get()
            );

            $view->with('globalCategories', $categories);
        });
    }

    private function configureRateLimiting(): void
    {
        // লগইন করা user হলে user id, নাহলে IP
        $userKey = fn (Request $r) => (string) ($r->user()?->id ?? $r->ip());

        // Login-এর মাঝামাঝি অবস্থায় থাকা user (OTP/Authenticator pending)
        $pendingKey = fn (Request $r) => (string) ($r->session()->get('auth_user_id') ?? $r->ip());

        // ── Auth ──────────────────────────────────────────
        // Breeze-এর LoginRequest আগে থেকেই email+IP অনুযায়ী ৫ বার সীমা রাখে।
        // এখানে শুধু IP-ভিত্তিক সীমা যোগ হচ্ছে, একই IP থেকে অনেক আলাদা
        // email চেষ্টা (credential stuffing) ঠেকাতে।
        RateLimiter::for('login', fn (Request $r) =>
            self::limit(Limit::perMinute(20)->by($r->ip()), 'অনেক বেশি login চেষ্টা'));

        RateLimiter::for('register', fn (Request $r) =>
            self::limit(Limit::perHour(10)->by($r->ip()), 'অনেক বেশি account খোলার চেষ্টা'));

        RateLimiter::for('password-reset', fn (Request $r) => [
            self::limit(
                Limit::perHour(5)->by(Str::lower((string) $r->input('email')) . '|' . $r->ip()),
                'অনেক বেশি password reset চেষ্টা'
            ),
            self::limit(Limit::perHour(20)->by($r->ip()), 'অনেক বেশি password reset চেষ্টা'),
        ]);

        RateLimiter::for('otp-verify', fn (Request $r) => [
            self::limit(Limit::perMinutes(10, 10)->by('otp|' . $pendingKey($r)), 'অনেক বেশি code চেষ্টা'),
            self::limit(Limit::perMinute(20)->by($r->ip()), 'অনেক বেশি code চেষ্টা'),
        ]);

        RateLimiter::for('otp-send', fn (Request $r) => [
            self::limit(Limit::perHour(10)->by('otp-send|' . $pendingKey($r)), 'অনেক বেশি code চাওয়া হয়েছে'),
            self::limit(Limit::perHour(20)->by($r->ip()), 'অনেক বেশি code চাওয়া হয়েছে'),
        ]);

        // Authenticator-এর code ৬ ডিজিট, তাই user-ভিত্তিক কড়া সীমা
        RateLimiter::for('two-factor', fn (Request $r) => [
            self::limit(Limit::perMinutes(15, 5)->by('2fa|' . $pendingKey($r)), 'অনেক বেশি code চেষ্টা'),
            self::limit(Limit::perMinute(20)->by($r->ip()), 'অনেক বেশি code চেষ্টা'),
        ]);

        // ── Order / Payment ───────────────────────────────
        RateLimiter::for('orders', fn (Request $r) => [
            self::limit(Limit::perMinute(5)->by('orders|' . $userKey($r)), 'অনেক দ্রুত order দেওয়া হচ্ছে'),
            self::limit(Limit::perHour(30)->by($r->ip()), 'অনেক দ্রুত order দেওয়া হচ্ছে'),
        ]);

        RateLimiter::for('payment', fn (Request $r) =>
            self::limit(Limit::perMinute(10)->by('pay|' . $userKey($r)), 'অনেক বেশি payment চেষ্টা'));

        // SSLCommerz callback — প্রতিটায় gateway-র validation API ডাকা হয়,
        // তাই flood হলে সেটাই DoS-এর পথ হতে পারে
        RateLimiter::for('payment-callback', fn (Request $r) =>
            Limit::perMinute(60)->by($r->ip())
                ->response(fn ($request, $headers) =>
                    response('Too Many Requests', 429, $headers)));

        // ── Shop ──────────────────────────────────────────
        RateLimiter::for('cart', fn (Request $r) =>
            self::limit(Limit::perMinute(60)->by('cart|' . $userKey($r)), 'অনেক বেশি request'));

        RateLimiter::for('coupon', fn (Request $r) =>
            self::limit(Limit::perMinute(10)->by('coupon|' . $userKey($r)), 'অনেক বেশি coupon চেষ্টা'));

        RateLimiter::for('reviews', fn (Request $r) =>
            self::limit(Limit::perHour(5)->by('review|' . $userKey($r)), 'অনেক বেশি review দেওয়া হচ্ছে'));

        RateLimiter::for('search', fn (Request $r) =>
            self::limit(Limit::perMinute(60)->by($r->ip()), 'অনেক বেশি search'));
    }
}
