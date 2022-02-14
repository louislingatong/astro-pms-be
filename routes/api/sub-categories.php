<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/', 'SubCategoryController@index');
    Route::post('/', 'SubCategoryController@create');
    Route::get('{subCategory}', 'SubCategoryController@read');
    Route::put('{subCategory}', 'SubCategoryController@update');
    Route::delete('{subCategory}', 'SubCategoryController@delete');
    Route::post('/import', 'SubCategoryController@import');
});
