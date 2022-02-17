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
            'vessel' => new VesselResource($vesselMachinery->vessel),
            'machinery' => new MachineryResource($vesselMachinery->machinery),
            'incharge_rank' => new RankResource($vesselMachinery->inchargeRank),
            'sub_categories' => VesselMachinerySubCategoryResource::collection($vesselMachinery->subCategories),
        ];
    }
}
