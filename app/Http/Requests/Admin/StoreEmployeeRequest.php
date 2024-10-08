<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
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
            'image' => ['max:5000', 'image'],
            'first_name' => ['required', 'string', 'max:200'],
            'middle_name' => ['string', 'max:200'],
            'last_name' => ['required', 'string', 'max:200'],
            'email' => ['required', 'email', 'max:50', 'unique:employees,email,'],
            'phone' => ['required', 'string', 'max:20'],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required', 'string', 'max:50', Rule::in(['male', 'female', 'other'])],
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'address' => ['required', 'string', 'max:200'],
            'suburb' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:50'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:50'],
        ];
    }
}
