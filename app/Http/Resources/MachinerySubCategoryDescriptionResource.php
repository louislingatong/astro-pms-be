<?php

namespace App\Http\Resources;

use App\Models\MachinerySubCategoryDescription;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MachinerySubCategoryDescriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var MachinerySubCategoryDescription $description */
        $description = $this->resource;
        return [
            'id' => $description->getAttribute('id'),
            'name' => $description->getAttribute('name'),
        ];
    }
}
