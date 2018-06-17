<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('dashboard');
});

Auth::routes();

Route::middleware('auth')->group(function() {
    Route::get('/dashboard', 'HomeController@index')->name('dashboard');
    Route::get('/issues', 'IssuesController@index')->name('issues');
    Route::patch('/issues/{id}', 'IssuesController@update')->name('issues.update');

    Route::get('/locale/{locale}', 'HomeController@locale')->name('locale');
});

// lots
Route::middleware(['auth', 'role:admin'])->group( function() {
    Route::get('/lots/{id}', 'ParkingLotController@edit')->name('lot.edit');
    Route::post('/lots/{id}', 'ParkingLotController@save')->name('lot.update');
    Route::get('/users', 'UserController@index')->name('user.list');
    Route::patch('/users/{id}', 'UserController@update')->name('user.update');
});

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