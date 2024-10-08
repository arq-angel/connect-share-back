<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreProfileRequest;
use App\Http\Requests\Api\V1\UpdateProfileRequest;
use App\Http\Resources\V1\ProfileResource;
use App\Traits\ControllerTraits;
use App\Traits\ImageUploadTrait;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    use ControllerTraits, ImageUploadTrait;

    private array $returnMessage = [
        'success' => false,
        'message' => 'An error occurred',
        'data' => [],
    ];
    private int $returnMessageStatus = Response::HTTP_BAD_REQUEST;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        try {
            // only access the user using bearer token associated user from sanctum middleware
            $profile = Auth::guard('sanctum')->user();

            $this->returnMessage = [
                'success' => true,
                'message' => 'Profile details retrieved successfully.',
                'data' => new ProfileResource($profile),
            ];
            $this->returnMessageStatus = Response::HTTP_OK;
        } catch (\Throwable $throwable) {
            $this->returnMessage = [
                'success' => false,
                'message' => 'Error occurred while fetching profile details.',
                'data' => []
            ];
            if ($this->debuggable()) {
                $this->returnMessage['debug'] = $throwable->getMessage();
            }
            $this->returnMessageStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($this->returnMessage, $this->returnMessageStatus);
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
    public function show(String $id)
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
    public function update(UpdateProfileRequest $request)
    {

        try {
            $profile = Auth::guard('sanctum')->user();

            $validated = $request->validated();

            if ($validated['image']) {
                $imagePath = $this->updateImage($request, 'image', 'uploads', $profile->image);
                $profile->image = $imagePath;
            }

            $profile->first_name = $validated['first_name'];
            $profile->middle_name = $validated['middle_name'];
            $profile->last_name = $validated['last_name'];
            $profile->email = $validated['email'];
            $profile->phone = $validated['phone'];
            $profile->date_of_birth = $validated['date_of_birth'];
            $profile->gender = $validated['gender'];
            $profile->address = $validated['address'];
            $profile->suburb = $validated['suburb'];
            $profile->state = $validated['state'];
            $profile->postal_code = $validated['postal_code'];
            $profile->country = $validated['country'];
            $profile->update();

            $this->returnMessage = [
                'success' => true,
                'message' => 'Profile details updated successfully.',
                'data' => [
                    'profile' => new ProfileResource($profile),
                ]
            ];
            $this->returnMessageStatus = Response::HTTP_OK;
        } catch (\Throwable $throwable) {
            $this->returnMessage = [
                'success' => false,
                'message' => 'Error occurred while updating profile details.',
                'data' => []
            ];
            if ($this->debuggable()) {
                $this->returnMessage['debug'] = $throwable->getMessage();
            }
            $this->returnMessageStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($this->returnMessage, $this->returnMessageStatus);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
