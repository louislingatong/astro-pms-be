<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/', 'VesselController@index');
    Route::post('/', 'VesselController@create');
    Route::get('{vessel}', 'VesselController@read');
    Route::put('{vessel}', 'VesselController@update');
    Route::delete('{vessel}', 'VesselController@delete');
});
