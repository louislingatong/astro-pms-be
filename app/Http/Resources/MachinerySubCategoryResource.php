<?php

namespace App\Http\Resources;

use App\Models\MachinerySubCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MachinerySubCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var MachinerySubCategory $subCategory */
        $subCategory = $this->resource;
        return [
            'id' => $subCategory->getAttribute('id'),
            'name' => $subCategory->getAttribute('name'),
            'descriptions' => MachinerySubCategoryDescriptionResource::collection($subCategory->descriptions),
        ];
    }
}
