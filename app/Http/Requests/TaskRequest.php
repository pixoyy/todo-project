<?php

namespace App\Http\Requests;

use App\Repositories\TaskRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => 'required|exists:projects,id',
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'status' => ['required', Rule::in(['todo', 'in_progress', 'review', 'done'])],
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
            'due_date' => 'nullable|date',
            'assigned_admin_id' => 'nullable|exists:admins,id',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (!$this->filled('project_id') || !$this->filled('category_id')) {
                return;
            }

            $repo = new TaskRepository();
            if (!$repo->categoryBelongsToProject($this->category_id, $this->project_id)) {
                $validator->errors()->add('category_id', 'Category tidak sesuai dengan project yang dipilih.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'project_id.required' => 'Project wajib dipilih.',
            'project_id.exists' => 'Project tidak valid.',
            'category_id.required' => 'Category wajib dipilih.',
            'category_id.exists' => 'Category tidak valid.',
            'title.required' => 'Judul task wajib diisi.',
            'title.max' => 'Judul task maksimal 150 karakter.',
            'status.required' => 'Status task wajib dipilih.',
            'status.in' => 'Status task tidak valid.',
            'priority.required' => 'Prioritas task wajib dipilih.',
            'priority.in' => 'Prioritas task tidak valid.',
            'due_date.date' => 'Tanggal jatuh tempo tidak valid.',
            'assigned_admin_id.exists' => 'Assignee tidak valid.',
        ];
    }
}
