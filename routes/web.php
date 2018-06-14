<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('dashboard');
});

Auth::routes();

Route::get('/dashboard', 'HomeController@index')->name('dashboard');


Route::middleware('auth')->prefix('api')->group( function() {
    Route::get('lots', 'ParkingLotController@index');
    Route::get('lots/{id}', 'ParkingLotController@show');
    Route::get('lots/{id}/entries', 'ParkingLotController@entries');
    Route::post('lots/{id}/entries', 'ParkingLotController@addEntry');

    Route::get('entries/{id}', 'EntriesController@show');
    Route::patch('entries/{id}', 'EntriesController@update');
});