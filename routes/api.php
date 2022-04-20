<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UsersController;
use App\Http\Controllers\Api\V1\FarmsController;
use App\Http\Controllers\Api\V1\CattleController;
use App\Http\Controllers\Api\V1\FarmGoogleController;
use App\Http\Controllers\Api\V1\FarmFenceController;

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

Route::group(['prefix' => 'v1'], function() {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/passwords/reset', [AuthController::class, 'resetPassword']);
    Route::post('/passwords/change', [AuthController::class, 'changePassword']);

    Route::group(['middleware' => ['auth:api']], function() {
        // Users
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [UsersController::class, 'profile']);

        Route::put('/users/{user}/change-password', [UsersController::class, 'changePassword']);
        Route::group(['prefix' => 'super_admin/'], function() {
            Route::post('{id}/update-profile', [UsersController::class, 'updateProfile']);


            Route::group(['prefix' => 'farms/'], function() {
                Route::get('/', [FarmsController::class, 'index']);
                Route::post('/', [FarmsController::class, 'store']);
                Route::get('/{farm}', [FarmsController::class, 'show']);
                Route::put('/{farm}', [FarmsController::class, 'update']);
                Route::delete('/{farm}', [FarmsController::class, 'destroy']);
                Route::put('/{farm}/restore', [FarmsController::class, 'restore']);
                Route::get('/{farm}/linkGoogle', [FarmsController::class, 'linkGoogle']);
            });

            Route::group(['prefix' => 'cattle/'], function() {
                Route::get('/', [CattleController::class, 'index']);
                Route::post('/', [CattleController::class, 'store']);
                Route::get('/{cattle}', [CattleController::class, 'show']);
                Route::put('/{cattle}', [CattleController::class, 'update']);
                Route::delete('/{cattle}', [CattleController::class, 'destroy']);
                Route::put('/{cattle}/restore', [CattleController::class, 'restore']);
            });

            Route::group(['prefix' => 'farm_google/'], function() {
                Route::get('/', [FarmGoogleController::class, 'index']);
                Route::post('/', [FarmGoogleController::class, 'store']);
                Route::get('/{farm_google}', [FarmGoogleController::class, 'show']);
                Route::put('/{farm_google}', [FarmGoogleController::class, 'update']);
                Route::delete('/{farm_google}', [FarmGoogleController::class, 'destroy']);
                Route::put('/{farm_google}/restore', [FarmGoogleController::class, 'restore']);
                Route::get('/{farm_google}/auth', [FarmGoogleController::class, 'auth']);
            });

            Route::group(['prefix' => 'farm_fence/'], function() {
                Route::get('/', [FarmFenceController::class, 'index']);
                Route::post('/', [FarmFenceController::class, 'store']);
                Route::get('/{farm_fence}', [FarmFenceController::class, 'show']);
                Route::put('/{farm_fence}', [FarmFenceController::class, 'update']);
                Route::delete('/{farm_fence}', [FarmFenceController::class, 'destroy']);
                Route::put('/{farm_fence}/restore', [FarmFenceController::class, 'restore']);
                Route::get('/{farm_fence}/auth', [FarmFenceController::class, 'auth']);

                Route::group(['prefix' => 'coordinate/'], function() {
                    Route::post('/', [FarmFenceController::class, 'storeCoords']);
                    Route::put('/{farm_fence}', [FarmFenceController::class, 'updateCoords']);
                    Route::delete('/{farm_fence}', [FarmFenceController::class, 'destroyCoords']);
                    Route::put('/{farm_fence}/restore', [FarmFenceController::class, 'restoreCoords']);
                });

                Route::get('/{farm_fence}/coordinates', [FarmFenceController::class, 'coordinates']);
            });


        });

        Route::get('/farms/list', [FarmsController::class, 'list']);
        Route::get('/cattle/list', [CattleController::class, 'list']);
        Route::group(['prefix' => 'common'], function() {
            Route::get('/farmStatus', [FarmsController::class, 'getStatusList']);
            Route::get('/cattleStatus', [CattleController::class, 'getStatusList']);
            Route::get('/cattleArrivalStatusList', [CattleController::class, 'getCattleArrivalStatusList']);
            Route::get('/cattleBreed', [CattleController::class, 'getBreed']);
            Route::get('/cattleSex', [CattleController::class, 'getCattleSex']);
            Route::get('/cattleType', [CattleController::class, 'getCattleType']);
            Route::get('/farmGoogleStatus', [FarmGoogleController::class, 'getStatusList']);
        });

    });

});
