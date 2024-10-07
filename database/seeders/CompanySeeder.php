<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*Company::factory()
            ->count(1)
            ->hasFacilities(15)
            ->hasEmployees(1000)
            ->create();*/

        /*DB::table('companies')->insert([
            'name' => 'Thompson Health Care',
            'address' => '14 John Street',
            'suburb' => 'Avalon',
            'state' => 'New South Wales',
            'postal_code' => 2099,
            'country' => 'Australia',
            'email' => 'john@example.com',
            'phone' => '02-458596236',
            'website' => 'www.example.com',
            'industry' => 'Aged Care Services',
            'size' => 950,
            'established_date' => '1995-02-18',
            'created_at' => now(),
            'updated_at' => now(),
        ]);*/
    }
}
