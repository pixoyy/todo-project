<table class="table">
    <thead>
        <tr class="table-blue">
            <th>No</th>
            <th>Task</th>
            <th>Project / Category</th>
            <th>Assignee</th>
            <th>Prioritas</th>
            <th>Status</th>
            <th>Due Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($tasks as $i => $task)
            <tr>
                <x-padded-td>{{ $i + $tasks->firstItem() }}</x-padded-td>
                <x-padded-td>
                    <div class="fw-semibold">{{ $task->title }}</div>
                    @if (!empty($task->description))
                        <small class="text-muted">{!! \Illuminate\Support\Str::limit(strip_tags($task->description), 70) !!}</small>
                    @endif
                </x-padded-td>
                <x-padded-td>
                    <div>{{ $task->category->project->name ?? '-' }}</div>
                    <small class="text-muted">{{ $task->category->name ?? '-' }}</small>
                </x-padded-td>
                <x-padded-td>{{ $task->assignedAdmin->name ?? '-' }}</x-padded-td>
                <x-padded-td>{{ strtoupper($task->priority) }}</x-padded-td>
                <x-padded-td>{{ str_replace('_', ' ', strtoupper($task->status)) }}</x-padded-td>
                <x-padded-td>
                    @if ($task->due_date)
                        {{ $task->due_date->format('d M Y') }}
                        @if ($task->status !== 'done' && $task->due_date->isPast())
                            <div><small class="text-danger">Overdue</small></div>
                        @endif
                    @else
                        -
                    @endif
                </x-padded-td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <x-view-action-button :route="route('tasks_detail', $task->id)" />
                        @can('access', 'update')
                            <x-edit-action-button :route="route('tasks_edit', $task->id)" />
                        @endcan
                        @can('access', 'delete')
                            <x-delete-action-button :route="route('tasks_delete', $task->id)" />
                        @endcan
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">Task tidak ditemukan</td>
            </tr>
        @endforelse
    </tbody>
</table>
{{ $tasks->links() }}
