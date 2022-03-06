<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/', 'EmployeeController@index');
    Route::post('/', 'EmployeeController@create');
    Route::get('{employee}', 'EmployeeController@read');
    Route::put('{employee}', 'EmployeeController@update');
    Route::delete('{employee}', 'EmployeeController@delete');
});
