<?php

namespace App\Http\Resources;

use App\Models\MachineryModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MachineryModelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var MachineryModel $model */
        $model = $this->resource;
        return [
            'id' => $model->getAttribute('id'),
            'name' => $model->getAttribute('name'),
        ];
    }
}
