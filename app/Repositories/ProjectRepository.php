<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectRepository
{
    public function getData($request, $n = 10)
    {
        $search = $request->query('query');
        $status = $request->query('status');

        $projects = Project::withCount('categories')->when(!empty($search), function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                    ->orWhere('description', 'LIKE', "%$search%");
            });
        })->when(!empty($status) || $status === 0 || $status === "0", function ($query) use ($status) {
            $query->where('status', $status);
        })->orderByDesc('status')->orderByDesc('id')->paginate($n);

        return $projects;
    }

    public function getById($id)
    {
        $project = Project::find($id);
        return $project;
    }

    public function create($request)
    {
        Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => isset($request->status) ? $request->status : 0,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'admin_id' => Auth::id(),
        ]);
    }

    public function update($id, $request)
    {
        Project::where('id', $id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => isset($request->status) ? $request->status : 0,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'admin_id' => Auth::id(),
        ]);
    }

    public function delete($id)
    {
        $project = Project::find($id);
        if (!$project) {
            return [
                'status' => false,
                'message' => 'Project tidak ditemukan!',
            ];
        }

        $hasCategories = Category::where('project_id', $id)->exists();
        if ($hasCategories) {
            return [
                'status' => false,
                'message' => 'Project tidak dapat dihapus karena masih memiliki category.',
            ];
        }

        $project->delete();

        return [
            'status' => true,
            'message' => 'Project berhasil dihapus!',
        ];
    }
}
