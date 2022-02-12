<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIntervalRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'unit_id' => ['required', 'exists:interval_units,id'],
            'value' => 'required',
        ];
    }

    public function getUnitId()
    {
        return $this->input('unit_id', null);
    }

    public function getValue()
    {
        return $this->input('value', null);
    }
}
