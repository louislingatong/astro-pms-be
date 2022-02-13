<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateVesselMachineryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'installed_date' => ['required', 'date', 'date_format:d-M-Y'],
            'vessel_id' => ['required', 'exists:vessels,id'],
            'machinery_id' => ['required', 'exists:machineries,id'],
            'incharge_rank_id' => ['required', 'exists:ranks,id'],
            'interval_id' => ['required', 'exists:intervals,id'],
        ];
    }

    public function getInstalledDate()
    {
        return $this->input('installed_date', null);
    }

    public function getVesselId()
    {
        return $this->input('vessel_id', null);
    }

    public function getMachineryId()
    {
        return $this->input('machinery_id', null);
    }

    public function getInchargeRankId()
    {
        return $this->input('incharge_rank_id', null);
    }

    public function getIntervalId()
    {
        return $this->input('interval_id', null);
    }
}
