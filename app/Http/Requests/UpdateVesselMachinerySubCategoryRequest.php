<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVesselMachinerySubCategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'code' => 'required',
            'description' => 'nullable',
            'interval_id' => [
                'required',
                'exists:intervals,id',
            ],
            'vessel_machinery_id' => [
                'required',
                'exists:vessel_machineries,id',
            ],
            'machinery_sub_category_id' => [
                'required',
                'exists:machinery_sub_categories,id',
            ],
        ];
    }

    public function getCode()
    {
        return $this->input('code', null);
    }

    public function getDescription()
    {
        return $this->input('description', null);
    }

    public function getIntervalId()
    {
        return $this->input('interval_id', null);
    }

    public function getVesselMachineryId()
    {
        return $this->input('vessel_machinery_id', null);
    }

    public function getMachinerySubCategoryId()
    {
        return $this->input(' machinery_sub_category_id', null);
    }
}
