<?php

namespace App\Http\Resources;

use App\Models\VesselMachinery;
use App\Models\VesselMachinerySubCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VesselMachinerySubCategoryWorkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var VesselMachinerySubCategory $vesselMachinerySubCategory */
        $vesselMachinerySubCategory = $this->resource;
        /** @var VesselMachinery $vesselMachinery */
        $vesselMachinery = $vesselMachinerySubCategory->vesselMachinery;
        return [
            'id' => $vesselMachinerySubCategory->getAttribute('id'),
            'code' => $vesselMachinerySubCategory->getAttribute('code'),
            'due_date' => Carbon::create($vesselMachinerySubCategory->getAttribute('due_date'))->format('d-M-Y'),
            'interval' => new IntervalResource($vesselMachinerySubCategory->interval),
            'sub_category' => new MachinerySubCategoryResource($vesselMachinerySubCategory->subCategory),
            'description' => new MachinerySubCategoryDescriptionResource($vesselMachinerySubCategory->description),
            'installed_date' => Carbon::create($vesselMachinery->getAttribute('installed_date'))->format('d-M-Y'),
            'status' => $this->getStatus($vesselMachinerySubCategory->getAttribute('due_date')),
            'current_work' => new WorkResource($vesselMachinerySubCategory->currentWork),
            'work_history' => WorkResource::collection($vesselMachinerySubCategory->worksHistory),
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
