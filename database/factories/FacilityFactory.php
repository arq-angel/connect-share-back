<?php

namespace Database\Factories;

use App\Models\Department;
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

        $selectedCountry = $this->faker->randomElement(getCountryItems());
        $selectedState = $this->faker->randomElement(getStateItems($selectedCountry));

        return [
            'name' => $this->faker->company,
            'company_id' => \App\Models\Company::factory(), // Assuming a self-relation for demonstration
            'image' => $this->faker->imageUrl(640, 480, 'business', true), // Placeholder for logo
            'address' => $this->faker->streetAddress,
            'suburb' => $this->faker->city,
            'state' => $selectedState,
            'postal_code' => $this->faker->postcode,
            'country' => $selectedCountry,
            'email' => $this->faker->companyEmail,
            'phone' => $this->faker->phoneNumber,
            'website' => $this->faker->url,
            'size' => $this->faker->numberBetween(50, 100), // You can adjust these values as needed
            'established_date' => $this->faker->date(),
//            'is_active' => $this->faker->boolean(80), // 80% chance of being active
            'is_active' => true, // set true for all
        ];
    }
}
