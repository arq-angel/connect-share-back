<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use NunoMaduro\Collision\Adapters\Phpunit\State;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['sometimes', 'string', 'max:100'],
            'middle_name' => ['sometimes', 'string', 'max:100'],
            'last_name' => ['sometimes', 'string', 'max:100'],
            'image' => ['nullable', 'image', 'max:5000'],
            'email' => ['sometimes', 'email', 'max:100'],
            'phone' => ['sometimes', 'string', 'max:20'],
            'date_of_birth' => ['sometimes', 'string', 'max:20'],
            'gender' => ['sometimes', 'string', Rule::in(['male', 'female', 'other'])],
            'address' => ['sometimes', 'string', 'max:200'],
            'suburb' => ['sometimes', 'string', 'max:100'],
            'state' => [
                'sometimes',
                'string',
                'max:50',
                function ($attribute, $value, $fail) {
                    // Retrieve the value of 'country' from the request data
                    $country = request('country');

                    // Use getStateItems function to get the valid states for the given country
                    if ($country && !in_array($value, getStateItems($country))) {
                        $fail("The $attribute is invalid for the selected country.");
                    }
                }
            ],
            'postal_code' => ['sometimes', 'string', 'max:20'],
            'country' => ['sometimes', 'string', 'max:50', Rule::in(getCountryItems())],
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->firstName) {
            $this->merge([
                'first_name' => $this->firstName,
            ]);
        }

        if ($this->middleName) {
            $this->merge([
                'middle_name' => $this->middleName,
            ]);
        }

        if ($this->lastName) {
            $this->merge([
                'last_name' => $this->lastName,
            ]);
        }

        if ($this->dateOfBirth) {
            $this->merge([
                'date_of_birth' => $this->dateOfBirth,
            ]);
        }

        if ($this->postCode) {
            $this->merge([
                'postal_code' => $this->postCode,
            ]);
        }
    }
}
