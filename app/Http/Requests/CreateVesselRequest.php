<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateVesselRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'owner' => 'required',
            'code_name' => 'required',
            'former_name' => 'nullable',
            'flag' => 'nullable',
            'call_sign' => 'nullable',
            'official_no' => 'nullable',
            'imo_no' => 'nullable',
            'loa' => 'nullable',
            'lbp' => 'nullable',
            'light_condition' => 'nullable',
            'classification' => 'nullable',
            'character' => 'nullable',
            'descriptive_note' => 'nullable',
            'built_year' => 'nullable',
            'build_yard' => 'nullable',
            'tpc' => 'nullable',
            'breadth' => 'nullable',
            'depth' => 'nullable',
            'summer_draft' => 'nullable',
            'summer_freeboard' => 'nullable',
            'summer_deadweight' => 'nullable',
            'winter_draft' => 'nullable',
            'winter_freeboard' => 'nullable',
            'winter_deadweight' => 'nullable',
            'tropical_draft' => 'nullable',
            'tropical_freeboard' => 'nullable',
            'tropical_deadweight' => 'nullable',
            'tropical_fw_draft' => 'nullable',
            'tropical_fw_freeboard' => 'nullable',
            'tropical_fw_deadweight' => 'nullable',
            'fw_draft' => 'nullable',
            'fw_freeboard' => 'nullable',
            'fw_deadweight' => 'nullable',
            'fw_allowance' => 'nullable',
            'light_shift_drafts_f' => 'nullable',
            'light_shift_drafts_a' => 'nullable',
            'heavy_ballast_drafts_f' => 'nullable',
            'heavy_ballast_drafts_a' => 'nullable',
            'normal_ballast_drafts_f' => 'nullable',
            'normal_ballast_drafts_a' => 'nullable',
            'international_gt' => 'nullable',
            'international_nt' => 'nullable',
            'panama_gt' => 'nullable',
            'panama_nt' => 'nullable',
            'suez_gt' => 'nullable',
            'suez_nt' => 'nullable',
        ];
    }

    public function getOwner()
    {
        return $this->input('owner', null);
    }

    public function getCodeName()
    {
        return $this->input('code_name', null);
    }

    public function getName()
    {
        return $this->input('name', null);
    }

    public function getFormerName()
    {
        return $this->input('former_name', null);
    }

    public function getFlag()
    {
        return $this->input('flag', null);
    }

    public function getCallSign()
    {
        return $this->input('call_sign', null);
    }

    public function getOfficialNumber()
    {
        return $this->input('official_no', null);
    }

    public function getImoNo()
    {
        return $this->input('imo_no', null);
    }

    public function getLoa()
    {
        return $this->input('loa', null);
    }

    public function getLbp()
    {
        return $this->input('lbp', null);
    }

    public function getLightCondition()
    {
        return $this->input('light_condition', null);
    }

    public function getClassification()
    {
        return $this->input('classification', null);
    }

    public function getCharacter()
    {
        return $this->input('character', null);
    }

    public function getDescriptiveNote()
    {
        return $this->input('descriptive_note', null);
    }

    public function getBuiltYear()
    {
        return $this->input('built_year', null);
    }

    public function getBuiltYard()
    {
        return $this->input('build_yard', null);
    }

    public function getTpc()
    {
        return $this->input('tpc', null);
    }

    public function getBreadth()
    {
        return $this->input('breadth', null);
    }

    public function getDepth()
    {
        return $this->input('depth', null);
    }

    public function getSummerDraft()
    {
        return $this->input('summer_draft', null);
    }

    public function getSummerFreeboard()
    {
        return $this->input('summer_freeboard', null);
    }

    public function getSummerDeadweight()
    {
        return $this->input('summer_deadweight', null);
    }

    public function getSWinterDraft()
    {
        return $this->input('winter_draft', null);
    }

    public function getWinterFreeboard()
    {
        return $this->input('winter_freeboard', null);
    }

    public function getWinterDeadweight()
    {
        return $this->input('winter_deadweight', null);
    }

    public function getTropicalDraft()
    {
        return $this->input('tropical_draft', null);
    }

    public function getTropicalFreeboard()
    {
        return $this->input('tropical_freeboard', null);
    }

    public function getTropicalDeadweight()
    {
        return $this->input('tropical_deadweight', null);
    }

    public function getTropicalFwDraft()
    {
        return $this->input('tropical_fw_draft', null);
    }

    public function getTropicalFwFreeboard()
    {
        return $this->input('tropical_fw_freeboard', null);
    }

    public function getTropicalFwDeadweight()
    {
        return $this->input('tropical_fw_deadweight', null);
    }

    public function getFwDraft()
    {
        return $this->input('fw_draft', null);
    }

    public function getFwFreeboard()
    {
        return $this->input('fw_freeboard', null);
    }

    public function getFwDeadweight()
    {
        return $this->input('fw_deadweight', null);
    }

    public function getFwAllowance()
    {
        return $this->input('fw_allowance', null);
    }

    public function getLightShiftDraftsF()
    {
        return $this->input('light_shift_drafts_f', null);
    }

    public function getLightShiftDraftsA()
    {
        return $this->input('light_shift_drafts_a', null);
    }

    public function getHeavyBallastDraftsF()
    {
        return $this->input('heavy_ballast_drafts_f', null);
    }

    public function getHeavyBallastDraftsA()
    {
        return $this->input('heavy_ballast_drafts_a', null);
    }

    public function getNormalBallastDraftsF()
    {
        return $this->input('normal_ballast_drafts_f', null);
    }

    public function getNormalBallastDraftsA()
    {
        return $this->input('normal_ballast_drafts_a', null);
    }

    public function getInternationalGt()
    {
        return $this->input('international_gt', null);
    }

    public function getInternationalNt()
    {
        return $this->input('international_nt', null);
    }

    public function getPanamaGt()
    {
        return $this->input('panama_gt', null);
    }

    public function getPanamaNt()
    {
        return $this->input('panama_nt', null);
    }

    public function getSuezGt()
    {
        return $this->input('suez_gt', null);
    }

    public function getSuezNt()
    {
        return $this->input('suez_nt', null);
    }

}
