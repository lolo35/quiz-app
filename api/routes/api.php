<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontEndLogsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\QuizController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'logs'], function() {
    Route::post('/error', [FrontEndLogsController::class, 'saveError']);
});

Route::group(['prefix' => 'frontend'], function () {
    Route::group(['prefix' => 'users'], function () {
        Route::post('/user', [UsersController::class, 'addUser']);
    });

    Route::group(['prefix' => "quiz"], function () {
        Route::get('/questions', [QuizController::class, 'generateQuiz']);
        Route::get('/answers', [QuizController::class, 'fetchAnswer']);
        Route::post('/answer', [QuizController::class, 'submitAnswer']);
    });
});