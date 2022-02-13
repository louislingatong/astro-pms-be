<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/', 'VesselMachineryController@index');
    Route::post('/', 'VesselMachineryController@create');
    Route::get('{vesselMachinery}', 'VesselMachineryController@read');
    Route::put('{vesselMachinery}', 'VesselMachineryController@update');
    Route::delete('{vesselMachinery}', 'VesselMachineryController@delete');
});
