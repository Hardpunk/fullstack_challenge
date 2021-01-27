<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['namespace' => 'App\\Http\\Controllers'], function () {
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('/login', 'Auth\LoginController@login');
    Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('/register', 'UserController@register');
});

Auth::routes([
    'register' => false,
    'verify' => false
]);

Route::group(['middleware' => 'jwt.verify', 'namespace' => 'App\\Http\\Controllers'], function () {
    Route::match(['get', 'post'], '/logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('/', 'HomeController@index')->name('index');
    Route::get('/home', 'HomeController@home')->name('home');

    Route::group(['middleware' => 'role_permission:admin'], function() {
        Route::resource('users', 'UserController');
        Route::get('/logs', 'LogController@index')->name('logs.index');
    });

});

Route::any('/{param}', function () {
    return redirect('/');
})->where('param', '.*');
