<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\EmployeeAssignmentDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEmployeeAssignmentRequest;
use App\Http\Requests\Admin\UpdateEmployeeAssignmentRequest;
use App\Models\Company;
use App\Models\Employee;
use App\Models\EmployeeAssignment;
use App\Models\Facility;
use App\Models\JobTitle;
use Illuminate\Support\Facades\Auth;

class EmployeeAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(EmployeeAssignmentDataTable $dataTable)
    {
        return $dataTable->render('admin.assignment.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $company = Company::first();
        $facilities = Facility::select('name', 'id')->orderBy('name', 'ASC')->get();
        $jobTitles = JobTitle::select('title', 'id')->orderBy('title', 'ASC')->get();
        $employees = Employee::select('first_name', 'middle_name', 'last_name', 'image', 'id')->orderBy('first_name', 'ASC')->get();
        $contractTypes = getContractTypes();
        $assignmentStatus = getAssignmentStatus();

        return view('admin.assignment.create', compact('company', 'facilities', 'jobTitles', 'employees', 'contractTypes', 'assignmentStatus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeAssignmentRequest $request)
    {
        $assignment = new EmployeeAssignment();
        $assignment->company_id = $request->company_id;
        $assignment->facility_id = $request->facility_id;
        $assignment->department_id = $request->department_id;
        $assignment->job_title_id = $request->job_title_id;
        $assignment->employee_id = $request->employee_id;
        $assignment->hire_date = Employee::findOrFail($request->employee_id)->created_at;
        $assignment->start_date = $request->start_date;
        $assignment->end_date = $request->end_date;
        $assignment->system_user_id = getUniqueSystemUserId();
        $assignment->contract_type = $request->contract_type;
        $assignment->status = getAssignmentStatus()[0];
        $assignment->created_by = Auth::user()->id;
        $assignment->save();

        toastr()->success('Created Successfully');
        return redirect()->route('admin.assignment.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeAssignment $assignment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeAssignment $assignment)
    {
        $company = Company::first();
        $facilities = Facility::select('name', 'id')->orderBy('name', 'ASC')->get();
        $jobTitles = JobTitle::select('title', 'id')->orderBy('title', 'ASC')->get();
        $employees = Employee::select('first_name', 'middle_name', 'last_name', 'image', 'id')->orderBy('first_name', 'ASC')->get();
        $contractTypes = getContractTypes();
        $assignmentStatus = getAssignmentStatus();

        return view('admin.assignment.create', compact('assignment', 'company', 'facilities', 'jobTitles', 'employees', 'contractTypes', 'assignmentStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeAssignmentRequest $request, EmployeeAssignment $assignment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeAssignment $assignment)
    {
        //
    }
}
