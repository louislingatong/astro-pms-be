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
            'vessel_sub_category_id' => ['required', 'exists:vessel_sub_categories,id'],
            'last_done' => ['required', 'date', 'date_format:d-M-Y'],
            'instructions' => 'nullable',
            'remarks' => 'nullable',
        ];
    }

    public function getVesselSubCategoryId()
    {
        return $this->input('vessel_sub_category_id', null);
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
