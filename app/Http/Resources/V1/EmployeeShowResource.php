<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /*return parent::toArray($request);*/

        return [
            'firstName' => $this->first_name,
            'middleName' => $this->middle_name,
            'lastName' => $this->last_name,
            'image' => $this->image,
            'department' => $this->department,
            'designation' => $this->designation,
            'company' => $this->company->name,
            'jobTitle' => $this->job_title,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'suburb' => $this->suburb,
            'state' => $this->state,
            'postCode' => $this->postal_code,
        ];
    }
}
