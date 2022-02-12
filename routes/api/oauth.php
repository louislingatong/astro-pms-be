<?php

use Illuminate\Support\Facades\Route;

Route::post('activate', 'UserController@activate');

Route::post('password/forgot', 'Auth\PasswordController@forgot');
Route::post('password/reset', 'Auth\PasswordController@reset');

Route::group(['middleware' => ['auth:api']], function () {
    Route::delete('token', 'Auth\TokenController@delete');
    Route::post('register', 'UserController@register');
});
