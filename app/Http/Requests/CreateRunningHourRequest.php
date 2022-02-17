<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRunningHourRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'vessel_machinery_id' => [
                'required',
                'exists:vessel_machineries,id',
            ],
            'running_hours' => [
                'required',
                'numeric',
            ],
            'updating_date' => [
                'required',
                'date',
                'date_format:d-M-Y',
            ],
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

    public function getUpdatingDate()
    {
        return $this->input('updating_date', null);
    }
}
