<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'email' => $this->email,
            'phone' => $this->phone,
            'jobTitle' => $this->job_title,
            'department' => $this->department,
            'designation' => $this->designation,
            'company' => $this->company->name,
            'dateOfBirth' => $this->date_of_birth,
            'gender' => $this->gender,
            'address' => $this->address,
            'suburb' => $this->suburb,
            'state' => $this->state,
            'postCode' => $this->postal_code,
        ];
    }
}
