<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Department;
use App\Models\EmployeeAssignment;
use App\Models\Facility;
use App\Models\JobTitle;
use App\Traits\ControllerTraits;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrganizationChartController extends Controller
{
    use ControllerTraits;

    private array $returnMessage = [
        'success' => false,
        'message' => 'An error occurred',
        'data' => [],
    ];
    private int $returnMessageStatus = Response::HTTP_BAD_REQUEST;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([

        ]);

        try {
            $companyChart = $this->companyChart();

            $this->returnMessage = [
                'success' => true,
                'message' => 'Company Org Chart retrieved successfully.',
                'data' => $companyChart
            ];

            $this->returnMessageStatus = Response::HTTP_OK;

        } catch (\Throwable $throwable) {
            $this->returnMessage = [
                'success' => false,
                'message' => 'Error occurred while fetching company org chart.',
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $request->validate([

        ]);

        try {
            $facility = Facility::findOrFail($id);

            $facilityChart = $this->facilityChart($facility->id);

            $this->returnMessage = [
                'success' => true,
                'message' => 'Facility Org Chart retrieved successfully.',
                'data' => $facilityChart
            ];

            $this->returnMessageStatus = Response::HTTP_OK;

        } catch (\Throwable $throwable) {
            $this->returnMessage = [
                'success' => false,
                'message' => 'Error occurred while fetching employee assignments.',
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function companyChart()
    {
        try {
            $company = Company::with(['facilities'])->first();

            return $this->prepareCompanyChart($company);
        } catch (\Throwable $throwable) {
            throw $throwable;
        }
    }

    private function prepareCompanyChart($company)
    {
        $preparedOrgChart = [
            'company' => [
                'name' => $company->name,
                'image' => $company->image,
                'phone' => $company->phone,
                'email' => $company->email,
                'website' => $company->website,
                'address' => $company->address,
                'suburb' => $company->suburb,
                'state' => $company->state,
                'postcode' => $company->postal_code,
                'country' => $company->country,
                'facilities' => []
            ],
        ];

        // Here sorting is done based on id so first entry has to be for head office which is being used as a facility entry
        foreach ($company->facilities as $facility) {
            $facilityData = $this->prepareFacilityDataForCompanyChart($facility);
            $preparedOrgChart['company']['facilities'][] = $facilityData;
        }

        return $preparedOrgChart;
    }

    private function prepareFacilityDataForCompanyChart($facility)
    {
        $preparedFacilityData = [
            'id' => $facility->id,
            'name' => $facility->name,
            'phone' => $facility->phone,
            'email' => $facility->email,
            'address' => $facility->address,
            'suburb' => $facility->suburb,
            'state' => $facility->state,
            'postCode' => $facility->postal_code,
            'country' => $facility->country,
            'estDate' => $facility->established_date
        ];

        return $preparedFacilityData;
    }

    public function facilityChart(string $facilityId)
    {
        try {
            $facility = Facility::with(['departments.jobTitles', 'departments.childrenDepartments'])
                ->findOrFail($facilityId);

            return $this->prepareFacilityChart($facility);
        } catch (\Throwable $throwable) {
            throw $throwable;
        }
    }

    private function prepareFacilityChart($facility)
    {
        $facilityEmployeeAssignments = EmployeeAssignment::with('employee')
            ->where('facility_id', $facility->id)
            ->get()
            ->groupBy('job_title_id'); // Group assignments by job title ID

        $preparedFacilityChart = [
            'facility' => [
                'id' => $facility->id,
                'name' => $facility->name,
                'phone' => $facility->phone,
                'email' => $facility->email,
                'address' => $facility->address,
                'suburb' => $facility->suburb,
                'state' => $facility->state,
                'postCode' => $facility->postal_code,
                'country' => $facility->country,
                'estDate' => $facility->established_date,
                'departments' => []
            ]
        ];

        foreach ($facility->departments as $department) {
            if (!$department->directory_flag) continue;

            $departmentData = $this->prepareDepartmentData($department, $facilityEmployeeAssignments);
            $preparedFacilityChart['facility']['departments'][] = $departmentData;
        }

        return $preparedFacilityChart;
    }

    private function prepareDepartmentData($department)
    {
        return [
            'id' => $department->id,
            'name' => $department->name,
            'shortName' => $department->short_name,
            'image' => $department->image,
            'jobTitles' => $department->jobTitles->map(function ($jobTitle) {
                if (!$jobTitle->directory_flag) return null;
                return $this->prepareJobTitleData($jobTitle); // No pre-grouped assignments needed
            })->filter(), // Filter out any null entries

            'childrenDepartments' => $this->getRecursiveChildrenDepartments($department),
        ];
    }


    private function prepareJobTitleData($jobTitle)
    {
        // Fetch employees specifically assigned to this job title
        $jobTitleAssignments = EmployeeAssignment::with('employee')
            ->where('job_title_id', $jobTitle->id)
            ->get();

        return [
            'id' => $jobTitle->id,
            'title' => $jobTitle->title,
            'shortTitle' => $jobTitle->short_title,
            'image' => $jobTitle->image,
            'employees' => $this->formatEmployees($jobTitleAssignments), // Direct employees of this job title
            'childrenJobTitles' => $this->getRecursiveChildrenJobTitles($jobTitle), // Fetch children with their employees
        ];
    }


    private function getRecursiveChildrenDepartments($department)
    {
        $childrenDepartments = [];

        foreach ($department->childrenDepartments as $childDepartment) {
            if (!$childDepartment->directory_flag) continue;

            $childData = [
                'id' => $childDepartment->id,
                'name' => $childDepartment->name,
                'shortName' => $childDepartment->short_name,
                'image' => $childDepartment->image,
                'jobTitles' => $childDepartment->jobTitles->map(function ($jobTitle) {
                    if (!$jobTitle->directory_flag) return null;
                    return $this->prepareJobTitleData($jobTitle);
                })->filter(), // Filter out any null entries
                'childrenDepartments' => $this->getRecursiveChildrenDepartments($childDepartment),
            ];

            $childrenDepartments[] = $childData;
        }

        return $childrenDepartments;
    }


    private function getRecursiveChildrenJobTitles($jobTitle)
    {
        $childrenJobTitles = [];

        foreach ($jobTitle->childrenJobTitles as $childJobTitle) {
            if (!$childJobTitle->directory_flag) continue;

            // Fetch employee assignments specifically for this child job title
            $childJobTitleAssignments = EmployeeAssignment::with('employee')
                ->where('job_title_id', $childJobTitle->id)
                ->get();

            $childData = [
                'id' => $childJobTitle->id,
                'title' => $childJobTitle->title,
                'shortTitle' => $childJobTitle->short_title,
                'image' => $childJobTitle->image,
                'employees' => $this->formatEmployees($childJobTitleAssignments), // Fetch employees here
                'childrenJobTitles' => $this->getRecursiveChildrenJobTitles($childJobTitle), // Recurse for deeper children
            ];

            $childrenJobTitles[] = $childData;
        }

        return $childrenJobTitles;
    }


    private function formatEmployees($assignments)
    {
        return $assignments->map(function ($assignment) {
            return [
                'id' => $assignment->employee->id,
                'firstName' => $assignment->employee->first_name,
                'middleName' => $assignment->employee->middle_name,
                'lastName' => $assignment->employee->last_name,
                'image' => $assignment->employee->image,
                'email' => $assignment->employee->email,
                'phone' => $assignment->employee->phone,
            ];
        })->toArray();
    }

}
