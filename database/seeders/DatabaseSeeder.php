<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Employee;
use App\Models\EmployeeAssignment;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // test admin
        User::factory()->create([
            'name' => 'Test Admin User',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ]);

        // create the company
        Company::factory()->create([
            'name' => 'Thompson Health Care',
            'address' => '14 John Street',
            'suburb' => 'Avalon',
            'state' => 'New South Wales',
            'postal_code' => 2099,
            'country' => 'Australia',
            'email' => 'john@example.com',
            'phone' => '02-458596236',
            'website' => 'http://www.example.com',
            'industry' => 'Aged Care Services',
            'size' => 950,
            'established_date' => '1995-02-18',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // seeder using set array
        $this->call([
            JobTitleSeeder::class,
            DepartmentSeeder::class,
        ]);

        $this->call([
           // call facility factory to create set number of random facilities
            FacilitySeeder::class,
            EmployeeSeeder::class,
        ]);

        // test employee
        Employee::factory()->create([
            'first_name' => 'John',
            'middle_name' => 'Michael',
            'last_name' => 'Doe',
            'image' => 'uploads/images/profile.jpg',  // assuming you have the image stored somewhere
            'email' => 'john@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'phone' => '123-456-7890',
            'company_id' => Company::first()->id,
            'date_of_birth' => '1990-01-01',   // date format as 'YYYY-MM-DD'
            'gender' => 'Male',
            'address' => '1234 Elm Street',
            'suburb' => 'Downtown',
            'postal_code' => '90210',

            //fields not here will be added by factory such as country and state
        ]);

        // finally call the assignment seeder
        $this->call([
//            EmployeeAssignmentSeeder::class,
        ]);


    }
}
