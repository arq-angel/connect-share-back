<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJobTitleRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:200'],
            'short_title' => ['required', 'string', 'max:100'],
            'manager_id' => ['required', 'integer', 'exists:job_titles,id'],
            'status' => ['required', 'string', Rule::in(getStatuses(request: 'status')['keys'])],
            'directory_flag' => ['required', 'boolean'],
        ];
    }
}
