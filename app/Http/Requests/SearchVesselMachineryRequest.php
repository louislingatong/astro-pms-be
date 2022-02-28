<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchVesselMachineryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'vessel' => [
                'required',
                'exists:vessels,name',
            ],
            'department' => [
                'nullable',
                'exists:vessel_departments,name',
            ],
            'keyword' => 'nullable',
            'page' => [
                'nullable',
                'numeric',
            ],
            'limit' => [
                'nullable',
                'numeric',
            ],
        ];
    }

    public function getVessel()
    {
        return $this->input('vessel', null);
    }

    public function getDepartment()
    {
        return $this->input('department', '');
    }

    public function getKeyword()
    {
        return $this->input('keyword', '');
    }

    public function getPage()
    {
        return (int)$this->input('page', 1); // page default to 1.
    }

    public function getLimit()
    {
        return (int)$this->input('limit', config('search.results_per_page')); // set via config
    }
}
