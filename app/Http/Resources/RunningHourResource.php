<?php

namespace App\Http\Resources;

use App\Models\RunningHour;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RunningHourResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var RunningHour $runningHour */
        $runningHour = $this->resource;
        /** @var User $creator */
        $creator = $runningHour->creator;
        return [
            'id' => $runningHour->getAttribute('id'),
            'running_hours' => $runningHour->getAttribute('running_hours'),
            'created_at' => Carbon::create($runningHour->getAttribute('created_at'))->format('d-M-Y'),
            'creator' => $creator->getAttribute('full_name'),
        ];
    }
}
