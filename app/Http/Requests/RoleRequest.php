<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
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
        $rules = [];
        if ($this->routeIs('role_update')) {
            $rules = [
                'name' => ['required', 'max:100', Rule::unique('roles', 'name')->ignore($this->id)->whereNull('deleted_at')],
            ];
        }

        return [
            'name' => ['required', 'max:100', Rule::unique('roles', 'name')->whereNull('deleted_at')],
            'status' => 'nullable|in:0,1',
            ...$rules,
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Nama peran ini sudah ada',
            'name.*' => 'Nama peran harus diisi (max: 100)',
            'status' => 'Status tidak valid',
        ];
    }
}
