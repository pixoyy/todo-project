<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');
        $passwordRule = $id ? 'nullable|string|min:6|confirmed' : 'required|string|min:6|confirmed';

        return [
            'role_id' => 'required|exists:roles,id',
            'name' => 'required|string|max:100',
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('admins', 'email')->whereNull('deleted_at')->ignore($id),
            ],
            // Use digits_between to enforce integer-like input while preserving leading zero.
            'phone_number' => 'required|digits_between:8,20',
            'status' => 'required|integer|in:0,1',
            'password' => $passwordRule,
        ];
    }

    public function messages(): array
    {
        return [
            'role_id.required' => 'Role wajib dipilih.',
            'role_id.exists' => 'Role tidak valid.',
            'name.required' => 'Nama user wajib diisi.',
            'name.max' => 'Nama user maksimal 100 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan user lain.',
            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'phone_number.digits_between' => 'Nomor telepon harus berupa angka 8 sampai 20 digit.',
            'status.required' => 'Status user wajib dipilih.',
            'status.in' => 'Status user tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
        ];
    }
}
