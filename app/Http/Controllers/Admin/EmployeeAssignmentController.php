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
        $employees = Employee::select('first_name', 'middle_name', 'last_name', 'image', 'id')->orderBy('first_name', 'ASC')->get();
        $contractTypes = getContractTypes();
        $assignmentStatus = getAssignmentStatus();

        return view('admin.assignment.create', compact('company', 'facilities', 'employees', 'contractTypes', 'assignmentStatus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeAssignmentRequest $request)
    {
        // Check if the combination of employee_id, facility_id, job_title_id, and department_id exists for another assignment
        // because the update is triggering the unique constraint violation due to how MySQL processes the update
        $exists = EmployeeAssignment::where('employee_id', $request->employee_id)
            ->where('facility_id', $request->facility_id)
            ->where('job_title_id', $request->job_title_id)
            ->where('department_id', $request->department_id)
            ->exists();

        if ($exists) {
            toastr()->error('An assignment with this employee, facility, job title, and department already exists.');
            return back();
        }

        $assignment = new EmployeeAssignment();
        $assignment->company_id = $request->company_id;
        $assignment->facility_id = $request->facility_id;
        $assignment->department_id = $request->department_id;
        $assignment->job_title_id = $request->job_title_id;
        $assignment->employee_id = $request->employee_id;
        $assignment->hire_date = Employee::findOrFail($request->employee_id)->created_at;
        $assignment->start_date = $request->start_date;
        $assignment->end_date = $request->end_date;
        $assignment->contract_type = $request->contract_type;
        $assignment->status = getAssignmentStatus()[0];  // Active
        $assignment->created_by = Auth::user()->id;
        $assignment->save();

        $assignment->system_user_id = $assignment->id + 1000;
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
        $employees = Employee::select('first_name', 'middle_name', 'last_name', 'image', 'id')->orderBy('first_name', 'ASC')->get();
        $contractTypes = getContractTypes();
        $assignmentStatus = getAssignmentStatus();
        $selectedFacility = $assignment->facility->id;
        $selectedEmployee = $assignment->employee->id;

        return view('admin.assignment.edit', compact(
            'assignment', 'company', 'facilities', 'employees',
            'contractTypes', 'assignmentStatus', 'selectedFacility', 'selectedEmployee'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeAssignmentRequest $request, EmployeeAssignment $assignment)
    {
        // Check if the combination of employee_id, facility_id, job_title_id, and department_id exists for another assignment
        // because the update is triggering the unique constraint violation due to how MySQL processes the update
        $exists = EmployeeAssignment::where('employee_id', $request->employee_id)
            ->where('facility_id', $request->facility_id)
            ->where('job_title_id', $request->job_title_id)
            ->where('department_id', $request->department_id)
            ->where('id', '!=', $assignment->id) // Exclude the current record being updated
            ->exists();

        if ($exists) {
            toastr()->error('An assignment with this employee, facility, job title, and department already exists.');
            return back();
        }

        // Update all fields, including the unique fields
        $assignment->company_id = $request->company_id;
        $assignment->facility_id = $request->facility_id;
        $assignment->department_id = $request->department_id;
        $assignment->job_title_id = $request->job_title_id;
        $assignment->employee_id = $request->employee_id;

        // Update the non-unique fields
        $assignment->hire_date = Employee::findOrFail($request->employee_id)->created_at;
        $assignment->start_date = $request->start_date;
        $assignment->end_date = $request->end_date;
        $assignment->contract_type = $request->contract_type;
        $assignment->status;
        $assignment->updated_by = Auth::user()->id;

        // Save the changes
        $assignment->save();

        toastr()->success('Updated Successfully');
        return redirect()->route('admin.assignment.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeAssignment $assignment)
    {
        //
    }
}
