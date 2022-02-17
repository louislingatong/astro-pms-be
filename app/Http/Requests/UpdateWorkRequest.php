<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'vessel_sub_category_id' => [
                'required',
                'exists:vessel_sub_categories,id',
            ],
            'instructions' => 'required',
            'remarks' => 'required',
        ];
    }

    public function getVesselMachineryId()
    {
        return $this->input('vessel_machinery_id', null);
    }

    public function getRunningHours()
    {
        return $this->input('running_hours', null);
    }
}
