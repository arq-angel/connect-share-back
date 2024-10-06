<?php

use App\Http\Controllers\Admin\AjaxDepartmentController;
use App\Http\Controllers\Admin\AjaxEmployeeController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\EmployeeAssignmentController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\JobTitleController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
//    return view('welcome');
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

/* Admin Routes */
Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    /** Company Route */
    Route::resource('company', CompanyController::class)->only(['index', 'update']);

    /** Facility Route */
    Route::resource('facility', FacilityController::class);

    /** Department Route */
    Route::resource('department', DepartmentController::class);

    /** Job Title Route */
    Route::resource('job-title', JobTitleController::class);

    /** Employee Route */
    Route::resource('employee', EmployeeController::class);

    /** Assignment Route */
    Route::resource('assignment', EmployeeAssignmentController::class);

    /** Ajax Employee Data Route */
    Route::get('ajax-employee/{id}', [AjaxEmployeeController::class, 'create'])->name('ajax-employee.create');

    /** Ajax Employee Data Route */
    Route::get('ajax-department/{id}', [AjaxDepartmentController::class, 'create'])->name('ajax-department.create');

});




