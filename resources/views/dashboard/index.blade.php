@extends('main')

@section('main-content')
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <h2 class="mb-0">Dashboard</h2>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <x-custom-card>
                <div class="text-muted small">Total Project</div>
                <div class="fs-4 fw-bold">{{ $totalProjects }}</div>
                <div class="small text-success">{{ $activeProjects }} aktif</div>
            </x-custom-card>
        </div>

        <div class="col-6 col-lg-3">
            <x-custom-card>
                <div class="text-muted small">Total Task</div>
                <div class="fs-4 fw-bold">{{ $totalTasks }}</div>
                <div class="small text-primary">{{ $doneTasks }} selesai</div>
            </x-custom-card>
        </div>

        <div class="col-6 col-lg-3">
            <x-custom-card>
                <div class="text-muted small">Total Category</div>
                <div class="fs-4 fw-bold">{{ $totalCategories }}</div>
                <div class="small text-secondary">Struktur task</div>
            </x-custom-card>
        </div>

        <div class="col-6 col-lg-3">
            <x-custom-card>
                <div class="text-muted small">Total User</div>
                <div class="fs-4 fw-bold">{{ $totalUsers }}</div>
                <div class="small text-info">Team terdaftar</div>
            </x-custom-card>
        </div>
    </div>

    <x-custom-card>
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <h5 class="mb-0">Project Aktif</h5>
            <span class="text-muted small">Klik project untuk melihat category dan daftar task per category</span>
        </div>

        @if ($activeProjectCards->isEmpty())
            <div class="alert alert-light border mb-0">Belum ada project aktif.</div>
        @else
            <div class="row g-3">
                @foreach ($activeProjectCards as $project)
                    @php
                        $completion = $project->tasks_count > 0 ? round(($project->done_tasks_count / $project->tasks_count) * 100) : 0;
                        $activeCategories = $project->categories->where('status', 1);
                    @endphp

                    <div class="col-12 col-md-6 col-xxl-4">
                        <a href="{{ route('projects_detail', $project->id) }}" class="project-active-card text-decoration-none text-reset d-block h-100">
                            <div class="border rounded-3 p-3 h-100 bg-white">
                                <div class="d-flex align-items-start justify-content-between gap-2 mb-1">
                                    <div class="fw-semibold">{{ \Illuminate\Support\Str::limit($project->name, 42) }}</div>
                                    <span class="badge bg-primary rounded-pill">{{ $project->tasks_count }} task</span>
                                </div>

                                <div class="small text-muted mb-2">
                                    {{ $project->categories_count }} category • {{ $project->done_tasks_count }} selesai
                                </div>

                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $completion }}%" aria-valuenow="{{ $completion }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="small text-muted mt-1">Progress {{ $completion }}%</div>

                                <div class="d-flex flex-wrap gap-1 mt-3">
                                    @forelse ($activeCategories->take(4) as $category)
                                        @php
                                            $categoryColor = !empty($category->color) ? $category->color : '#6c757d';
                                        @endphp
                                        <span class="category-pill">
                                            <span class="category-dot" style="background-color: {{ $categoryColor }};"></span>
                                            <span>{{ \Illuminate\Support\Str::limit($category->name, 14) }}</span>
                                            <span class="text-muted">({{ $category->tasks_count }})</span>
                                        </span>
                                    @empty
                                        <span class="text-muted small">Belum ada category aktif</span>
                                    @endforelse

                                    @if ($activeCategories->count() > 4)
                                        <span class="badge bg-light text-dark">+{{ $activeCategories->count() - 4 }} lainnya</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </x-custom-card>

    <div class="row g-3 mt-1">
        <div class="col-12 col-lg-7">
            <x-custom-card>
                <h6 class="mb-3">Ringkasan Status Task</h6>
                <div class="row g-2 text-center">
                    <div class="col-6 col-md-3">
                        <div class="p-2 rounded border bg-light text-secondary">
                            <div class="fw-bold">{{ $todoTasks }}</div>
                            <small>To Do</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-2 rounded border bg-light text-primary">
                            <div class="fw-bold">{{ $inProgressTasks }}</div>
                            <small>In Progress</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-2 rounded border bg-light text-warning">
                            <div class="fw-bold">{{ $reviewTasks }}</div>
                            <small>Review</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-2 rounded border bg-light text-success">
                            <div class="fw-bold">{{ $doneTasks }}</div>
                            <small>Done</small>
                        </div>
                    </div>
                </div>
            </x-custom-card>
        </div>

        <div class="col-12 col-lg-5">
            <x-custom-card>
                <h6 class="mb-3">Ringkasan Prioritas</h6>
                @php
                    $priorityTotal = max($lowPriority + $mediumPriority + $highPriority, 1);
                @endphp
                <div class="d-flex flex-column gap-2">
                    <div>
                        <div class="d-flex justify-content-between small mb-1"><span>Low</span><span>{{ $lowPriority }}</span></div>
                        <div class="progress" style="height: 6px;"><div class="progress-bar bg-success" style="width: {{ round(($lowPriority / $priorityTotal) * 100) }}%"></div></div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between small mb-1"><span>Medium</span><span>{{ $mediumPriority }}</span></div>
                        <div class="progress" style="height: 6px;"><div class="progress-bar bg-warning" style="width: {{ round(($mediumPriority / $priorityTotal) * 100) }}%"></div></div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between small mb-1"><span>High</span><span>{{ $highPriority }}</span></div>
                        <div class="progress" style="height: 6px;"><div class="progress-bar bg-danger" style="width: {{ round(($highPriority / $priorityTotal) * 100) }}%"></div></div>
                    </div>
                </div>
            </x-custom-card>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .project-active-card > div {
            transition: all 0.2s ease;
        }

        .project-active-card:hover > div {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
            border-color: #86b7fe !important;
        }

        .category-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 2px 8px;
            border-radius: 999px;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            font-size: 12px;
            line-height: 1.4;
        }

        .category-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }
    </style>
@endpush