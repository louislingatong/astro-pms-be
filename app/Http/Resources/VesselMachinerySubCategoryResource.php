<?php

namespace App\Http\Resources;

use App\Models\VesselMachinerySubCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VesselMachinerySubCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var VesselMachinerySubCategory $vesselSubCategory */
        $vesselSubCategory = $this->resource;
        return [
            'id' => $vesselSubCategory->getAttribute('id'),
            'code' => $vesselSubCategory->getAttribute('code'),
            'interval' => new IntervalResource($vesselSubCategory->interval),
            'sub_category' => new MachinerySubCategoryResource($vesselSubCategory->subCategory),
            'description' => new MachinerySubCategoryDescriptionResource($vesselSubCategory->description),
        ];
    }
}
