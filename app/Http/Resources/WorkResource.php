<?php

namespace App\Http\Resources;

use App\Models\Work;
use App\Models\User;
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
    public function toArray($request)
    {
        /** @var Work $job */
        $job = $this->resource;
        /** @var User $creator */
        $creator = $job->creator;
        return [
            'id' => $job->getAttribute('id'),
            'instructions' => $job->getAttribute('instructions'),
            'remarks' => $job->getAttribute('remarks'),
            'created_at' => Carbon::create($job->getAttribute('created_at'))->format('d-M-Y'),
            'creator' => $creator->getAttribute('full_name'),
        ];
    }
}
