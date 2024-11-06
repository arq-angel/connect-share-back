<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Department;
use App\Models\EmployeeAssignment;
use App\Models\Facility;
use App\Models\JobTitle;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index(Request $request)
    {
        $facilities = Facility::all();
        return view('admin.organization.index', compact('facilities'));
    }

    public function company(Request $request)
    {
        $company = Company::with(['facilities.departments.jobTitles.childrenJobTitles'])->first();

        if (!$company) {
            return $this->handleError("No Company Found");
        }

        $companyChart = $this->prepareOrgChart($company);

        return response()->json([
            'success' => true,
            'message' => 'Company Org Chart retrieved successfully.',
            'data' => $companyChart
        ]);
    }

    private function prepareOrgChart($company)
    {
        $orgChart = [
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

        foreach ($company->facilities as $facility) {
            $employeeAssignments = $this->getEmployeeAssignmentsByFacility($facility->id);
            $facilityData = $this->prepareFacilityData($facility, $employeeAssignments);
            $orgChart['company']['facilities'][] = $facilityData;
        }

        return $orgChart;
    }

    public function facility(Facility $facility, Request $request)
    {
        $facility->load(['departments.jobTitles.childrenJobTitles']);
        $employeeAssignments = $this->getEmployeeAssignmentsByFacility($facility->id);

        $facilityChart = [
            'company' => $this->formatCompanyData($facility->company),
            'facility' => $this->prepareFacilityData($facility, $employeeAssignments)
        ];

        return response()->json([
            'success' => true,
            'message' => 'Facility Org Chart retrieved successfully.',
            'data' => $facilityChart
        ]);
    }

    public function department(Facility $facility, Department $department, Request $request)
    {
        $department->load(['jobTitles.childrenJobTitles']);

        if (!$facility->departments()->where('department_id', $department->id)->exists()) {
            return $this->handleError("Department not associated with the specified facility");
        }

        $employeeAssignments = $this->getEmployeeAssignmentsByFacility($facility->id);

        $departmentChart = [
            'company' => $this->formatCompanyData($facility->company),
            'facility' => [
                'id' => $facility->id,
                'name' => $facility->name,
                'phone' => $facility->phone,
                'email' => $facility->email,
                'departments' => [
                    $this->prepareDepartmentData($department, $employeeAssignments),
                ]
            ]
        ];

        return response()->json([
            'success' => true,
            'message' => 'Department Org Chart retrieved successfully.',
            'data' => $departmentChart
        ]);
    }

    public function jobTitle(Facility $facility, Department $department, JobTitle $jobTitle, Request $request)
    {
        $jobTitle->load('childrenJobTitles');

        if (!$facility->departments()->where('department_id', $department->id)->exists() ||
            !$department->jobTitles()->where('job_title_id', $jobTitle->id)->exists()) {
            return $this->handleError("Job title not associated with the specified department and facility");
        }

        $employeeAssignments = $this->getEmployeeAssignmentsByFacility($facility->id);

        $jobTitleChart = [
            'company' => $this->formatCompanyData($facility->company),
            'facility' => [
                'id' => $facility->id,
                'name' => $facility->name,
                'phone' => $facility->phone,
                'email' => $facility->email,
                'departments' => [
                    'id' => $department->id,
                    'name' => $department->name,
                    'shortName' => $department->short_name,
                    'jobTitles' => [
                        $this->prepareJobTitleData($jobTitle, $employeeAssignments)
                    ]
                ]
            ]
        ];

        return response()->json([
            'success' => true,
            'message' => 'Job Title Org Chart retrieved successfully.',
            'data' => $jobTitleChart
        ]);
    }

    private function prepareFacilityData($facility, $employeeAssignments)
    {
        return [
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
            'departments' => $this->prepareDepartments($facility->departments, $employeeAssignments)
        ];
    }

    private function prepareDepartments($departments, $employeeAssignments)
    {
        return $departments->filter(fn($department) => $department->directory_flag)
            ->map(fn($department) => $this->prepareDepartmentData($department, $employeeAssignments))
            ->values()
            ->toArray();
    }

    private function prepareDepartmentData($department, $employeeAssignments)
    {
        return [
            'id' => $department->id,
            'name' => $department->name,
            'shortName' => $department->short_name,
            'jobTitles' => $this->prepareJobTitles($department->jobTitles, $employeeAssignments),
            'childrenDepartments' => $this->getRecursiveChildrenDepartments($department, $employeeAssignments),
        ];
    }

    private function prepareJobTitles($jobTitles, $employeeAssignments)
    {
        return $jobTitles->filter(fn($jobTitle) => $jobTitle->directory_flag)
            ->map(fn($jobTitle) => $this->prepareJobTitleData($jobTitle, $employeeAssignments))
            ->values()
            ->toArray();
    }

    private function prepareJobTitleData($jobTitle, $employeeAssignments)
    {
        return [
            'id' => $jobTitle->id,
            'title' => $jobTitle->title,
            'shortTitle' => $jobTitle->short_title,
            'employees' => $this->getEmployeesForJobTitle($jobTitle->id, $employeeAssignments),
            'childrenJobTitles' => $this->getRecursiveChildrenJobTitles($jobTitle, $employeeAssignments),
        ];
    }

    private function getEmployeesForJobTitle($jobTitleId, $employeeAssignments)
    {
        return $employeeAssignments->get($jobTitleId, collect())->map(function ($assignment) {
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

    private function getEmployeeAssignmentsByFacility($facilityId)
    {
        return EmployeeAssignment::with('employee')
            ->where('facility_id', $facilityId)
            ->get()
            ->groupBy('job_title_id');
    }

    private function getRecursiveChildrenDepartments($department, $employeeAssignments)
    {
        return $department->childrenDepartments->map(function ($childDepartment) use ($employeeAssignments) {
            return [
                'id' => $childDepartment->id,
                'name' => $childDepartment->name,
                'shortName' => $childDepartment->short_name,
                'jobTitles' => $childDepartment->jobTitles->map(function ($jobTitle) use ($employeeAssignments) {
                    if (!$jobTitle->directory_flag) return null;
                    return $this->prepareJobTitleData($jobTitle, $employeeAssignments);
                })->filter()->values()->all(),
                'childrenDepartments' => $this->getRecursiveChildrenDepartments($childDepartment, $employeeAssignments),
            ];
        })->toArray();
    }

    private function getRecursiveChildrenJobTitles($jobTitle, $facilityEmployeeAssignments)
    {
        return $jobTitle->childrenJobTitles->map(function ($childJobTitle) use ($facilityEmployeeAssignments) {
            if (!$childJobTitle->directory_flag) return null;

            // Fetch employees specifically for this nested child job title
            $childJobTitleAssignments = $facilityEmployeeAssignments->get($childJobTitle->id, collect());

            return [
                'id' => $childJobTitle->id,
                'title' => $childJobTitle->title,
                'shortTitle' => $childJobTitle->short_title,
                'image' => $childJobTitle->image,
                'employees' => $this->formatEmployees($childJobTitleAssignments), // Include employees here
                'childrenJobTitles' => $this->getRecursiveChildrenJobTitles($childJobTitle, $facilityEmployeeAssignments), // Recursively fetch deeper levels
            ];
        })->filter()->values()->all();
    }


    private function handleError($message)
    {
        toastr()->error($message);
        return redirect()->back();
    }

    private function formatCompanyData($company)
    {
        return [
            'id' => $company->id,
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
        ];
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
