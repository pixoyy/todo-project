<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'project_id' => 'required|exists:projects,id',
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories', 'name')
                    ->where(fn ($q) => $q->where('project_id', $this->project_id)->whereNull('deleted_at'))
                    ->ignore($id),
            ],
            'color' => 'nullable|string|max:20',
            'order' => [
                'nullable',
                'integer',
                'min:0',
                Rule::unique('categories', 'order')
                    ->where(fn ($q) => $q->where('project_id', $this->project_id)->whereNull('deleted_at'))
                    ->ignore($id),
            ],
            'status' => 'required|integer|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'project_id.required' => 'Project wajib dipilih.',
            'project_id.exists' => 'Project tidak valid.',
            'name.required' => 'Nama category wajib diisi.',
            'name.max' => 'Nama category maksimal 100 karakter.',
            'name.unique' => 'Nama category sudah digunakan pada project ini.',
            'color.max' => 'Kode warna maksimal 20 karakter.',
            'order.integer' => 'Urutan harus berupa angka.',
            'order.min' => 'Urutan minimal 0.',
            'order.unique' => 'Urutan sudah digunakan pada project ini.',
            'status.required' => 'Status category wajib dipilih.',
            'status.in' => 'Status category tidak valid.',
        ];
    }
}
