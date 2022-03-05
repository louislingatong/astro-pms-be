<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateWorkRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'vessel_machinery_sub_category_ids' => [
                'required',
                'array',
            ],
            'vessel_machinery_sub_category_ids.*' => [
                'required',
                'exists:vessel_machinery_sub_categories,id',
            ],
            'last_done' => [
                'required',
                'date',
                'date_format:d-M-Y',
            ],
            'instructions' => 'nullable',
            'remarks' => 'nullable',
        ];
    }

    public function getVesselMachinerySubCategoryIds()
    {
        return $this->input('vessel_machinery_sub_category_ids.*', null);
    }

    public function getLastDone()
    {
        return $this->input('last_done', null);
    }

    public function getInstructions()
    {
        return $this->input('instructions', null);
    }

    public function getRemarks()
    {
        return $this->input('remarks', null);
    }
}
