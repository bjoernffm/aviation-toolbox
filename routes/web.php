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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/timeadder', function () {
    return view('timeadder.show');
});

Route::get('/autobrake', 'AutobrakeController@show');
Route::post('/autobrake', 'AutobrakeController@store');

Route::get('/descend-calculator', 'DescendCalculatorController@show');
Route::post('/descend-calculator', 'DescendCalculatorController@store');
