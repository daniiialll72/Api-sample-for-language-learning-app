<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Panel\PartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Client\PerformanceController;
use App\Http\Controllers\Client\AuthController as ClientAuthController;
use App\Http\Controllers\Panel\LevelController;
use App\Http\Controllers\Panel\LessonController;
use App\Http\Controllers\Panel\PeriodController;
use App\Http\Controllers\Panel\SliderController;
use App\Http\Controllers\Panel\LanguageController;
use App\Http\Controllers\Panel\LanguagemotherController;
use App\Http\Controllers\Panel\TagsController;

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

//--------------Panel------------------//
    Route::group(['prefix' => 'auth'], function () {
        Route::any('/register', [AuthController::class, 'register']);
        Route::any('/login', [AuthController::class, 'login']);
    });

    Route::apiResource('languagemother', LanguagemotherController::class);
    
    Route::middleware('auth:sanctum')->group(function () {


        Route::apiResource('language', LanguageController::class);

        Route::apiResource('course', PeriodController::class);

        Route::apiResource('level', LevelController::class);

        Route::get('lesson/changeFreeStatus', [LessonController::class, 'changeFreeStatus']);
        Route::apiResource('lesson', LessonController::class);

        Route::apiResource('part', PartController::class);

        Route::apiResource('slider', SliderController::class);

        Route::apiResource('tag', TagsController::class);
    });



//--------------Client------------------//
    Route::group(['prefix' => 'client'], function () {
        Route::any('/register', [ClientAuthController::class, 'register']);
        Route::any('/login', [ClientAuthController::class, 'login']);
        Route::any('/confirm', [ClientAuthController::class, 'confirm']);
        Route::any('/forgot', [ClientAuthController::class, 'forgot']);
        Route::any('/confirmResetPass', [ClientAuthController::class, 'confirmResetPass']);
        Route::any('/resetPass', [ClientAuthController::class, 'resetPass']);
    });
    Route::post('performance/setAnswer', [PerformanceController::class, 'setAnswer']);
