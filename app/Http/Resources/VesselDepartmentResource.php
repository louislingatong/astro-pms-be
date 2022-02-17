<?php

namespace App\Http\Resources;

use App\Models\VesselDepartment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VesselDepartmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var VesselDepartment $department */
        $department = $this->resource;
        return [
            'id' => $department->getAttribute('id'),
            'name' => $department->getAttribute('name'),
        ];
    }
}
