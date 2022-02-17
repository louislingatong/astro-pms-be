<?php

namespace App\Http\Resources;

use App\Models\VesselMachinery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VesselMachineryRunningHourResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var VesselMachinery $vesselMachinery */
        $vesselMachinery = $this->resource;
        return [
            'id' => $vesselMachinery->getAttribute('id'),
            'installed_date' => Carbon::parse($vesselMachinery->getAttribute('installed_date'))->format('d-M-Y'),
            'vessel' => new VesselResource($vesselMachinery->vessel),
            'machinery' => new MachineryResource($vesselMachinery->machinery),
            'current_running_hour' => new RunningHourResource($vesselMachinery->currentRunningHour),
            'running_hour_history' => RunningHourResource::collection($vesselMachinery->runningHoursHistory),
        ];
    }
}
