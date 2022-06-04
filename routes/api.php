<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Panel\PartController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('languagemother', LanguagemotherController::class);

Route::apiResource('language', LanguageController::class);

Route::apiResource('period', PeriodController::class);

Route::apiResource('level', LevelController::class);

Route::get('lesson/changeFreeStatus', [LessonController::class, 'changeFreeStatus']);
Route::apiResource('lesson', LessonController::class);

Route::apiResource('part', PartController::class);

Route::apiResource('slider', SliderController::class);

Route::apiResource('tag', TagsController::class);

// Route::group(['prefix' => 'sliders'], function () {
//     Route::get('/', [SliderController::class, 'get']);
//     Route::post('/storeSimpleSlider', [SliderController::class, 'storeSimpleSlider']);
//     Route::post('/storeMultiSlider', [SliderController::class, 'storeMultiSlider']);
// });
