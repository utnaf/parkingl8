<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('dashboard');
});

Auth::routes();

Route::get('/dashboard', 'HomeController@index')->name('dashboard');

Route::get('/locale/{locale}', 'HomeController@locale')->name('locale');

// lots
Route::get('/lots/{id}', 'ParkingLotController@edit')->name('lot.edit');
Route::post('/lots/{id}', 'ParkingLotController@save')->name('lot.update');

// api
Route::middleware('auth')->prefix('api')->group( function() {
    Route::get('lots', 'ParkingLotController@index');
    Route::get('lots/{id}', 'ParkingLotController@show');
    Route::get('lots/{id}/entries', 'ParkingLotController@entries');
    Route::post('lots/{id}/entries', 'ParkingLotController@addEntry');

    Route::get('entries/{id}', 'EntriesController@show');
    Route::get('entries/{id}/price', 'EntriesController@price');
    Route::patch('entries/{id}', 'EntriesController@update');
});