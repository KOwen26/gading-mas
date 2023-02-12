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
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        View::composer(CustomersComposer::class);
    }
}