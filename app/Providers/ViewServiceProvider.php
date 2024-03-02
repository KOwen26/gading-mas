<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use App\View\Composers\CustomersComposer;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
        View::composer(CustomersComposer::class);
    }
}