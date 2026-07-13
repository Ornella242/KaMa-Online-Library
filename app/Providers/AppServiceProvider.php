<?php

namespace App\Providers;
use App\Models\Category;
use Illuminate\Pagination\Paginator;
use App\Models\Book;
use Illuminate\Support\Facades\URL;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       view()->composer('partials.header', function ($view) {
 
        //  Affiche les categories qui ont au moins un livre publie
            // $categories = Category::whereHas('books', function ($query) {
            //     $query->where('status', 'published');
            // })
            // ->get();
            
        // Affiche les categories qui ont au moins un livre
            $categories = Category::whereHas('books')
                ->get();

            $view->with('categories', $categories);

        });
            Paginator::useBootstrapFive();
          

    }
}
