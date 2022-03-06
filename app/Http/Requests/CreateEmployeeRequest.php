<?php

namespace App\Http\Requests;

use App\Rules\EmailAddressRule;
use App\Rules\PasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateEmployeeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'email' => [
                'required',
                new EmailAddressRule,
                'unique:users,email',
            ],
            'password' => [
                'required',
                new PasswordRule,
            ],
            'department' => [
                'required',
                'exists:employee_departments,name',
            ],
            'id_number' => 'nullable',
            'position' => 'nullable',
        ];
    }

    public function getFirstName()
    {
        return $this->input('first_name', null);
    }

    public function getMiddleName()
    {
        return $this->input('middle_name', null);
    }

    public function getLastName()
    {
        return $this->input('last_name', null);
    }

    public function getEmail()
    {
        return $this->input('email', null);
    }

    public function getPassword()
    {
        return $this->input('password', null);
    }

    public function getDepartment()
    {
        return $this->input('department', null);
    }

    public function getIdNumber()
    {
        return $this->input('id_number', null);
    }

    public function getPosition()
    {
        return $this->input('position', null);
    }
}
