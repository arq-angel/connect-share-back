<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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
            'id' => $this->id,
            'sequenceId' => $this->sequence_id,
            'firstName' => $this->first_name,
            'middleName' => $this->middle_name,
            'lastName' => $this->last_name,
            'image' => $this->image,
            'company' => $this->company->name,
            'assignments' => new EmployeeAssignmentCollection($this->assignments),
        ];
    }
}
