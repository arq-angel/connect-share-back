<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\JobTitleDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreJobTitleRequest;
use App\Http\Requests\Admin\UpdateJobTitleRequest;
use App\Models\Company;
use App\Models\JobTitle;
use App\Traits\ImageUploadTrait;

class JobTitleController extends Controller
{

    use ImageUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(JobTitleDataTable $dataTable)
    {
        return $dataTable->render('admin.job-title.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $company = Company::first();
        $jobTitles = JobTitle::all();
        return view('admin.job-title.create', compact('company', 'jobTitles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobTitleRequest $request)
    {
        $jobTitle = new JobTitle();

        if ($request->hasFile('image')) {
            $imagePath = $this->uploadImage($request, 'image', 'uploads');
            $jobTitle->image = $imagePath;
        }
        $jobTitle->company_id = $request->company_id;
        $jobTitle->title = $request->title;
        $jobTitle->short_title = $request->short_title;
        $jobTitle->manager_id = $request->manager_id;
        $jobTitle->save();

        toastr()->success('Created Successfully');
        return redirect()->route('admin.job-title.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(JobTitle $jobTitle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobTitle $jobTitle)
    {
        $company = Company::first();
        $jobTitles = JobTitle::where('id',  '!=', $jobTitle->id)->get(); // to avoid recursion where the department has itself as its parent
        $managerId = $jobTitle->manager ? $jobTitle->manager->id : null;
        return view('admin.job-title.edit', compact('jobTitle', 'company', 'jobTitles', 'managerId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobTitleRequest $request, JobTitle $jobTitle)
    {
        if ($request->hasFile('image')) {
            $imagePath = $this->updateImage($request, 'image', 'uploads', $jobTitle->image);
            $jobTitle->image = $imagePath;
        }
        $jobTitle->company_id = $request->company_id;
        $jobTitle->title = $request->title;
        $jobTitle->short_title = $request->short_title;
        $jobTitle->manager_id = $request->manager_id;
        $jobTitle->save();

        toastr()->success('Updated Successfully');
        return redirect()->route('admin.job-title.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobTitle $jobTitle)
    {
        deleteFileIfExists($jobTitle->image);
        $jobTitle->delete();
    }
}
