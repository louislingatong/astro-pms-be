<?php

namespace App\Http\Resources;

use App\Models\VesselMachinerySubCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VesselMachinerySubCategoryWorkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var VesselMachinerySubCategory $vesselSubCategory */
        $vesselSubCategory = $this->resource;
        return [
            'id' => $vesselSubCategory->getAttribute('id'),
            'code' => $vesselSubCategory->getAttribute('code'),
            'sub_category' => new SubCategoryResource($vesselSubCategory->subCategory),
            'description' => new SubCategoryDescriptionResource($vesselSubCategory->description),
            'current_job' => new WorkResource($vesselSubCategory->currentJob),
            'job_history' => WorkResource::collection($vesselSubCategory->jobsHistory),
        ];
    }
}
