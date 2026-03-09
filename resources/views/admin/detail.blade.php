@extends('main')

@section('main-content')
    <x-custom-card>
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <div class="mb-2 text-muted">Nama</div>
                <div class="fw-semibold">{{ $user->name }}</div>
            </div>

            <div class="col-12 col-md-6">
                <div class="mb-2 text-muted">Role</div>
                <div>{{ $user->role->name ?? '-' }}</div>
            </div>

            <div class="col-12 col-md-6">
                <div class="mb-2 text-muted">Email</div>
                <div>{{ $user->email }}</div>
            </div>

            <div class="col-12 col-md-6">
                <div class="mb-2 text-muted">Nomor Telepon</div>
                <div>{{ $user->phone_number }}</div>
            </div>

            <div class="col-12 col-md-6">
                <div class="mb-2 text-muted">Status</div>
                @if ($user->status == 1)
                    <span class="badge bg-success">Aktif</span>
                @else
                    <span class="badge bg-secondary">Tidak Aktif</span>
                @endif
            </div>

            <div class="col-12 col-md-6">
                <div class="mb-2 text-muted">Last Login</div>
                <div>{{ $user->last_login ? $user->last_login->format('d M Y H:i') : '-' }}</div>
            </div>

            <div class="col-12 text-end mt-4">
                <a href="{{ route('users') }}" class="btn btn-outline-secondary">Kembali</a>
                @can('access', 'update')
                    <a href="{{ route('users_edit', $user->id) }}" class="btn btn-blue">Edit</a>
                @endcan
            </div>
        </div>
    </x-custom-card>
@endsection
