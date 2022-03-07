<?php

use Illuminate\Support\Facades\Route;
use App\Models\VesselMachinerySubCategory;
use Illuminate\Http\Request ;

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/', 'WorkController@index');
    Route::post('/', 'WorkController@create');

    Route::get('/count', function (Request $request) {
        $work = [];
        $work['warning'] = VesselMachinerySubCategory::searchByStatus(config('work.statuses.warning'))->count();
        $work['due'] = VesselMachinerySubCategory::searchByStatus(config('work.statuses.due'))->count();
        $work['overdue'] = VesselMachinerySubCategory::searchByStatus(config('work.statuses.overdue'))->count();
        return response()->json(['data' => $work]);
    });
});
