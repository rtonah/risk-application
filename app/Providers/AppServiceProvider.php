<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
        // Option 1 : si get_setting() est globalement disponible
        $env_mode = get_setting('env_mode') ?? 'demo';
        // Partage avec toutes les vues
        View::share('env_mode', $env_mode);

        Paginator::useBootstrapFive();

    }

}
