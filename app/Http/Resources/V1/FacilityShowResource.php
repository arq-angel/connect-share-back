<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FacilityShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//        return parent::toArray($request);

        // here we will use eloquent relationship to retrieve details dynamically
        return [
            'employee' => [
                'id' => $this['employee']->id,
                'first_name' => $this['employee']->first_name,
                'middle_name' => $this['employee']->middle_name,
                'last_name' => $this['employee']->last_name,
                'image' => $this['employee']->image,
                ],
            'department' => [
                'id' => $this['department']->id,
                'name' => $this['department']->name,
                'short_name' => $this['department']->short_name,
                'image' => $this['department']->image,
                ],
            'jobTitle' => [
                'id' => $this['jobTitle']->id,
                'title' => $this['jobTitle']->title,
                'short_title' => $this['jobTitle']->short_title,
                'image' => $this['jobTitle']->image,
                ]
        ];
    }
}
