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

Route::get('/', function () {
    return 'Hello<br><a href="/auth">Log in with lichess</a>';
});


Route::get('/lichess', 'LichessAuthorizationController@index');

Route::get('/callback', 'LichessAuthorizationController@callback');


Route::get('/auth', 'LichessAuthorizationController@index');
