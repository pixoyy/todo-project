@extends('main')

@section('main-content')
    <x-custom-card>
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
                <div class="fw-semibold">{{ $project->categories()->count() }}</div>
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

            <div class="col-12 text-end mt-4">
                <a href="{{ route('projects') }}" class="btn btn-outline-secondary">Kembali</a>
                @can('access', 'update')
                    <a href="{{ route('projects_edit', $project->id) }}" class="btn btn-blue">Edit</a>
                @endcan
            </div>
        </div>
    </x-custom-card>
@endsection
