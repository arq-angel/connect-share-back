<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreProfileRequest;
use App\Http\Requests\V1\UpdateProfileRequest;
use App\Http\Resources\V1\ProfileResource;
use App\Models\Employee;
use App\Traits\ControllerTraits;
use App\Traits\ImageUploadTrait;

class ProfileController extends Controller
{
    use ControllerTraits, ImageUploadTrait;

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
    public function store(StoreProfileRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $returnMessage = [];

        try {
            $profile = Employee::findOrFail($id);

            $returnMessage = [
                'success' => true,
                'message' => 'Profile details retrieved successfully.',
                'data' => new ProfileResource($profile),
            ];
        } catch (\Throwable $throwable) {
            $returnMessage = [
                'success' => false,
                'message' => 'Error occurred while fetching profile details.',
                'data' => ''
            ];

            if ($this->debuggable()) {
                $returnMessage['debug'] = $throwable->getMessage();
            }
        }

        return response()->json($returnMessage, 200);
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
    public function update(UpdateProfileRequest $request, string $id)
    {
        $returnMessage = [];

        try {
            $profile = Employee::findOrFail($id);

            $validated = $request->validated();

            $imagePath = $this->updateImage($request, 'image', 'uploads', $profile->image);
            $validated['image'] = $imagePath;

            $profile->update($validated);

            $returnMessage = [
                'success' => true,
                'message' => 'Profile details updated successfully.',
                'data' => [
                    'profile' => new ProfileResource($profile),
                ]
            ];
        } catch (\Throwable $throwable) {
            $returnMessage = [
                'success' => false,
                'message' => 'Error occurred while updating profile details.',
                'data' => ''
            ];

            if ($this->debuggable()) {
                $returnMessage['debug'] = $throwable->getMessage();
            }
        }

        return response()->json($returnMessage, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
