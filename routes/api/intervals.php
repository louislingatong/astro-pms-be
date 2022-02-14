<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/', 'IntervalController@index');
    Route::post('/', 'IntervalController@create');
    Route::get('{interval}', 'IntervalController@read');
    Route::put('{interval}', 'IntervalController@update');
    Route::delete('{interval}', 'IntervalController@delete');
    Route::post('/import', 'IntervalController@import');
});
