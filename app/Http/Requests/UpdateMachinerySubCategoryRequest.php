<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMachinerySubCategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'machinery_id' => [
                'required',
                'exists:machineries,id',
            ],
            'name' => 'required',
        ];
    }

    public function getMachineryId()
    {
        return $this->input('machinery_id', null);
    }

    public function getName()
    {
        return $this->input('name', null);
    }
}
