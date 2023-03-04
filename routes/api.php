<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Src\Vacancies\Candidates\Infrastructure\Controllers\AuthController;
use Src\Vacancies\Candidates\Infrastructure\Controllers\CreateCantidateController;
use Src\Vacancies\Candidates\Infrastructure\Controllers\GetAllCandidatesController;
use Src\Vacancies\Candidates\Infrastructure\Controllers\GetCandidateByIdController;
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

Route::middleware('jwt.verify')->group(function(){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);

    Route::post('lead', CreateCantidateController::class);
    Route::get('lead/{id}', GetCandidateByIdController::class);
    Route::get('leads', GetAllCandidatesController::class);
});

Route::post('auth', [AuthController::class, 'login']);
