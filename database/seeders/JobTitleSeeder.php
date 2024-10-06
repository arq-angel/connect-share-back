<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::first();
        $jobTitles = [
            ['title' => 'Registered Nurse', 'short_title' => 'RN'],
            ['title' => 'Enrolled Nurse', 'short_title' => 'EN'],
            ['title' => 'Aged Care Worker', 'short_title' => 'ACW'],
            ['title' => 'Personal Care Assistant', 'short_title' => 'PCA'],
            ['title' => 'Lifestyle Coordinator', 'short_title' => 'LC'],
            ['title' => 'Clinical Care Manager', 'short_title' => 'CCM'],
            ['title' => 'Facility Manager', 'short_title' => 'FM'],
            ['title' => 'Care Coordinator', 'short_title' => 'CC'],
            ['title' => 'Allied Health Assistant', 'short_title' => 'AHA'],
            ['title' => 'Physiotherapist', 'short_title' => 'PT'],
            ['title' => 'Occupational Therapist', 'short_title' => 'OT'],
            ['title' => 'Dietitian', 'short_title' => 'Dietitian'],
            ['title' => 'Social Worker', 'short_title' => 'SW'],
            ['title' => 'Chef', 'short_title' => 'Chef'],
            ['title' => 'Kitchen Assistant', 'short_title' => 'KA'],
            ['title' => 'Laundry Assistant', 'short_title' => 'LA'],
            ['title' => 'Maintenance Officer', 'short_title' => 'MO'],
            ['title' => 'Receptionist', 'short_title' => 'Receptionist'],
            ['title' => 'Administration Assistant', 'short_title' => 'AA'],
            ['title' => 'Quality Manager', 'short_title' => 'QM'],
        ];

        foreach ($jobTitles as $job) {
            DB::table('job_titles')->insert([
                'company_id' => $company->id,
                'title' => $job['title'],
                'short_title' => $job['short_title'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
