<?php

namespace App\Http\Requests;

use App\Rules\Password;
use App\Rules\PasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'token' => 'required',
            'password' => [
                'required',
                new PasswordRule,
                'confirmed'
            ],
        ];
    }

    public function getToken()
    {
        return $this->input('token', null);
    }

    public function getPassword()
    {
        return $this->input('password', null);
    }
}
