<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('dashboard');
});

Auth::routes();

Route::get('/dashboard', 'HomeController@index')->name('dashboard');


Route::middleware('auth')->prefix('api')->group( function() {
    Route::get('lots', 'ParkingLotController@index');
    Route::get('lots/{id}', 'ParkingLotController@item');
});