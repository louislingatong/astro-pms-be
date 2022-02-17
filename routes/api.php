<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('oauth')
    ->group(base_path('routes/api/oauth.php'));
// register profile route
Route::prefix('profile')
    ->group(base_path('routes/api/profile.php'));
// register users routes
Route::prefix('users')
    ->group(base_path('routes/api/users.php'));
// register vessels routes
Route::prefix('vessels')
    ->group(base_path('routes/api/vessels.php'));
// register machineries routes
Route::prefix('machineries')
    ->group(base_path('routes/api/machineries.php'));
// register machinery sub categories routes
Route::prefix('machinery-sub-categories')
    ->group(base_path('routes/api/machinery-sub-categories.php'));
// register intervals routes
Route::prefix('intervals')
    ->group(base_path('routes/api/intervals.php'));
// register vessel machineries routes
Route::prefix('vessel-machineries')
    ->group(base_path('routes/api/vessel-machineries.php'));
// register vessel sub categories routes
Route::prefix('vessel-machinery-sub-categories')
    ->group(base_path('routes/api/vessel-machinery-sub-categories.php'));
// register running hours routes
Route::prefix('running-hours')
    ->group(base_path('routes/api/running-hours.php'));
// register works routes
Route::prefix('works')
    ->group(base_path('routes/api/works.php'));
