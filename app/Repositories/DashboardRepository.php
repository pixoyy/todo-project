<?php

namespace App\Repositories;

use App\Models\Project;
use App\Models\Category;
use App\Models\Task;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;

class DashboardRepository{

    public function getData($request){
        // Get total counts
        $totalProjects = Project::count();
        $totalCategories = Category::count();
        $totalTasks = Task::count();
        $totalUsers = Admin::count();

        // Get tasks by status
        $tasksByStatus = Task::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $todoTasks = $tasksByStatus['todo'] ?? 0;
        $inProgressTasks = $tasksByStatus['in_progress'] ?? 0;
        $reviewTasks = $tasksByStatus['review'] ?? 0;
        $doneTasks = $tasksByStatus['done'] ?? 0;

        // Get tasks by priority
        $tasksByPriority = Task::select('priority', DB::raw('count(*) as total'))
            ->groupBy('priority')
            ->pluck('total', 'priority')
            ->toArray();

        $lowPriority = $tasksByPriority['low'] ?? 0;
        $mediumPriority = $tasksByPriority['medium'] ?? 0;
        $highPriority = $tasksByPriority['high'] ?? 0;

        // Show only active projects for dashboard cards
        $activeProjectCards = Project::query()
            ->where('projects.status', 1)
            ->withCount([
                'categories',
                'tasks',
                'tasks as done_tasks_count' => function ($query) {
                    $query->where('tasks.status', 'done');
                },
            ])
            ->with([
                'categories' => function ($query) {
                    $query->select('id', 'project_id', 'name', 'color', 'order', 'status')
                        ->orderBy('order')
                        ->withCount('tasks');
                },
            ])
            ->orderByDesc('tasks_count')
            ->orderByDesc('id')
            ->limit(9)
            ->get();

        // Get active/inactive counters
        $activeProjects = Project::where('projects.status', 1)->count();
        $inactiveProjects = Project::where('projects.status', 0)->count();

        return [
            'totalProjects' => $totalProjects,
            'totalCategories' => $totalCategories,
            'totalTasks' => $totalTasks,
            'totalUsers' => $totalUsers,
            'activeProjects' => $activeProjects,
            'inactiveProjects' => $inactiveProjects,
            'todoTasks' => $todoTasks,
            'inProgressTasks' => $inProgressTasks,
            'reviewTasks' => $reviewTasks,
            'doneTasks' => $doneTasks,
            'lowPriority' => $lowPriority,
            'mediumPriority' => $mediumPriority,
            'highPriority' => $highPriority,
            'activeProjectCards' => $activeProjectCards,
        ];
    }
}
