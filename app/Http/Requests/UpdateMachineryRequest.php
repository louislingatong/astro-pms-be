<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMachineryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'department_id' => [
                'required',
                'exists:vessel_departments,id',
            ],
            'code_name' => 'required',
            'name' => 'required',
            'model' => 'nullable',
            'maker' => 'nullable',
        ];
    }

    public function getDepartmentId()
    {
        return $this->input('department_id', null);
    }

    public function getCodeName()
    {
        return $this->input('code_name', null);
    }

    public function getName()
    {
        return $this->input('name', null);
    }

    public function getModel()
    {
        return $this->input('model', null);
    }

    public function getMaker()
    {
        return $this->input('maker', null);
    }
}
