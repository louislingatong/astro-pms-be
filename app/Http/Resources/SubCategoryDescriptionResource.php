<?php

namespace App\Http\Resources;

use App\Models\SubCategoryDescription;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryDescriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var SubCategoryDescription $description */
        $description = $this->resource;
        return [
            'id' => $description->getAttribute('id'),
            'name' => $description->getAttribute('name'),
        ];
    }
}
