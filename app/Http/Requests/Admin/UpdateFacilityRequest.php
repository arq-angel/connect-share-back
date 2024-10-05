<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFacilityRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:200'],
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'address' => ['required', 'string', 'max:200'],
            'suburb' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:20'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:50', 'unique:facilities,email,'. $this->route('facility')->id],
            'phone' => ['required', 'string', 'max:20'],
            'website' => ['required', 'url',],
            'size' => ['required', 'integer'],
            'established_date' => ['required', 'date'],
        ];
    }
}
