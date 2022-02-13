<?php

namespace App\Http\Resources;

use App\Models\VesselMachinery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VesselMachineryResource extends JsonResource
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
            'due_date' => Carbon::parse($vesselMachinery->getAttribute('due_date'))->format('d-M-Y'),
            'status' => $this->getStatus($vesselMachinery->getAttribute('due_date')),
            'vessel' => new VesselResource($vesselMachinery->vessel),
            'machinery' => new MachineryResource($vesselMachinery->machinery),
            'incharge_rank' => new RankResource($vesselMachinery->inchargeRank),
            'interval' => new IntervalResource($vesselMachinery->interval),
        ];
    }

    /**
     * Get the job status
     *
     * @param string $dueDate
     * @return string
     */
    public function getStatus(string $dueDate): string
    {
        $currentDate = Carbon::now()->startOfDay();
        $dueDate = Carbon::parse($dueDate);
        if ($currentDate->greaterThan($dueDate)) {
            return config('work.statuses.overdue');
        } else if ($currentDate->isSameDay($dueDate)) {
            return config('work.statuses.due');
        } else if ($currentDate->diffInDays($dueDate) <= config('work.warning_days')) {
            return config('work.statuses.warning');
        } else {
            return '';
        }
    }
}
