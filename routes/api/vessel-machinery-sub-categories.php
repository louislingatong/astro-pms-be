<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/', 'VesselMachinerySubCategoryController@index');
    Route::post('/', 'VesselMachinerySubCategoryController@create');
    Route::get('{vesselMachinerySubCategory}', 'VesselMachinerySubCategoryController@read');
    Route::put('{vesselMachinerySubCategory}', 'VesselMachinerySubCategoryController@update');
    Route::delete('{vesselMachinerySubCategory}', 'VesselMachinerySubCategoryController@delete');
});
