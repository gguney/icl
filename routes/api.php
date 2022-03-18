<?php

use App\Http\Controllers\FixtureController;
use App\Http\Controllers\TeamController;
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

Route::get('/teams', [TeamController::class, 'index']);

Route::controller(FixtureController::class)
    ->prefix('/fixtures')
    ->group(
        function (){
            Route::get('/', 'index');
            Route::post('/generate', 'generate');
            Route::post('/reset-all', 'resetAllFixtures');
            Route::post('/play-next-week', 'playNextWeek');
            Route::post('/play-all', 'playAll');
            Route::patch('/{fixtureId}', 'update');
        }
    );

