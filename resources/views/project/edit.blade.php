@extends('main')

@section('main-content')
    <x-custom-card>
        <form id="form-project" class="row g-3" method="POST" action="{{ route('projects_update', $project->id) }}">
            @csrf
            @method('PATCH')

            <div class="col-12">
                <label for="name" class="form-label">Nama</label>
                <input type="text" name="name" class="form-control border-grey @error('name') is-invalid @enderror"
                    id="name" value="{{ old('name', $project->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6">
                <label for="start_date" class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date"
                    class="form-control border-grey @error('start_date') is-invalid @enderror" id="start_date"
                    value="{{ old('start_date', optional($project->start_date)->format('Y-m-d')) }}" required>
                @error('start_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6">
                <label for="end_date" class="form-label">Tanggal Selesai</label>
                <input type="date" name="end_date"
                    class="form-control border-grey @error('end_date') is-invalid @enderror" id="end_date"
                    value="{{ old('end_date', optional($project->end_date)->format('Y-m-d')) }}" required>
                @error('end_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label for="description">Deskripsi</label>
                <textarea name="description" id="description" cols="30" rows="10"
                    class="form-control border-grey @error('description') is-invalid @enderror">{{ old('description', $project->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-4">
                <label for="status" class="form-label">Status</label>
                <select name="status" class="form-select border-grey @error('status') is-invalid @enderror" id="status"
                    required>
                    <option value="1" @selected(old('status', $project->status) == 1)>Aktif</option>
                    <option value="0" @selected((string) old('status', $project->status) === '0')>Tidak Aktif</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 mt-5 text-end">
                <a href="{{ route('projects') }}" class="btn btn-outline-secondary">Kembali</a>
                <button class="btn btn-blue" type="submit">Simpan</button>
            </div>
        </form>
    </x-custom-card>
@endsection

@push('head.js')
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('js/ckeditor-action.js') }}"></script>
@endpush

@push('js')
    <script>
        CKEDITOR.replace('description');

        @if (session()->has('message'))
            toastr.error("{{ session()->get('message') }}");
        @endif

        $('#form-project').on('submit', function() {
            addEditorDataToForm(this, 'description');
        });
    </script>
@endpush
