<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Facility;
use App\Models\JobTitle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Facility::factory()->count(15)->create();

        $facilities = Facility::all();
        $departments = Department::pluck('id')->toArray();

        foreach ($facilities as $facility) {
            // Randomly select job titles for the current department
            $randomDepartmentIds = collect($departments)->random(rand(1, count($departments)))->toArray();

            // Insert the job titles associated with this department into the pivot table
            foreach ($randomDepartmentIds as $departmentId) {
                DB::table('department_facility')->insert([
                    'facility_id' => $facility->id,
                    'department_id' => $departmentId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
