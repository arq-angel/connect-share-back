<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\EmployeeAssignment;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        // Retrieve the company with its related data
        $company = Company::with(['facilities.departments.jobTitles'])->first();
        if (!$company) {
            return response()->json(['message' => 'No company found'], 404);
        }

        // Prepare the organizational chart
        $orgChart = [
            'company' => $company->name,
            'facilities' => []
        ];

        // Get all employee assignments for the company
        $employeeAssignments = EmployeeAssignment::with('employee')
            ->where('company_id', $company->id)
            ->get()
            ->groupBy('job_title_id'); // Group by job title

        foreach ($company->facilities as $facility) {
            $facilityData = [
                'id' => $facility->id,
                'name' => $facility->name,
                'departments' => []
            ];

            foreach ($facility->departments as $department) {
                $departmentData = [
                    'id' => $department->id,
                    'name' => $department->name,
                    'jobTitles' => []
                ];

                foreach ($department->jobTitles as $jobTitle) {
                    $jobTitleData = [
                        'id' => $jobTitle->id,
                        'title' => $jobTitle->title,
                        'employees' => [] // Initialize employees array
                    ];

                    // Check if there are employee assignments for this job title
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

                    // Add job title data to the department
                    $departmentData['jobTitles'][] = $jobTitleData;
                }

                // Add department data to the facility
                $facilityData['departments'][] = $departmentData;
            }

            // Add facility data to the organizational chart
            $orgChart['facilities'][] = $facilityData;

            return response()->json($orgChart);
        }

        return response()->json($orgChart);

    }
}
