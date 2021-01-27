<?php

namespace App\Providers;

use Dingo\Api\Auth\Provider\JWT;
use Illuminate\Support\ServiceProvider;

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
        setlocale(LC_ALL, 'pt-BR', 'pt_BR.utf8');

        app('Dingo\Api\Auth\Auth')->extend('jwt', function ($app) {
            return new JWT($app['Tymon\JWTAuth\JWTAuth']);
        });
    }
}
