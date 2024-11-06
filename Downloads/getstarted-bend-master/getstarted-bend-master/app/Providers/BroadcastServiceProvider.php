<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Routing\Router;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['router']->group([
            'middleware' => ['web']
        ], function (Router $router) {
        
            /** @noinspection PhpIncludeInspection */
            require_once base_path('routes/channels.php');
        });
        //Broadcast::routes();
        // require base_path('routes/channels.php');
    }
}