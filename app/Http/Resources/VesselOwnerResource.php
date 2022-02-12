<?php

namespace App\Http\Resources;

use App\Models\VesselOwner;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VesselOwnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var VesselOwner $owner */
        $owner = $this->resource;
        return [
            'id' => $owner->getAttribute('id'),
            'name' => $owner->getAttribute('name'),
        ];
    }
}
