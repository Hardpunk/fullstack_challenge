<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->post('users', [App\Http\Controllers\API\UserAPIController::class, 'store'])->name('api.users.store');

    $api->group(['prefix' => 'auth'], function ($api) {
        $api->post('/login', [App\Http\Controllers\JWTAuthController::class, 'login'])->name('api.auth.login');
    });

    $api->group(['middleware' => 'api.auth'], function ($api) {
        $api->post('/logout', [App\Http\Controllers\JWTAuthController::class, 'logout']);
        $api->post('/refresh', [App\Http\Controllers\JWTAuthController::class, 'refresh']);
        $api->get('/profile', [App\Http\Controllers\JWTAuthController::class, 'userProfile']);

        $api->get('users', [App\Http\Controllers\API\UserAPIController::class, 'index'])->name('api.users.index');
        $api->get('users/{user}', [App\Http\Controllers\API\UserAPIController::class, 'show'])->name('api.users.show');
        $api->match(['put', 'patch'], 'users/{user}', [App\Http\Controllers\API\UserAPIController::class, 'update'])->name('api.users.update');
        $api->delete('users/{user}', [App\Http\Controllers\API\UserAPIController::class, 'destroy'])->name('api.users.destroy');
    });
});
