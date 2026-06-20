<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use App\Observers\CategoryObserver;
use App\Services\CartService;
use App\Services\CouponService;

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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Category::observe(CategoryObserver::class);

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
