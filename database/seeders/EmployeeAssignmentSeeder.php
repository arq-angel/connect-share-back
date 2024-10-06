<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Facility;
use App\Models\JobTitle;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::all();
        $facilities = Facility::all();
        $employees = Employee::all();
        $departments = Department::all();
        $jobTitles = JobTitle::all();
        $users = User::all();

        foreach ($companies as $company) {
            foreach ($employees as $employee) {
                // Determine the number of facilities the employee will be assigned to:
                // 90% chance for 1 facility, 10% chance for multiple facilities
                $facilityCount = (rand(1, 100) <= 99) ? 1 : rand(2, $facilities->count());

                // Select a random set of facilities (for multi-facility assignments)
                $assignedFacilities = $facilities->random($facilityCount);

                foreach ($assignedFacilities as $facility) {
                    // Assign 99% of employees 1 job entry, 1% get 2 entries
                    $entriesCount = (rand(1, 100) <= 99) ? 1 : 2;

                    for ($i = 0; $i < $entriesCount; $i++) {
                        DB::table('employee_assignments')->insert([
                            'company_id' => $company->id,
                            'facility_id' => $facility->id,
                            'employee_id' => $employee->id,
                            'job_title_id' => $jobTitles->random()->id,  // Pick a random job title
                            'department_id' => $departments->random()->id,  // Pick a random department
                            'hire_date' => now()->subDays(rand(0, 365)),  // Random hire date within the last year
                            'start_date' => now()->subDays(rand(0, 365)), // Random start date within the last year
                            'end_date' => (rand(1, 100) <= 10) ? now()->addDays(rand(0, 30)) : null, // Random end date for 10% of employees
                            'system_user_id' => substr(uniqid(), -6), // Generate a random system user ID
                            'contract_type' => (rand(1, 100) <= 80) ? 'permanent' : 'contract', // 80% permanent, 20% contract
                            'status' => ['active', 'on_leave', 'terminated'][rand(0, 2)], // Randomly select status
                            'created_by' => (rand(1, 100) <= 50) ? $users->random()->id : null, // 50% chance to assign a user
                            'updated_by' => (rand(1, 100) <= 50) ? $users->random()->id : null, // 50% chance to assign a user
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }
}
