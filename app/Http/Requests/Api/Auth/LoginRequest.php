<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'username' => 'required|max:100',
            'password' => 'required',
            'login_key' => 'required|in:phone,email'
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'username' => is_numeric($this->username) ? convert_arabic_number($this->username) : $this->username,
            'login_key' => filter_var($this->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone'
        ]);
    }
}
