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
use Illuminate\Support\Facades\Cache;
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
            'refreshCache' => ['nullable', 'boolean'],
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
     * Display the specified facility chart.
     */
    public function show(string $id, Request $request)
    {
        $request->validate([
            'refreshCache' => ['nullable', 'boolean'],
        ]);

        try {
            $refreshCache = $request->query('refreshCache', false);
            $facility = Facility::findOrFail($id);

            $facilityChart = $this->facilityChart($facility->id, $refreshCache);

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
                'id' => $company->id,
                'name' => $company->name,
                'image' => $company->image,
                'phone' => $company->phone,
                'email' => $company->email,
                'website' => $company->website,
                'address' => $company->address,
                'suburb' => $company->suburb,
                'state' => $company->state,
                'postCode' => $company->postal_code,
                'country' => $company->country,
                'facilities' => []
            ],
        ];

        foreach ($company->facilities as $facility) {
            $facilityData = $this->prepareFacilityDataForCompanyChart($facility);
            $preparedOrgChart['company']['facilities'][] = $facilityData;
        }

        return $preparedOrgChart;
    }

    private function prepareFacilityDataForCompanyChart($facility)
    {
        return [
            'id' => $facility->id,
            'name' => $facility->name,
            'phone' => $facility->phone,
            'email' => $facility->email,
            'website' => $facility->website,
            'address' => $facility->address,
            'suburb' => $facility->suburb,
            'state' => $facility->state,
            'postCode' => $facility->postal_code,
            'country' => $facility->country,
            'estDate' => $facility->established_date
        ];
    }

    public function facilityChart(string $facilityId, $refreshCache = false)
    {
        if ($refreshCache) {
            Cache::forget("facility_chart_{$facilityId}");
        }

        // Cache the entire organization chart for the facility for an hour to improve performance
        return Cache::remember("facility_chart_{$facilityId}", 60 * 60, function () use ($facilityId) {
            try {
                $facility = Facility::with([
                    'departments.jobTitles.childrenJobTitles',
                    'departments.childrenDepartments'
                ])->findOrFail($facilityId);

                $facilityEmployeeAssignments = EmployeeAssignment::with('employee')
                    ->where('facility_id', $facility->id) // Filter by facility_id
                    ->get()
                    ->groupBy('job_title_id');

                return $this->prepareFacilityChart($facility, $facilityEmployeeAssignments);
            } catch (\Throwable $throwable) {
                throw $throwable;
            }
        });
    }

    private function prepareFacilityChart($facility, $facilityEmployeeAssignments)
    {
        return [
            'facility' => [
                'id' => $facility->id,
                'name' => $facility->name,
                'phone' => $facility->phone,
                'email' => $facility->email,
                'website' => $facility->website,
                'address' => $facility->address,
                'suburb' => $facility->suburb,
                'state' => $facility->state,
                'postCode' => $facility->postal_code,
                'country' => $facility->country,
                'estDate' => $facility->established_date,
                'departments' => $facility->departments->map(function ($department) use ($facilityEmployeeAssignments) {
                    if (!$department->directory_flag) return null;
                    return $this->prepareDepartmentData($department, $facilityEmployeeAssignments);
                })->filter()->values()->all()
            ]
        ];
    }

    private function prepareDepartmentData($department, $facilityEmployeeAssignments)
    {
        return [
            'id' => $department->id,
            'name' => $department->name,
            'shortName' => $department->short_name,
            'image' => $department->image,
            'jobTitles' => $department->jobTitles->map(function ($jobTitle) use ($facilityEmployeeAssignments) {
                if (!$jobTitle->directory_flag) return null;
                return $this->prepareJobTitleData($jobTitle, $facilityEmployeeAssignments);
            })->filter()->values()->all(),
            'childrenDepartments' => $this->getRecursiveChildrenDepartments($department, $facilityEmployeeAssignments),
        ];
    }

    private function prepareJobTitleData($jobTitle, $facilityEmployeeAssignments)
    {
        $jobTitleAssignments = $facilityEmployeeAssignments->get($jobTitle->id, collect());

        return [
            'id' => $jobTitle->id,
            'title' => $jobTitle->title,
            'shortTitle' => $jobTitle->short_title,
            'image' => $jobTitle->image,
            'employees' => $this->formatEmployees($jobTitleAssignments),
            'childrenJobTitles' => $this->getRecursiveChildrenJobTitles($jobTitle, $facilityEmployeeAssignments),
        ];
    }

    private function getRecursiveChildrenDepartments($department, $facilityEmployeeAssignments)
    {
        return $department->childrenDepartments->map(function ($childDepartment) use ($facilityEmployeeAssignments) {
            if (!$childDepartment->directory_flag) return null;
            return [
                'id' => $childDepartment->id,
                'name' => $childDepartment->name,
                'shortName' => $childDepartment->short_name,
                'image' => $childDepartment->image,
                'jobTitles' => $childDepartment->jobTitles->map(function ($jobTitle) use ($facilityEmployeeAssignments) {
                    if (!$jobTitle->directory_flag) return null;
                    return $this->prepareJobTitleData($jobTitle, $facilityEmployeeAssignments);
                })->filter()->values()->all(),
                'childrenDepartments' => $this->getRecursiveChildrenDepartments($childDepartment, $facilityEmployeeAssignments),
            ];
        })->filter()->values()->all();
    }

    private function getRecursiveChildrenJobTitles($jobTitle, $facilityEmployeeAssignments)
    {
        return $jobTitle->childrenJobTitles->map(function ($childJobTitle) use ($facilityEmployeeAssignments) {
            if (!$childJobTitle->directory_flag) return null;

            $childJobTitleAssignments = $facilityEmployeeAssignments->get($childJobTitle->id, collect());

            return [
                'id' => $childJobTitle->id,
                'title' => $childJobTitle->title,
                'shortTitle' => $childJobTitle->short_title,
                'image' => $childJobTitle->image,
                'employees' => $this->formatEmployees($childJobTitleAssignments),
                'childrenJobTitles' => $this->getRecursiveChildrenJobTitles($childJobTitle, $facilityEmployeeAssignments),
            ];
        })->filter()->values()->all();
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
