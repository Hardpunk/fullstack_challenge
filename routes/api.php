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
    $api->group(['namespace' => 'App\\Http\\Controllers\\API'], function ($api) {

        $api->group(['prefix' => 'auth'], function ($api) {
            $api->post('/login', 'JWTAuthController@login')->name('api.auth.login');
        });

        $api->post('users', 'UserAPIController@store')->name('api.users.store');

        $api->group(['middleware' => 'api.auth'], function ($api) {
            $api->post('/logout', 'JWTAuthController@logout')->name('api.auth.logout');
            $api->post('/refresh', 'JWTAuthController@refresh')->name('api.auth.refresh_token');

            $api->get('logs', 'LogAPIController@index')->name('api.logs.index');

            $api->get('users', 'UserAPIController@index')->name('api.users.index');
            $api->get('users/{user}', 'UserAPIController@show')->name('api.users.show');
            $api->get('/users/profile', 'JWTAuthController@userProfile')->name('api.users.profile');
            $api->delete('users/{user}', 'UserAPIController@destroy')->name('api.users.destroy');
            $api->match(['put', 'patch'], 'users/{user}', 'UserAPIController@update')->name('api.users.update');
        });
    });
});
