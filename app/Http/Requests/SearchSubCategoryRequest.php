<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchSubCategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'keyword' => 'nullable',
            'status' => ['nullable', 'in:' . implode(',', config('job.statuses'))],
            'page' => ['nullable', 'numeric'],
            'limit' => ['nullable', 'numeric'],
        ];
    }

    public function getKeyword()
    {
        return $this->input('keyword', '');
    }

    public function getStatus()
    {
        return $this->input('status', '');
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
