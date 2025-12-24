<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class PaginationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Set custom pagination view
        Paginator::defaultView('components.pagination');
        Paginator::defaultSimpleView('components.simple-pagination');
        
        // Use Bootstrap 5 for pagination
        Paginator::useBootstrapFive();
    }
}