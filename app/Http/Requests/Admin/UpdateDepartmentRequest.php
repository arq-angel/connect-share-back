<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDepartmentRequest extends FormRequest
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
        $departmentId = $this->route('department')->id;

        return [
            'image' => ['image', 'max:5000'],
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'name' => ['required', 'string', 'max:200', 'unique:departments,name,' . $this->route('department')->id],
            'short_name' => ['required', 'string', 'max:100', 'unique:departments,short_name,' . $this->route('department')->id],
            'job_title_id.*' => ['integer', 'exists:job_titles,id'],
            'parent_id' => ['nullable', 'integer', 'exists:departments,id',Rule::notIn([$departmentId])],
        ];
    }

    public function messages(): array
    {
        return [
            'parent_id.not_in' => 'A department cannot be its own parent. Please select a different department.', // Custom message for Rule::notIn
//            'name.unique' => 'The department name has already been taken.',
//            'short_name.unique' => 'The short name has already been taken.',
            // Add other custom messages as needed
        ];
    }
}
