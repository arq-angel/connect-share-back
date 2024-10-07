<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeAssignment;
use App\Models\Facility;
use App\Models\JobTitle;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $facilityCount = Facility::count();
        $departmentCount = Department::count();
        $jobTitleCount = JobTitle::count();
        $employeeCount = Employee::count();
        $assignmentCount = EmployeeAssignment::count();
        return view('admin.dashboard' , compact('facilityCount' , 'departmentCount' , 'jobTitleCount' , 'employeeCount', 'assignmentCount'));
    }
}
