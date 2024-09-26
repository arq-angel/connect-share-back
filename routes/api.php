<?php

use App\Http\Controllers\Api\V1\CompanyController;
use App\Http\Controllers\Api\V1\EmployeeController;
use App\Http\Controllers\Api\V1\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/** api/v1 */
Route::group(['prefix' => '/v1', 'namespace' => 'App\Http\Controllers\Api\V1', 'middleware' => []], function () {
    Route::apiResource('company', CompanyController::class);
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('profile', ProfileController::class);
});
