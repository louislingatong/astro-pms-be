<?php

namespace App\Http\Resources;

use App\Models\VesselMachinerySubCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VesselMachinerySubCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var VesselMachinerySubCategory $vesselSubCategory */
        $vesselSubCategory = $this->resource;
        return [
            'id' => $vesselSubCategory->getAttribute('id'),
            'code' => $vesselSubCategory->getAttribute('code'),
            'due_date' => Carbon::parse($vesselSubCategory->getAttribute('due_date'))->format('d-M-Y'),
            'status' => $this->getStatus($vesselSubCategory->getAttribute('due_date')),
            'interval' => new IntervalResource($vesselSubCategory->interval),
            'sub_category' => new MachinerySubCategoryResource($vesselSubCategory->subCategory),
            'description' => new MachinerySubCategoryDescriptionResource($vesselSubCategory->description),
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
