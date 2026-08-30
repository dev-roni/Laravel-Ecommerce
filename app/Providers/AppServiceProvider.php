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
}
