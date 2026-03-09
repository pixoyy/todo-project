@extends('main')

@section('main-content')
    <x-custom-card>
        <div class="row g-3">
            <div class="col-12 col-md-7">
                <div class="mb-2 text-muted">Judul</div>
                <div class="fw-semibold">{{ $task->title }}</div>
            </div>

            <div class="col-12 col-md-5">
                <div class="mb-2 text-muted">Project / Category</div>
                <div>{{ $task->category->project->name ?? '-' }} / {{ $task->category->name ?? '-' }}</div>
            </div>

            <div class="col-12 col-md-3">
                <div class="mb-2 text-muted">Status</div>
                <div>{{ str_replace('_', ' ', strtoupper($task->status)) }}</div>
            </div>

            <div class="col-12 col-md-3">
                <div class="mb-2 text-muted">Prioritas</div>
                <div>{{ strtoupper($task->priority) }}</div>
            </div>

            <div class="col-12 col-md-3">
                <div class="mb-2 text-muted">Due Date</div>
                <div>{{ $task->due_date ? $task->due_date->format('d M Y') : '-' }}</div>
            </div>

            <div class="col-12 col-md-3">
                <div class="mb-2 text-muted">Completed At</div>
                <div>{{ $task->completed_at ? $task->completed_at->format('d M Y H:i') : '-' }}</div>
            </div>

            <div class="col-12 col-md-6">
                <div class="mb-2 text-muted">Assignee</div>
                <div>{{ $task->assignedAdmin->name ?? '-' }}</div>
            </div>

            <div class="col-12 col-md-6">
                <div class="mb-2 text-muted">Dibuat Oleh</div>
                <div>{{ $task->createdByAdmin->name ?? '-' }}</div>
            </div>

            <div class="col-12">
                <div class="mb-2 text-muted">Deskripsi</div>
                <div>{!! $task->description ?: '-' !!}</div>
            </div>

            <div class="col-12 text-end mt-4">
                <a href="{{ route('tasks') }}" class="btn btn-outline-secondary">Kembali</a>
                @can('access', 'update')
                    <a href="{{ route('tasks_edit', $task->id) }}" class="btn btn-blue">Edit</a>
                @endcan
            </div>
        </div>
    </x-custom-card>
@endsection
