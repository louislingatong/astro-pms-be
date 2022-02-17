<?php

namespace App\Http\Resources;

use App\Models\RankType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RankTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var RankType $type */
        $type = $this->resource;
        return [
            'id' => $type->getAttribute('id'),
            'name' => $type->getAttribute('name'),
        ];
    }
}
