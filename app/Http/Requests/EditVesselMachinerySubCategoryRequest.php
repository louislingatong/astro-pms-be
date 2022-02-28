<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditVesselMachinerySubCategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'vessel_machinery_sub_categories' => [
                'required',
                'array',
            ],
            'vessel_machinery_sub_categories.*.code' => 'required',
            'vessel_machinery_sub_categories.*.description' => 'nullable',
            'vessel_machinery_sub_categories.*.interval' => [
                'required',
                'exists:intervals,name',
            ],
            'vessel_machinery_sub_categories.*.machinery_sub_category_id' => [
                'required',
                'exists:machinery_sub_categories,id',
            ],
        ];
    }

    public function getVesselMachinerySubCategories()
    {
        return $this->input('vessel_machinery_sub_categories.*', null);
    }
}
