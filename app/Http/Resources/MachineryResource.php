<?php

namespace App\Http\Resources;

use App\Models\Machinery;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MachineryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Machinery $machinery */
        $machinery = $this->resource;
        return [
            'id' => $machinery->getAttribute('id'),
            'name' => $machinery->getAttribute('name'),
            'code_name' => $machinery->getAttribute('code_name'),
            'department' => new VesselDepartmentResource($machinery->department),
            'maker' => new MachineryMakerResource($machinery->maker),
            'model' => new MachineryModelResource($machinery->model),
            'sub_categories' => SubCategoryResource::collection($machinery->subCategories),
        ];
    }
}
