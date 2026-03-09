<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Project;
use App\Models\Task;

class CategoryRepository
{
    public function getData($request, $n = 10)
    {
        $search = $request->query('query');
        $status = $request->query('status');
        $projectId = $request->query('project_id');

        return Category::with(['project'])
            ->withCount('tasks')
            ->when(!empty($search), function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%")
                        ->orWhereHas('project', function ($p) use ($search) {
                            $p->where('name', 'LIKE', "%$search%");
                        });
                });
            })
            ->when(!empty($status) || $status === 0 || $status === '0', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when(!empty($projectId), function ($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })
            ->orderByDesc('status')
            ->orderBy('project_id', 'asc')
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc')
            ->paginate($n);
    }

    public function getById($id)
    {
        return Category::with(['project'])->withCount('tasks')->find($id);
    }

    public function getProjectOptions()
    {
        return Project::orderBy('name')->get(['id', 'name']);
    }

    public function create($request)
    {
        // Auto-increment order: get max order + 1 for this project
        $maxOrder = Category::where('project_id', $request->project_id)->max('order') ?? -1;
        
        Category::create([
            'project_id' => $request->project_id,
            'name' => $request->name,
            'color' => $request->color,
            'order' => $request->order ?? ($maxOrder + 1),
            'status' => isset($request->status) ? $request->status : 0,
        ]);
    }

    public function update($id, $request)
    {
        Category::where('id', $id)->update([
            'project_id' => $request->project_id,
            'name' => $request->name,
            'color' => $request->color,
            'order' => $request->order ?? 0,
            'status' => isset($request->status) ? $request->status : 0,
        ]);
    }

    public function delete($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return [
                'status' => false,
                'message' => 'Category tidak ditemukan!',
            ];
        }

        $hasTasks = Task::where('category_id', $id)->exists();
        if ($hasTasks) {
            return [
                'status' => false,
                'message' => 'Category tidak dapat dihapus karena masih memiliki task.',
            ];
        }

        $category->delete();

        return [
            'status' => true,
            'message' => 'Category berhasil dihapus!',
        ];
    }

    public function getCategoriesByProject($projectId)
    {
        return Category::where('project_id', $projectId)
            ->withCount('tasks')
            ->orderBy('order')
            ->orderBy('id')
            ->get();
    }

    public function bulkUpdateOrder($orders)
    {
        foreach ($orders as $categoryId => $order) {
            Category::where('id', $categoryId)->update(['order' => $order]);
        }
    }
}
