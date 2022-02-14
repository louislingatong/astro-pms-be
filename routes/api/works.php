<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/', 'WorkController@index');
    Route::post('/', 'WorkController@create');
});