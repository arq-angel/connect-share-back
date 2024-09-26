<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'first_name' => $this->faker->firstName(),
            'middle_name' => $this->faker->lastName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'job_title' => $this->faker->jobTitle(),
            'department' => $this->faker->randomElement(['HR', 'IT', 'Sales', 'Marketing', 'Finance', 'Operations', 'Customer Support']),
            'designation' => $this->faker->jobTitle(),
            'hire_date' => $this->faker->date(),
            'employee_id' => $this->faker->uuid(),
            'date_of_birth' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'address' => $this->faker->address(),
            'suburb' => $this->faker->city(),
            'state' => $this->faker->state(),
            'postal_code' => $this->faker->postcode(),
            'country' => 'Australia',
            'is_active' => $this->faker->randomElement([0, 1]),
        ];
    }
}
