@extends('main')

@section('main-content')
    <x-custom-card>
        @php
            $totalTasks = $project->categories->sum('tasks_count');
            $doneTasks = $project->categories->sum(function ($category) {
                return $category->tasks->where('status', 'done')->count();
            });
            $completion = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 0;
        @endphp

        <div class="row g-3">
            <div class="col-12 col-md-6">
                <div class="mb-2 text-muted">Nama Project</div>
                <div class="fw-semibold">{{ $project->name }}</div>
            </div>

            <div class="col-12 col-md-3">
                <div class="mb-2 text-muted">Status</div>
                @if ($project->status == 1)
                    <span class="badge bg-success">Aktif</span>
                @else
                    <span class="badge bg-secondary">Tidak Aktif</span>
                @endif
            </div>

            <div class="col-12 col-md-3">
                <div class="mb-2 text-muted">Jumlah Category</div>
                <div class="fw-semibold">{{ $project->categories->count() }}</div>
            </div>

            <div class="col-12 col-md-3">
                <div class="mb-2 text-muted">Total Task</div>
                <div class="fw-semibold">{{ $totalTasks }}</div>
            </div>

            <div class="col-12 col-md-3">
                <div class="mb-2 text-muted">Progress Selesai</div>
                <div class="fw-semibold">{{ $completion }}%</div>
            </div>

            <div class="col-12 col-md-6">
                <div class="mb-2 text-muted">Tanggal Mulai</div>
                <div>{{ optional($project->start_date)->format('d M Y') ?? '-' }}</div>
            </div>

            <div class="col-12 col-md-6">
                <div class="mb-2 text-muted">Tanggal Selesai</div>
                <div>{{ optional($project->end_date)->format('d M Y') ?? '-' }}</div>
            </div>

            <div class="col-12">
                <div class="mb-2 text-muted">Deskripsi</div>
                <div>{!! $project->description ?: '-' !!}</div>
            </div>

            <div class="col-12 mt-2">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                    <h5 class="mb-0">Category & Task</h5>
                    <span class="text-muted small">Klik dari dashboard untuk melihat detail kerja per category</span>
                </div>

                @forelse ($project->categories as $category)
                    @php
                        $categoryColor = !empty($category->color) ? $category->color : '#6c757d';
                        $categoryTaskCount = $category->tasks->count();
                    @endphp

                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-white" style="border-left: 6px solid {{ $categoryColor }};">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="rounded-circle" style="width: 12px; height: 12px; background-color: {{ $categoryColor }};"></span>
                                    <span class="fw-semibold">{{ $category->name }}</span>
                                    @if ($category->status == 1)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                    @endif
                                </div>

                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span class="badge text-dark" style="background-color: {{ $categoryColor }}22; border: 1px solid {{ $categoryColor }}66;">
                                        {{ $categoryTaskCount }} task
                                    </span>
                                    <a href="{{ route('categories_detail', $category->id) }}" class="btn btn-sm btn-outline-secondary">Detail Category</a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body pt-2">
                            @if ($category->tasks->isEmpty())
                                <div class="text-muted py-2">Belum ada task pada category ini.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Task</th>
                                                <th>Assignee</th>
                                                <th>Prioritas</th>
                                                <th>Status</th>
                                                <th>Due Date</th>
                                                <th class="text-end">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($category->tasks as $task)
                                                @php
                                                    $priorityClass = [
                                                        'low' => 'success',
                                                        'medium' => 'warning',
                                                        'high' => 'danger',
                                                    ][$task->priority] ?? 'secondary';

                                                    $statusClass = [
                                                        'todo' => 'secondary',
                                                        'in_progress' => 'primary',
                                                        'review' => 'warning',
                                                        'done' => 'success',
                                                    ][$task->status] ?? 'secondary';
                                                @endphp

                                                <tr>
                                                    <td>
                                                        <div class="fw-semibold">{{ $task->title }}</div>
                                                        @if (!empty($task->description))
                                                            <small class="text-muted">{{ \Illuminate\Support\Str::limit(strip_tags($task->description), 70) }}</small>
                                                        @endif
                                                    </td>
                                                    <td>{{ $task->assignedAdmin->name ?? '-' }}</td>
                                                    <td><span class="badge bg-{{ $priorityClass }}">{{ strtoupper($task->priority) }}</span></td>
                                                    <td><span class="badge bg-{{ $statusClass }}">{{ str_replace('_', ' ', strtoupper($task->status)) }}</span></td>
                                                    <td>
                                                        @if ($task->due_date)
                                                            {{ $task->due_date->format('d M Y') }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
                                                        <a href="{{ route('tasks_detail', $task->id) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="alert alert-light border">Belum ada category pada project ini.</div>
                @endforelse
            </div>

            <div class="col-12 text-end mt-4">
                <a href="{{ route('projects') }}" class="btn btn-outline-secondary">Kembali</a>
                @can('access', 'update')
                    <a href="{{ route('projects_edit', $project->id) }}" class="btn btn-blue">Edit</a>
                @endcan
            </div>
        </div>
    </x-custom-card>
@endsection
