<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AjaxJobTitleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        /*return parent::toArray($request);*/

        return $this->collection->map(function ($department) {
            return [
                'id' => $department->id,
                'title' => $department->title,
            ];
        })->toArray();
    }
}
