<?php

namespace App\Http\Requests;

use App\Rules\EmailAddressRule;
use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                new EmailAddressRule,
            ],
        ];
    }

    public function getEmail()
    {
        return $this->input('email', null);
    }
}
