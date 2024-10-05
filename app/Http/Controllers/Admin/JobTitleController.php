<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\JobTitleDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreJobTitleRequest;
use App\Http\Requests\Admin\UpdateJobTitleRequest;
use App\Models\JobTitle;

class JobTitleController extends Controller
{
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
        return view('admin.job-title.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobTitleRequest $request)
    {
        $jobTitle = new JobTitle();

        $jobTitle->title = $request->title;
        $jobTitle->short_title = $request->short_title;
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
        return view('admin.job-title.edit', compact('jobTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobTitleRequest $request, JobTitle $jobTitle)
    {
        $jobTitle->title = $request->title;
        $jobTitle->short_title = $request->short_title;
        $jobTitle->save();

        toastr()->success('Updated Successfully');
        return redirect()->route('admin.job-title.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobTitle $jobTitle)
    {
        $jobTitle->delete();
    }
}
