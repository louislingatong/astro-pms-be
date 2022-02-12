<?php

namespace App\Http\Resources;

use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var SubCategory $subCategory */
        $subCategory = $this->resource;
        return [
            'id' => $subCategory->getAttribute('id'),
            'name' => $subCategory->getAttribute('name'),
            'descriptions' => SubCategoryDescriptionResource::collection($subCategory->descriptions),
        ];
    }
}
