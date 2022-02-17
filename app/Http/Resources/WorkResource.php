<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var Work $work */
        $work = $this->resource;
        /** @var User $creator */
        $creator = $work->creator;
        return [
            'id' => $work->getAttribute('id'),
            'last_done' => Carbon::create($work->getAttribute('last_done'))->format('d-M-Y'),
            'instructions' => $work->getAttribute('instructions'),
            'remarks' => $work->getAttribute('remarks'),
            'created_at' => Carbon::create($work->getAttribute('created_at'))->format('d-M-Y'),
            'creator' => $creator->getAttribute('full_name'),
        ];
    }
}
