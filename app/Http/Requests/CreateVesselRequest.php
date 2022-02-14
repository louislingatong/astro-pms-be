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
    public function rules(): array
    {
        return [
            'owner_id' => [
                'required',
                'exists:vessel_owners,id',
            ],
            'code_name' => 'required',
            'name' => 'required',
        ];
    }

    public function getOwnerId()
    {
        return $this->input('owner_id', null);
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
