<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\EmployeeDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEmployeeRequest;
use App\Http\Requests\Admin\UpdateEmployeeRequest;
use App\Models\Company;
use App\Models\Employee;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    use ImageUploadTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(EmployeeDataTable $dataTable)
    {
        return $dataTable->render('admin.employee.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $company = Company::first();
        $countries = getCountryItems();
        $genders = getGenders();
        return view('admin.employee.create', compact('company', 'countries', 'genders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        $employee = new Employee();

        if ($request->hasFile('image')) {
            $imagePath = $this->uploadImage($request, 'image', 'uploads');
            $employee->image = $imagePath;
        }
        $employee->first_name = $request->first_name;
        $employee->middle_name = $request->middle_name;
        $employee->last_name = $request->last_name;
        $employee->gender = $request->gender;
        $employee->date_of_birth = $request->date_of_birth;
        $employee->company_id = $request->company_id;
        $employee->email = $request->email;
        $employee->phone = $request->phone;
        $employee->address = $request->address;
        $employee->suburb = $request->suburb;
        $employee->state = $request->state;
        $employee->postal_code = $request->postal_code;
        $employee->country = $request->country;
        $employee->save();

        toastr()->success('Created successfully');
        return redirect()->route('admin.employee.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $company = Company::first();
        $countries = getCountryItems();
        $genders = getGenders();
        return view('admin.employee.edit', compact('employee', 'company', 'countries', 'genders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        if ($request->hasFile('image')) {
            $imagePath = $this->updateImage($request, 'image', 'uploads', $employee->image);
            $employee->image = $imagePath;
        }
        $employee->first_name = $request->first_name;
        $employee->middle_name = $request->middle_name;
        $employee->last_name = $request->last_name;
        $employee->gender = $request->gender;
        $employee->date_of_birth = $request->date_of_birth;
        $employee->company_id = $request->company_id;
        $employee->email = $request->email;
        $employee->phone = $request->phone;
        $employee->address = $request->address;
        $employee->suburb = $request->suburb;
        $employee->state = $request->state;
        $employee->postal_code = $request->postal_code;
        $employee->country = $request->country;
        $employee->save();

        toastr()->success('Updated successfully');
        return redirect()->route('admin.employee.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        deleteFileIfExists($employee->image);
        $employee->delete();
    }
}
