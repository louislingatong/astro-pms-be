<?php

namespace App\Http\Resources;

use App\Models\Vessel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VesselResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var Vessel $vessel */
        $vessel = $this->resource;
        return [
            'id' => $vessel->getAttribute('id'),
            'code_name' => $vessel->getAttribute('code_name'),
            'name' => $vessel->getAttribute('name'),
            'owner' => new VesselOwnerResource($vessel->owner),
            'former_name' => $vessel->getAttribute('former_name'),
            'flag' => $vessel->getAttribute('flag'),
            'call_sign' => $vessel->getAttribute('call_sign'),
            'official_no' => $vessel->getAttribute('official_no'),
            'imo_no' => $vessel->getAttribute('imo_no'),
            'loa' => $vessel->getAttribute('loa'),
            'lbp' => $vessel->getAttribute('lbp'),
            'light_condition' => $vessel->getAttribute('light_condition'),
            'classification' => $vessel->getAttribute('classification'),
            'character' => $vessel->getAttribute('character'),
            'descriptive_note' => $vessel->getAttribute('descriptive_note'),
            'built_year' => $vessel->getAttribute('built_year'),
            'build_yard' => $vessel->getAttribute('build_yard'),
            'tpc' => $vessel->getAttribute('tpc'),
            'breadth' => $vessel->getAttribute('breadth'),
            'depth' => $vessel->getAttribute('depth'),
            'summer_draft' => $vessel->getAttribute('summer_draft'),
            'summer_freeboard' => $vessel->getAttribute('summer_freeboard'),
            'summer_deadweight' => $vessel->getAttribute('summer_deadweight'),
            'winter_draft' => $vessel->getAttribute('winter_draft'),
            'winter_freeboard' => $vessel->getAttribute('winter_freeboard'),
            'winter_deadweight' => $vessel->getAttribute('winter_deadweight'),
            'tropical_draft' => $vessel->getAttribute('tropical_draft'),
            'tropical_freeboard' => $vessel->getAttribute('tropical_freeboard'),
            'tropical_deadweight' => $vessel->getAttribute('tropical_deadweight'),
            'tropical_fw_draft' => $vessel->getAttribute('tropical_fw_draft'),
            'tropical_fw_freeboard' => $vessel->getAttribute('tropical_fw_freeboard'),
            'tropical_fw_deadweight' => $vessel->getAttribute('tropical_fw_deadweight'),
            'fw_draft' => $vessel->getAttribute('fw_draft'),
            'fw_freeboard' => $vessel->getAttribute('fw_freeboard'),
            'fw_deadweight' => $vessel->getAttribute('fw_deadweight'),
            'fw_allowance' => $vessel->getAttribute('fw_allowance'),
            'light_shift_drafts_f' => $vessel->getAttribute('light_shift_drafts_f'),
            'light_shift_drafts_a' => $vessel->getAttribute('light_shift_drafts_a'),
            'heavy_ballast_drafts_f' => $vessel->getAttribute('heavy_ballast_drafts_f'),
            'heavy_ballast_drafts_a' => $vessel->getAttribute('heavy_ballast_drafts_a'),
            'normal_ballast_drafts_f' => $vessel->getAttribute('normal_ballast_drafts_f'),
            'normal_ballast_drafts_a' => $vessel->getAttribute('normal_ballast_drafts_a'),
            'international_gt' => $vessel->getAttribute('international_gt'),
            'international_nt' => $vessel->getAttribute('international_nt'),
            'panama_gt' => $vessel->getAttribute('panama_gt'),
            'panama_nt' => $vessel->getAttribute('panama_nt'),
            'suez_gt' => $vessel->getAttribute('suez_gt'),
            'suez_nt' => $vessel->getAttribute('suez_nt'),
        ];
    }
}
