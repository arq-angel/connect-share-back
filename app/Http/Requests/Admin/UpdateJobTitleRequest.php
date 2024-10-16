<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJobTitleRequest extends FormRequest
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
        $jobTitleId = $this->route('job_title')->id;

        return [
            'image' => ['image', 'max:5000'],
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'title' => ['required', 'string', 'max:200', 'unique:job_titles,title,' . $this->route('job_title')->id],
            'short_title' => ['required', 'string', 'max:100', 'unique:job_titles,short_title,' . $this->route('job_title')->id],
            'manager_id' => ['nullable', 'integer', 'exists:job_titles,id',Rule::notIn([$jobTitleId])],
            'status' => ['required', 'string', Rule::in(getStatuses(request: 'status')['keys'])],
            'directory_flag' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'manager_id.not_in' => 'A job title cannot be its own manager. Please select a different job title.', // Custom message for Rule::notIn
//            'name.unique' => 'The department name has already been taken.',
//            'short_name.unique' => 'The short name has already been taken.',
            // Add other custom messages as needed
        ];
    }
}
