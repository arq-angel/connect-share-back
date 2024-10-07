<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Department;
use App\Models\JobTitle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::first();
        $jobTitles = JobTitle::pluck('id')->toArray();

        $departments = [
            ['name' => 'Nursing', 'short_name' => 'NSG'],
            ['name' => 'Administration', 'short_name' => 'ADM'],
            ['name' => 'Housekeeping', 'short_name' => 'HK'],
            ['name' => 'Maintenance', 'short_name' => 'MTN'],
            ['name' => 'Catering', 'short_name' => 'CAT'],
            ['name' => 'Physiotherapy', 'short_name' => 'PT'],
            ['name' => 'Occupational Therapy', 'short_name' => 'OT'],
            ['name' => 'Social Work', 'short_name' => 'SW'],
        ];

        foreach ($departments as $department) {
            DB::table('departments')->insert([
                'company_id' => $company->id,
                'name' => $department['name'],
                'short_name' => $department['short_name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $departments = Department::all();
        foreach ($departments as $department) {
            // Randomly select job titles for the current department
            $randomJobTitleIds = collect($jobTitles)->random(rand(1, count($jobTitles)))->toArray();

            // Insert the job titles associated with this department into the pivot table
            foreach ($randomJobTitleIds as $jobTitleId) {
                DB::table('department_job_title')->insert([
                    'department_id' => $department->id,
                    'job_title_id' => $jobTitleId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
