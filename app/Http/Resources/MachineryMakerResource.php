<?php

namespace App\Http\Resources;

use App\Models\MachineryMaker;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MachineryMakerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var MachineryMaker $maker */
        $maker = $this->resource;
        return [
            'id' => $maker->getAttribute('id'),
            'name' => $maker->getAttribute('name'),
        ];
    }
}
