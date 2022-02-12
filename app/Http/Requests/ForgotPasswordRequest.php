<?php

namespace App\Http\Requests;

use App\Rules\EmailAddress;
use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', new EmailAddress],
        ];
    }

    public function getEmail()
    {
        return $this->input('email', null);
    }
}
