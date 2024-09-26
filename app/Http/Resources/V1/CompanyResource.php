<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'name' => $this->name,
            'logo' => $this->logo,
            'address' => $this->address,
            'suburb' => $this->suburb,
            'state' => $this->state,
            'postCode' => $this->postal_code,
            'country' => $this->country,
            'email' => $this->email,
            'phone' => $this->phone,
            'website' => $this->website,
        ];
    }
}
