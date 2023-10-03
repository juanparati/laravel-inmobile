<?php

namespace Juanparati\Inmobile\Providers;

use Illuminate\Support\ServiceProvider;
use Juanparati\Inmobile\Inmobile;

class InmobileProvider extends ServiceProvider
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

        $this->app->singleton(Inmobile::class, function () {
            return new Inmobile(config('inmobile'));
        });
    }
}
