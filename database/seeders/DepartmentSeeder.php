<?php

namespace Database\Seeders;

use App\Models\Company;
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
    }
}
