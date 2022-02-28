<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchVesselDepartmentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
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

    public function getKeyword()
    {
        return $this->input('keyword', '');
    }

    public function getPage()
    {
        return $this->input('page', 1); // page default to 1.
    }

    public function getLimit()
    {
        return $this->input('limit', config('search.results_per_page')); // set via config
    }
}
