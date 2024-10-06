<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEmployeeAssignmentRequest;
use App\Http\Requests\Admin\UpdateEmployeeAssignmentRequest;
use App\Models\EmployeeAssignment;

class EmployeeAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeAssignmentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeAssignment $employeeAssignment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeAssignment $employeeAssignment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeAssignmentRequest $request, EmployeeAssignment $employeeAssignment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeAssignment $employeeAssignment)
    {
        //
    }
}