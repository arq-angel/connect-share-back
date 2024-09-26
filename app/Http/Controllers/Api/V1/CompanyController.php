<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreCompanyRequest;
use App\Http\Requests\V1\UpdateCompanyRequest;
use App\Http\Resources\V1\CompanyResource;
use App\Models\Company;
use App\Traits\ControllerTraits;

class CompanyController extends Controller
{
    use ControllerTraits;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $returnMessage = [];

        try {
            $company = Company::first();
            if (!$company) {
                throw new \Exception('No company found.');
            }
            $companyResource = new CompanyResource($company);

            $returnMessage = [
                'success' => true,
                'message' => 'Company retrieved successfully.',
                'data' =>  $companyResource,
            ];
        } catch (\Throwable $throwable) {
            $returnMessage = [
                'success' => false,
                'message' => 'Error occurred while fetching company',
                'data' => ''
            ];

            if ($this->debuggable()) {
                $returnMessage['debug'] = $throwable->getMessage();
            }
        }

        return response()->json($returnMessage, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        //
    }
}
