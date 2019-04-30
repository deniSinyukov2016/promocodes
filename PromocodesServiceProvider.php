<?php

namespace Itlead\Promocodes;

use Illuminate\Support\ServiceProvider;
use Itlead\Promocodes\Promocodes;

class PromocodesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/promocodes.php' => config_path('promocodes.php'),
        ]);

        $this->publishes([
            __DIR__ . '/migrations' => database_path('migrations')
        ], 'migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/promocodes.php', 'promocodes'
        );

        $this->loadMigrationsFrom(__DIR__ . '/migrations');

        $this->app->singleton('promocodes', function () {
            return new Promocodes;
        });
    }
}
