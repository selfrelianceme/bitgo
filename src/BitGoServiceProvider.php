<?php

namespace Selfreliance\BitGo;
use Illuminate\Support\ServiceProvider;

class BitGoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/routes.php';
        $this->app->make('Selfreliance\BitGo\BitGo');

        $this->publishes([
            __DIR__.'/config/bitgo.php' => config_path('bitgo.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/bitgo.php', 'bitgo'
        );

        $this->app->bind(BitGo::class, function () {
            return new BitGo();
        });

        $this->app->alias(BitGo::class, 'payment.bitgo');
    }
}