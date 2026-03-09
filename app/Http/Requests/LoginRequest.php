<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|between:8,32',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($validator->errors()->has('email') || $validator->errors()->has('password')) {
            $validator->errors()->forget('email');
            $validator->errors()->forget('password');
            $validator->errors()->add('auth', 'Invalid email or password.');
        }
        
        throw new ValidationException($validator);
    }
}
