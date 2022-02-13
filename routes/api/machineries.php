<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/', 'MachineryController@index');
    Route::post('/', 'MachineryController@create');
    Route::get('{machinery}', 'MachineryController@read');
    Route::put('{machinery}', 'MachineryController@update');
    Route::delete('{machinery}', 'MachineryController@delete');
});
