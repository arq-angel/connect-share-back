<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CompanyController;
use App\Http\Controllers\Api\V1\EmployeeController;
use App\Http\Controllers\Api\V1\FacilityController;
use App\Http\Controllers\Api\V1\OrganizationChartController;
use App\Http\Controllers\Api\V1\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/** api/v1 */
Route::group(['prefix' => '/v1', 'namespace' => 'App\Http\Controllers\Api\V1', 'middleware' => []], function () {

    /** Auth - login */
    Route::post('/login', [AuthController::class, 'login']);

    /** Requires Bearer Token */
    Route::group(['middleware' => ['auth:sanctum']], function () {
        /** Auth - logout */
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout-from-all', [AuthController::class, 'logoutFromAll']);

        /** validate bearer token */
        Route::get('/validate-token', function () {
            return response()->json([
                'success' => true,
                'message' => 'Valid Bearer token',
            ]);
        });

        /** profile resource controller - accessible only with sanctum validated token */
        Route::apiResource('profile', ProfileController::class)->only(['index', 'update']);

        /** only provides company's details */
        Route::apiResource('company', CompanyController::class)->only(['index']);

        /** only provides paginated employee list with params and individual employee contact details*/
        Route::apiResource('employees', EmployeeController::class)->only(['index', 'show']);

        /** only provides paginated facility list with params*/
        Route::apiResource('facilities', FacilityController::class)->only(['index', 'show']);

        /** only provides nested array of facilities within company in index and provides nested array of facility, departments, job titles and assigned employees in show*/
        Route::apiResource('orgCharts', OrganizationChartController::class)->only(['index', 'show']);
    });
});

