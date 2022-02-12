<?php

namespace App\Http\Resources;

use App\Models\Vessel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VesselResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var Vessel $vessel */
        $vessel = $this->resource;
        return [
            'id' => $vessel->getAttribute('id'),
            'name' => $vessel->getAttribute('name'),
            'owner' => new VesselOwnerResource($vessel->owner),
        ];
    }
}
