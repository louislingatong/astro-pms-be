<?php

namespace App\Http\Resources;

use App\Models\Interval;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IntervalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var Interval $interval */
        $interval = $this->resource;
        return [
            'id' => $interval->getAttribute('id'),
            'value' => $interval->getAttribute('value'),
            'name' => $interval->getAttribute('name'),
            'unit' => new IntervalUnitResource($interval->unit),
        ];
    }
}
