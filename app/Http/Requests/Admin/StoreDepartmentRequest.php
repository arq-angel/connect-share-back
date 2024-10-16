<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDepartmentRequest extends FormRequest
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
            'image' => ['image', 'max:5000'],
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'name' => ['required', 'string', 'max:200'],
            'short_name' => ['required', 'string', 'max:100'],
            'job_title_id.*' => ['integer', 'exists:job_titles,id'],
            'parent_id' => ['nullable', 'integer', 'exists:departments,id'],
            'status' => ['required', 'string', Rule::in(getStatuses(request: 'status')['keys'])],
            'directory_flag' => ['required', 'boolean'],
        ];
    }
}
