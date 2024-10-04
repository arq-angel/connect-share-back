<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Facility;
use App\Models\JobTitle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacilityEmployeeSeeder extends Seeder
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
                        DB::table('facility_employees')->insert([
                            'facility_id' => $facility->id,
                            'employee_id' => $employee->id,
                            'job_title_id' => $jobTitles->random()->id,  // Pick a random job title
                            'department_id' => $departments->random()->id,  // Pick a random department
                            'hire_date' => now()->subDays(rand(0, 365)),  // Random hire date within the last year
                            'system_user_id' => substr(uniqid(), -6), // Generate a random system user ID
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }
}
