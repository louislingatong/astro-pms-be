<?php

namespace App\Http\Resources;

use App\Models\EmployeeDepartment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeDepartmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var EmployeeDepartment $department */
        $department = $this->resource;
        return [
            'id' => $department->getAttribute('id'),
            'name' => $department->getAttribute('name'),
        ];
    }
}
