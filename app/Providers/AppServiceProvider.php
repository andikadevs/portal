<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
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
        // Paksa HTTPS di production (PRD §9).
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Bagikan daftar kategori ke navbar publik.
        View::composer('components.layouts.public', function ($view): void {
            $view->with('navCategories', Category::orderBy('name')->get());
        });

        Paginator::defaultView('vendor.pagination.newsportal');
    }
}
