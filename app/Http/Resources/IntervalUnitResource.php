<?php

namespace App\Http\Resources;

use App\Models\IntervalUnit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IntervalUnitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var IntervalUnit $unit */
        $unit = $this->resource;
        return [
            'id' => $unit->getAttribute('id'),
            'name' => $unit->getAttribute('name'),
        ];
    }
}
