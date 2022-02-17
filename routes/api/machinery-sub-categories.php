<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/', 'MachinerySubCategoryController@index');
    Route::post('/', 'MachinerySubCategoryController@create');
    Route::get('{machinerySubCategory}', 'MachinerySubCategoryController@read');
    Route::put('{machinerySubCategory}', 'MachinerySubCategoryController@update');
    Route::delete('{machinerySubCategory}', 'MachinerySubCategoryController@delete');
    Route::post('/import', 'MachinerySubCategoryController@import');
});
