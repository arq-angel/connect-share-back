<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
//        $selectedCountry = $this->faker->randomElement(getCountryItems());
//        $selectedState = $this->faker->randomElement(getStateItems($selectedCountry));

        return [
//            'name' => $this->faker->company(),
            'image' => $this->faker->imageUrl(640, 480, 'business', true), // Placeholder for logo
//            'address' => $this->faker->address(),
//            'suburb' => $this->faker->city(),
//            'state' => $selectedState,
//            'postal_code' => $this->faker->postcode(),
//            'country' => $selectedCountry,
//            'email' => $this->faker->unique()->safeEmail(),
//            'phone' => $this->faker->phoneNumber(),
//            'website' => $this->faker->url(),
//            'industry' => 'Healthcare',
//            'size' => 1000,
//            'established_date' => '2000-01-01',
//            'is_active' => true,
        ];
    }
}
