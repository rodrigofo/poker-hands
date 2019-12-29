<?php

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
Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')
    ->middleware('auth')
    ->name('home');

Route::post('/upload', 'HomeController@upload')
    ->middleware('auth')
    ->name('upload');

Route::get('/game/{game}', 'GameController@game')
    ->middleware('auth')
    ->name('game');
