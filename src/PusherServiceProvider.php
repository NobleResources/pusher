<?php

namespace VanLonden\Pusher;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class PusherServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/pusher.php' => config_path('pusher.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/migrations/' => database_path('migrations')
        ], 'migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Pusher::class, function ($app) {
           return new Pusher(
               new Client(),
               config('pusher.server_key'),
               config('pusher.batch_size')
           );
        });
    }
}