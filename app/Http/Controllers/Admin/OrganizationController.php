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
        return view('admin.organization.index', compact('facilities', ));
    }

    public function company(Request $request)
    {
        $company = Company::with(['facilities.departments.jobTitles'])->first();

        if (!$company) {
            return response()->json(['message' => 'No company found'], 404);
        }

        return response()->json($this->prepareOrgChart($company));
    }

    private function prepareOrgChart($company)
    {
        $orgChart = [
            'company' => [
                'name' => $company->name,
                'phone' => $company->phone,
                'email' => $company->email,
                'facilities' => []
            ],
        ];

        $employeeAssignments = $this->getEmployeeAssignments($company->id);

        foreach ($company->facilities as $facility) {
            $facilityData = $this->prepareFacilityData($facility, $employeeAssignments);
            $orgChart['company']['facilities'][] = $facilityData;
        }

        return $orgChart;
    }

    private function prepareFacilityData($facility, $employeeAssignments)
    {
        $facilityData = [
            'id' => $facility->id,
            'name' => $facility->name,
            'phone' => $facility->phone,
            'email' => $facility->email,
            'departments' => []
        ];

        foreach ($facility->departments as $department) {
            if (!$department->directory_flag) continue;

            $departmentData = $this->prepareDepartmentData($department, $employeeAssignments);
            $facilityData['departments'][] = $departmentData;
        }

        return $facilityData;
    }

    private function prepareDepartmentData($department, $employeeAssignments)
    {
        $departmentData = [
            'id' => $department->id,
            'name' => $department->name,
            'short_name' => $department->short_name,
            'jobTitles' => [],
            'childrenDepartments' => $this->getRecursiveChildrenDepartments($department),
            'siblingDepartments' => $department->siblingDepartments()
        ];

        foreach ($department->jobTitles as $jobTitle) {
            if (!$jobTitle->directory_flag) continue;

            $jobTitleData = $this->prepareJobTitleData($jobTitle, $employeeAssignments);
            $departmentData['jobTitles'][] = $jobTitleData;
        }

        return $departmentData;
    }

    private function prepareJobTitleData($jobTitle, $employeeAssignments)
    {
        $jobTitleData = [
            'id' => $jobTitle->id,
            'title' => $jobTitle->title,
            'short_title' => $jobTitle->short_title,
            'employees' => [],
            'childrenJobTitles' => $this->getRecursiveChildrenJobTitles($jobTitle),
            'siblingJobTitles' => $jobTitle->siblingJobTitles()
        ];

        if ($employeeAssignments->has($jobTitle->id)) {
            foreach ($employeeAssignments[$jobTitle->id] as $assignment) {
                $employeeData = [
                    'id' => $assignment->employee->id,
                    'first_name' => $assignment->employee->first_name,
                    'middle_name' => $assignment->employee->middle_name,
                    'last_name' => $assignment->employee->last_name,
                ];
                $jobTitleData['employees'][] = $employeeData;
            }
        }

        return $jobTitleData;
    }

    private function getEmployeeAssignments($companyId)
    {
        return EmployeeAssignment::with('employee')
            ->where('company_id', $companyId)
            ->get()
            ->groupBy('job_title_id');
    }

    private function getRecursiveChildrenDepartments($department)
    {
        $childrenDepartments = $department->childrenDepartments();

        foreach ($childrenDepartments as &$childDepartment) {
            $childDepartment['childrenDepartments'] = $this->getRecursiveChildrenDepartments($childDepartment);
        }

        return $childrenDepartments;
    }

    private function getRecursiveChildrenJobTitles($jobTitle)
    {
        $childrenJobTitles = $jobTitle->childrenJobTitles();

        foreach ($childrenJobTitles as &$childJobTitle) {
            $childJobTitle['childrenJobTitles'] = $this->getRecursiveChildrenJobTitles($childJobTitle);
        }

        return $childrenJobTitles;
    }

    public function facility(string $facilityId, Request $request)
    {
        $facility = Facility::findOrFail($facilityId);

        if (!$facility->company) {
            return response()->json(['message' => 'Facility not associated with any company'], 404);
        }

        return response()->json($this->prepareFacilityChart($facility->company, $facility));
    }

    private function prepareFacilityChart($company, $facility)
    {
        $orgChart = [
            'company' => [
                'name' => $company->name,
                'phone' => $company->phone,
                'email' => $company->email,
                'facilities' => [
                    'id' => $facility->id,
                    'name' => $facility->name,
                    'phone' => $facility->phone,
                    'email' => $facility->email,
                    'departments' => []
                ]
            ]
        ];

        $employeeAssignments = $this->getEmployeeAssignments($facility->company_id);

        foreach ($facility->departments as $department) {
            if (!$department->directory_flag) continue;

            $departmentData = $this->prepareDepartmentData($department, $employeeAssignments);
            $orgChart['company']['facilities']['departments'][] = $departmentData;
        }

        return $orgChart;
    }

    public function department(string $facilityId, string $departmentId, Request $request)
    {
        $facility = Facility::findOrFail($facilityId);
        $department = Department::with('jobTitles')->findOrFail($departmentId);

        if (!$facility->departments()->where('department_id', $department->id)->exists()) {
            return response()->json(['message' => 'Department not associated with the specified facility'], 404);
        }

        if (!$department->directory_flag) {
            return response()->json(['message' => 'Department not available for display'], 404);
        }

        return response()->json($this->prepareDepartmentChart($facility->company, $facility, $department));
    }

    private function prepareDepartmentChart($company, $facility, $department)
    {
        $departmentChart = [
            'company' => [
                'name' => $company->name,
                'phone' => $company->phone,
                'email' => $company->email,
                'facilities' => [
                    'id' => $facility->id,
                    'name' => $facility->name,
                    'email' => $facility->email,
                    'phone' => $facility->phone,
                    'departments' => [
                        'id' => $department->id,
                        'name' => $department->name,
                        'short_name' => $department->short_name,
                        'jobTitles' => []
                    ]
                ]
            ]
        ];

        $employeeAssignments = $this->getEmployeeAssignments($facility->company_id);

        foreach ($department->jobTitles as $jobTitle) {
            if (!$jobTitle->directory_flag) continue;

            $jobTitleData = $this->prepareJobTitleData($jobTitle, $employeeAssignments);
            $departmentChart['company']['facilities']['departments']['jobTitles'][] = $jobTitleData;
        }

        return $departmentChart;
    }

    public function jobTitle(string $facilityId, string $departmentId, string $jobTitleId, Request $request)
    {
        $facility = Facility::findOrFail($facilityId);
        $department = Department::with('jobTitles')->findOrFail($departmentId);
        $jobTitle = JobTitle::findOrFail($jobTitleId);

        if (!$facility->departments()->where('department_id', $department->id)->exists()) {
            return response()->json(['message' => 'Department not associated with the specified facility'], 404);
        }

        if (!$department->jobTitles()->where('job_title_id', $jobTitle->id)->exists()) {
            return response()->json(['message' => 'Job title not associated with the specified department'], 404);
        }

        if (!$jobTitle->directory_flag) {
            return response()->json(['message' => 'Job title not available for display'], 404);
        }

        return response()->json($this->prepareJobTitleChart($facility->company, $facility, $department, $jobTitle));
    }

    private function prepareJobTitleChart($company, $facility, $department, $jobTitle)
    {
        $jobTitleChart = [
            'company' => [
                'name' => $company->name,
                'phone' => $company->phone,
                'email' => $company->email,
                'facilities' => [
                    'id' => $facility->id,
                    'name' => $facility->name,
                    'phone' => $facility->phone,
                    'email' => $facility->email,
                    'departments' => [
                        'id' => $department->id,
                        'name' => $department->name,
                        'short_name' => $department->short_name,
                        'jobTitles' => [
                            'id' => $jobTitle->id,
                            'title' => $jobTitle->title,
                            'short_title' => $jobTitle->short_title,
                            'employees' => []
                        ]
                    ]
                ]
            ]
        ];

        $employeeAssignments = EmployeeAssignment::with('employee')
            ->where('company_id', $facility->company_id)
            ->where('job_title_id', $jobTitle->id)
            ->get();

        foreach ($employeeAssignments as $assignment) {
            $employeeData = [
                'id' => $assignment->employee->id,
                'first_name' => $assignment->employee->first_name,
                'middle_name' => $assignment->employee->middle_name,
                'last_name' => $assignment->employee->last_name,
                'phone' => $assignment->employee->phone,
                'email' => $assignment->employee->email,
            ];
            $jobTitleChart['company']['facilities']['departments']['jobTitles']['employees'][] = $employeeData;
        }

        return $jobTitleChart;
    }
}
