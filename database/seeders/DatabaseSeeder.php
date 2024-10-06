<?php

namespace Database\Seeders;

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

        User::factory()->create([
            'name' => 'Test Admin User',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ]);

        $this->call([
            CompanySeeder::class,
            JobTitleSeeder::class,
            DepartmentSeeder::class,
        ]);

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
            'company_id' => '1',
            'date_of_birth' => '1990-01-01',   // date format as 'YYYY-MM-DD'
            'gender' => 'Male',
            'address' => '1234 Elm Street',
            'suburb' => 'Downtown',
            'state' => 'California',
            'postal_code' => '90210',
        ]);

        $this->call([
            EmployeeAssignmentSeeder::class,
        ]);


    }
}
