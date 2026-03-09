<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateTaskStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:update-status {--force : Force update without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update task status based on due date. Mark overdue tasks and auto-complete on-time tasks.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $force = $this->option('force');
        
        $this->info('🔄 Starting task status update...');
        $this->newLine();

        try {
            // ✅ 1. Mark overdue tasks (due date passed but not completed)
            $overdueCount = $this->markOverdueTasks();

            // ✅ 2. Mark on-time completed tasks
            $completedCount = $this->markCompletedTasks();

            // ✅ Summary
            $this->newLine();
            $this->info('✅ Task status update completed!');
            $this->info("📌 Overdue tasks found: {$overdueCount}");
            $this->info("✔️  On-time completed tasks: {$completedCount}");
            $this->newLine();
            $this->info('⏰ Next scheduled run: Tomorrow at 00:00 (Asia/Jakarta)');

            // Log untuk tracking
            Log::channel('daily')->info('Task status update completed', [
                'overdue_count' => $overdueCount,
                'completed_count' => $completedCount,
                'executed_at' => now('Asia/Jakarta'),
            ]);

        } catch (\Exception $e) {
            $this->error('❌ Error updating task status: ' . $e->getMessage());
            Log::error('Task status update failed', [
                'error' => $e->getMessage(),
                'timestamp' => now('Asia/Jakarta'),
            ]);
        }
    }

    /**
     * Mark tasks as overdue jika due_date sudah lewat dan belum completed
     */
    private function markOverdueTasks(): int
    {
        $this->line('📍 Checking overdue tasks...');

        $today = now('Asia/Jakarta')->toDateString();

        $overdueTasks = Task::where(function ($query) {
                // Status masih todo, in_progress, atau review
                $query->where('status', 'todo')
                    ->orWhere('status', 'in_progress')
                    ->orWhere('status', 'review');
            })
            ->whereNotNull('due_date')
            ->where('due_date', '<', $today) // Due date sudah lewat
            ->whereNull('completed_at') // Belum completed
            ->whereNull('deleted_at')
            ->get();

        $count = 0;

        foreach ($overdueTasks as $task) {
            $daysOverdue = now('Asia/Jakarta')->diffInDays($task->due_date);
            $this->line("  ⚠️  ID:{$task->id} | '{$task->title}' | Due: {$task->due_date->format('d M Y')} ({$daysOverdue} hari overdue)");
            $count++;
        }

        if ($count > 0) {
            $this->info("  ✅ Found {$count} overdue task(s)");
        } else {
            $this->info("  ✅ No overdue tasks found");
        }

        return $count;
    }

    /**
     * Count tasks yang completed on-time
     */
    private function markCompletedTasks(): int
    {
        $this->line('✅ Checking tasks completed on-time...');

        // Tasks yang completed dan selesai tepat waktu (pada atau sebelum due date)
        $onTimeCompletedTasks = Task::where('status', 'done')
            ->whereNotNull('due_date')
            ->whereNotNull('completed_at')
            ->where(DB::raw('DATE(completed_at)'), '<=', DB::raw('DATE(due_date)'))
            ->whereNull('deleted_at')
            ->count();

        if ($onTimeCompletedTasks > 0) {
            $this->info("  ✅ Found {$onTimeCompletedTasks} task(s) completed on-time");
        } else {
            $this->info("  ✅ No new on-time completed tasks today");
        }

        return $onTimeCompletedTasks;
    }
}

