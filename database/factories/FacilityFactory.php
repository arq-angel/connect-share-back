<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Facility>
 */
class FacilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'company_id' => \App\Models\Company::factory(), // Assuming a self-relation for demonstration
            'image' => $this->faker->imageUrl(640, 480, 'business', true), // Placeholder for logo
            'address' => $this->faker->streetAddress,
            'suburb' => $this->faker->city,
            'state' => $this->faker->state,
            'postal_code' => $this->faker->postcode,
            'country' => $this->faker->country,
            'email' => $this->faker->companyEmail,
            'phone' => $this->faker->phoneNumber,
            'website' => $this->faker->url,
            'size' => $this->faker->numberBetween(50, 200), // You can adjust these values as needed
            'established_date' => $this->faker->date(),
            'is_active' => $this->faker->boolean(80), // 80% chance of being active
        ];
    }
}
