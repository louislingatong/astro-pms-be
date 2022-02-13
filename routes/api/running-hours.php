<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/', 'RunningHourController@index');
    Route::post('/', 'RunningHourController@create');
});
