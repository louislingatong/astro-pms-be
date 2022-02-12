<?php

namespace App\Http\Resources;

use App\Models\Rank;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RankResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Rank $rank */
        $rank = $this->resource;
        return [
            'id' => $rank->getAttribute('id'),
            'name' => $rank->getAttribute('name'),
            'type' => new RankTypeResource($rank->type),
        ];
    }
}
