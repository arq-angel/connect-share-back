<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\FacilityDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFacilityRequest;
use App\Http\Requests\Admin\UpdateFacilityRequest;
use App\Models\Company;
use App\Models\Facility;
use App\Traits\ImageUploadTrait;

class FacilityController extends Controller
{
    use ImageUploadTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(FacilityDataTable $dataTable)
    {
        return $dataTable->render('admin.facility.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $company = Company::first();
        return view('admin.facility.create', compact('company'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFacilityRequest $request)
    {

        $facility = new Facility();

        if ($request->hasFile('image')) {
            $imagePath = $this->uploadImage($request, 'image', 'uploads');
            $facility->image = $imagePath;
        }
        $facility->name = $request->name;
        $facility->company_id = $request->company_id;
        $facility->address = $request->address;
        $facility->suburb = $request->suburb;
        $facility->state = $request->state;
        $facility->postal_code = $request->postal_code;
        $facility->country = $request->country;
        $facility->email = $request->email;
        $facility->phone = $request->phone;
        $facility->website = $request->website;
        $facility->size = $request->size;
        $facility->established_date = $request->established_date;
        $facility->save();

        toastr()->success('Created Successfully');
        return redirect()->route('admin.facility.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Facility $facility)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Facility $facility)
    {
        $company = Company::first();
        return view('admin.facility.edit', compact('facility', 'company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFacilityRequest $request, Facility $facility)
    {
        if ($request->hasFile('image')) {
            $imagePath = $this->updateImage($request, 'image', 'uploads', $facility->image);
            $facility->image = $imagePath;
        }

        $facility->name = $request->name;
        $facility->company_id = $request->company_id;
        $facility->address = $request->address;
        $facility->suburb = $request->suburb;
        $facility->state = $request->state;
        $facility->postal_code = $request->postal_code;
        $facility->country = $request->country;
        $facility->email = $request->email;
        $facility->phone = $request->phone;
        $facility->website = $request->website;
        $facility->size = $request->size;
        $facility->established_date = $request->established_date;
        $facility->save();

        toastr()->success('Updated Successfully');
        return redirect()->route('admin.facility.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Facility $facility)
    {
        deleteFileIfExists($facility->image);
        $facility->delete();
    }
}
