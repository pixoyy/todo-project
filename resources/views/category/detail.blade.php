@extends('main')

@section('main-content')
    <x-custom-card>
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <div class="mb-2 text-muted">Project</div>
                <div class="fw-semibold">{{ $category->project->name ?? '-' }}</div>
            </div>

            <div class="col-12 col-md-6">
                <div class="mb-2 text-muted">Nama Category</div>
                <div class="fw-semibold">{{ $category->name }}</div>
            </div>

            <div class="col-12 col-md-4">
                <div class="mb-2 text-muted">Warna</div>
                @if (!empty($category->color))
                    <div class="d-inline-flex align-items-center gap-2">
                        <span style="width: 14px; height: 14px; border-radius: 50%; background-color: {{ $category->color }}; display: inline-block;"></span>
                        {{ $category->color }}
                    </div>
                @else
                    <div>-</div>
                @endif
            </div>

            <div class="col-12 col-md-4">
                <div class="mb-2 text-muted">Urutan</div>
                <div>{{ $category->order }}</div>
            </div>

            <div class="col-12 col-md-4">
                <div class="mb-2 text-muted">Jumlah Task</div>
                <div>{{ $category->tasks_count }}</div>
            </div>

            <div class="col-12 col-md-4">
                <div class="mb-2 text-muted">Status</div>
                @if ($category->status == 1)
                    <span class="badge bg-success">Aktif</span>
                @else
                    <span class="badge bg-secondary">Tidak Aktif</span>
                @endif
            </div>

            <div class="col-12 text-end mt-4">
                <a href="{{ route('categories') }}" class="btn btn-outline-secondary">Kembali</a>
                @can('access', 'update')
                    <a href="{{ route('categories_edit', $category->id) }}" class="btn btn-blue">Edit</a>
                @endcan
            </div>
        </div>
    </x-custom-card>
@endsection
