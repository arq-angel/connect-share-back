<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CompanyController extends Controller
{
    use ImageUploadTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company = Company::first();
        return view('admin.company.index', compact('company'));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'image' => ['max:5000', 'image'],
            'name' => ['required', 'string', 'max:200'],
            'address' => ['required', 'string', 'max:200'],
            'suburb' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:20'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:50', 'unique:companies,email,' . $id],
            'phone' => ['required', 'string', 'max:20'],
            'website' => ['required', 'url',],
            'industry' => ['required', 'string', 'max:100'],
            'size' => ['required', 'integer'],
            'established_date' => ['required', 'date'],
        ]);

        $company = Company::first();

        /** Handle file upload */
        if ($company && $company->logo) {
            $imagePath = $this->updateImage($request, 'image', 'uploads', $company->logo);
        }
        $imagePath = $this->uploadImage($request, 'image', 'uploads');

        $data = [
            'name' => $request->name,
            'address' => $request->address,
            'suburb' => $request->suburb,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'email' => $request->email,
            'phone' => $request->phone,
            'website' => $request->website,
            'industry' => $request->industry,
            'size' => $request->size,
            'established_date' => $request->established_date,
        ];

        // Only add the image to the $data array if a new image is uploaded
        if (!empty($imagePath)) {
            $data['image'] = $imagePath;
        }

        Company::updateOrCreate(
            ['id' => $id],
            $data
        );

        toastr()->success("Updated Successfully");
        return redirect()->route("admin.company.index");

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
