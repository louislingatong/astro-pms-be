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
    public function rules()
    {
        return [
            'vessel_machinery_id' => ['required', 'exists:vessel_machineries,id'],
            'code' => 'required',
            'sub_category_id' => ['required', 'exists:sub_categories,id'],
            'description' => 'nullable'
        ];
    }

    public function getVesselMachineryId()
    {
        return $this->input('vessel_machinery_id', null);
    }

    public function getCode()
    {
        return $this->input('code', null);
    }

    public function getSubCategoryId()
    {
        return $this->input('sub_category_id', null);
    }

    public function getDescription()
    {
        return $this->input('description', null);
    }
}
