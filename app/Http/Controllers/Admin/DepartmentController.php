<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\DepartmentDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDepartmentRequest;
use App\Http\Requests\Admin\UpdateDepartmentRequest;
use App\Models\Company;
use App\Models\Department;
use App\Models\JobTitle;
use App\Traits\ImageUploadTrait;

class DepartmentController extends Controller
{
    use ImageUploadTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(DepartmentDataTable $dataTable)
    {
        return $dataTable->render('admin.department.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $company = Company::first();
        $jobTitles = JobTitle::all();
        $selectedJobTitles = $company->jobTitles->pluck('id')->toArray();
        return view('admin.department.create', compact('company', 'jobTitles', 'selectedJobTitles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartmentRequest $request)
    {
        $department = new Department();

        if ($request->hasFile('image')) {
            $imagePath = $this->uploadImage($request, 'image', 'uploads');
            $department->image = $imagePath;
        }
        $department->company_id = $request->company_id;
        $department->name = $request->name;
        $department->short_name = $request->short_name;
        $department->save();

        // Sync the selected job titles with the department (many-to-many relationship)
        $department->jobTitles()->sync($request->job_title_id);

        toastr()->success('Created Successfully');
        return redirect()->route('admin.department.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        $company = Company::first();
        $jobTitles = JobTitle::all();
        $selectedJobTitles = $department->jobTitles->pluck('id')->toArray();
        return view('admin.department.edit', compact('department', 'company', 'jobTitles', 'selectedJobTitles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        if ($request->hasFile('image')) {
            $imagePath = $this->updateImage($request, 'image', 'uploads', $department->image);
            $department->image = $imagePath;
        }
        $department->company_id = $request->company_id;
        $department->name = $request->name;
        $department->short_name = $request->short_name;
        $department->save();

        // Sync the selected job titles with the department (many-to-many relationship)
        $department->jobTitles()->sync($request->job_title_id);

        toastr()->success('Updated Successfully');
        return redirect()->route('admin.department.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        deleteFileIfExists($department->image);
        $department->delete();
    }
}
