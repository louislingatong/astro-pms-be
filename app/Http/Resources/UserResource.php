<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var User $user */
        $user = $this->resource;
        return [
            'id' => $user->getAttribute('id'),
            'first_name' => $user->getAttribute('first_name'),
            'last_name' => $user->getAttribute('last_name'),
            'full_name' => $user->getAttribute('full_name'),
            'email' => $user->getAttribute('email'),
            'status' => new UserStatusResource($user->status),
        ];
    }
}
