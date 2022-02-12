<?php

namespace App\Http\Resources;

use App\Models\UserStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserStatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var UserStatus $status */
        $status = $this->resource;
        return [
            'id' => $status->getAttribute('id'),
            'name' => $status->getAttribute('name'),
        ];
    }
}
