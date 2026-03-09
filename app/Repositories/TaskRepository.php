<?php

namespace App\Repositories;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskRepository
{
    public function getData($request, $n = 10)
    {
        $search = $request->query('query');
        $status = $request->query('status');
        $priority = $request->query('priority');
        $projectId = $request->query('project_id');
        $categoryId = $request->query('category_id');
        $assignedAdminId = $request->query('assigned_admin_id');

        return Task::with(['category.project', 'assignedAdmin', 'createdByAdmin'])
            ->when(!empty($search), function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%$search%")
                        ->orWhere('description', 'LIKE', "%$search%")
                        ->orWhereHas('category', function ($c) use ($search) {
                            $c->where('name', 'LIKE', "%$search%")
                                ->orWhereHas('project', function ($p) use ($search) {
                                    $p->where('name', 'LIKE', "%$search%");
                                });
                        });
                });
            })
            ->when(!empty($status), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when(!empty($priority), function ($query) use ($priority) {
                $query->where('priority', $priority);
            })
            ->when(!empty($projectId), function ($query) use ($projectId) {
                $query->whereHas('category', fn ($c) => $c->where('project_id', $projectId));
            })
            ->when(!empty($categoryId), function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when(!empty($assignedAdminId), function ($query) use ($assignedAdminId) {
                $query->where('assigned_admin_id', $assignedAdminId);
            })
            ->orderByRaw("FIELD(status, 'todo', 'in_progress', 'review', 'done')")
            ->orderBy('due_date')
            ->orderByDesc('id')
            ->paginate($n);
    }

    public function getById($id)
    {
        return Task::with(['category.project', 'assignedAdmin', 'createdByAdmin'])->find($id);
    }

    public function getFormOptions(): array
    {
        return [
            'projects' => Project::with(['categories' => function ($q) {
                $q->orderBy('order')->orderBy('name');
            }])->orderBy('name')->get(['id', 'name']),
            'admins' => Admin::with(['role:id,name'])
                ->where('status', 1)
                ->orderBy('name')
                ->get(['id', 'name', 'role_id']),
        ];
    }

    public function create($request)
    {
        Task::create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'completed_at' => $request->status === 'done' ? now() : null,
            'assigned_admin_id' => $request->assigned_admin_id,
            'created_by_admin_id' => Auth::id(),
        ]);
    }

    public function update($id, $request)
    {
        $task = Task::find($id);
        if (!$task) {
            return [
                'status' => false,
                'message' => 'Task tidak ditemukan!',
            ];
        }

        $task->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'completed_at' => $request->status === 'done' ? ($task->completed_at ?? now()) : null,
            'assigned_admin_id' => $request->assigned_admin_id,
        ]);

        return [
            'status' => true,
            'message' => 'Task berhasil diperbarui!',
        ];
    }

    public function delete($id)
    {
        $task = Task::find($id);
        if (!$task) {
            return [
                'status' => false,
                'message' => 'Task tidak ditemukan!',
            ];
        }

        $task->delete();

        return [
            'status' => true,
            'message' => 'Task berhasil dihapus!',
        ];
    }

    public function categoryBelongsToProject($categoryId, $projectId): bool
    {
        return Category::where('id', $categoryId)->where('project_id', $projectId)->exists();
    }
}
