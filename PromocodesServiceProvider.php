<?php

namespace Itlead\Promocodes;

use Illuminate\Support\ServiceProvider;
use Itlead\Promocodes\Promocodes;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;

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

        // $this->publishes([
        //     __DIR__ . '/migrations' => database_path('migrations')
        // ], 'migrations');
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

        // Register factories.
        $this->app->make(EloquentFactory::class)->load(__DIR__ . '/factories');

        // $this->app->singleton('promocodes', function () {
        //     return new Promocodes;
        // });
    }
}
