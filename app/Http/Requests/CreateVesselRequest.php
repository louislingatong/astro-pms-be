<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateVesselRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code_name' => 'required',
            'name' => 'required',
        ];
    }

    public function getCodeName()
    {
        return $this->input('code_name', null);
    }

    public function getName()
    {
        return $this->input('name', null);
    }
}
