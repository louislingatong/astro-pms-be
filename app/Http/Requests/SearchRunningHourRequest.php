<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRunningHourRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'vessel_id' => [
                'required',
                'exists:vessels,id',
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

    public function getVesselId()
    {
        return $this->input('vessel_id', null);
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
