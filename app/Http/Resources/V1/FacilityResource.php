<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FacilityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//        return parent::toArray($request);

        return [
            'id' => $this->id,
            'sequenceId' => $this->sequence_id,
            'image' => $this->image,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'suburb' => $this->suburb,
            'state' => $this->state,
            'postCode' => $this->postal_code,
            'country' => $this->country,
            'estDate' => $this->established_date
        ];
    }
}
