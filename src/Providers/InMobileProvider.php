<?php

namespace Juanparati\InMobile\Providers;

use Illuminate\Support\ServiceProvider;
use Juanparati\InMobile\InMobile;

class InMobileProvider extends ServiceProvider
{
    /**
     * Bootstrap service.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/inmobile.php' => config_path('inmobile.php'),
        ], 'inmobile');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/inmobile.php', 'inmobile');

        $this->app->singleton(InMobile::class, function ($app) {
            return new InMobile($app['config']['inmobile']);
        });

        $this->app->alias(InMobile::class, 'inmobile');
    }
}
