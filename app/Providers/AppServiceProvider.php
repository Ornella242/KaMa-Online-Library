<?php

namespace App\Providers;

use App\Models\Category;
use App\Services\CartService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CartService::class);
    }

    public function boot(): void
    {
        view()->composer('partials.header', function ($view) {
            $categories = Category::whereHas('books')->get();
            $cartCount = app(CartService::class)->count();

            $view->with([
                'categories' => $categories,
                'cartCount' => $cartCount,
            ]);
        });

        Paginator::useBootstrapFive();
    }
}
