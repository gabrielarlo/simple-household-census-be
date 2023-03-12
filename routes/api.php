<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\HouseholdController;
use App\Http\Controllers\API\HouseholdMemberController;
use App\Http\Controllers\API\StatsController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('unauthenticated', function () {
    return eRes('unauthenticated!', 401);
})->name('unauthenticated');

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('request-new-password', [AuthController::class, 'requestNewPassword']);
    Route::post('set-new-password', [AuthController::class, 'setNewPassword']);
});

Route::prefix('stats')->group(function () {
    Route::get('counts', [StatsController::class, 'counts']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('household')->group(function () {
        Route::get('get-suggestions', [HouseholdController::class, 'getSuggestions']);
        Route::get('list', [HouseholdController::class, 'list']);
        Route::post('create', [HouseholdController::class, 'create']);
        Route::post('update', [HouseholdController::class, 'update']);
        Route::post('delete', [HouseholdController::class, 'delete']);
    });

    Route::prefix('household-member')->group(function () {
        Route::get('list', [HouseholdMemberController::class, 'list']);
        Route::post('add', [HouseholdMemberController::class, 'add']);
        Route::post('update', [HouseholdMemberController::class, 'update']);
        Route::post('delete', [HouseholdMemberController::class, 'delete']);
    });

    Route::prefix('stats')->group(function () {
        Route::post('filter', [StatsController::class, 'filter']);
    });

    Route::prefix('user')->group(function () {
        Route::get('list', [UserController::class, 'list']);
    });
});

Route::get('phpinfo', function () {
    return phpinfo();
});
