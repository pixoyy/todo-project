@extends('main')

@section('main-content')
    <x-custom-card>
        <form class="row g-3" method="POST" action="{{ route('categories_create') }}">
            @csrf

            <div class="col-12 col-md-6">
                <label for="project_id" class="form-label">Project</label>
                <select name="project_id" id="project_id"
                    class="form-select border-grey @error('project_id') is-invalid @enderror" required>
                    <option value="">Pilih project</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}" @selected(old('project_id') == $project->id)>{{ $project->name }}</option>
                    @endforeach
                </select>
                @error('project_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6">
                <label for="name" class="form-label">Nama Category</label>
                <input type="text" name="name" id="name"
                    class="form-control border-grey @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-4">
                <label for="color" class="form-label">Warna (opsional)</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="color" name="color" id="color"
                        class="form-control form-control-color border-grey @error('color') is-invalid @enderror"
                        value="{{ old('color', '#3b82f6') }}" title="Pilih warna">
                    <input type="text" id="color_preview" class="form-control border-grey" value="{{ old('color', '#3b82f6') }}"
                        readonly>
                </div>
                @error('color')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-4">
                <label for="order" class="form-label">Urutan</label>
                <input type="number" name="order" id="order"
                    class="form-control border-grey @error('order') is-invalid @enderror" value="{{ old('order', 0) }}"
                    min="0">
                @error('order')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-4">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select border-grey @error('status') is-invalid @enderror"
                    required>
                    <option value="1" @selected(old('status', 1) == 1)>Aktif</option>
                    <option value="0" @selected((string) old('status') === '0')>Tidak Aktif</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 mt-4 text-end">
                <button class="btn btn-blue" type="submit">Simpan</button>
            </div>
        </form>
    </x-custom-card>
@endsection

@push('js')
    @if (session()->has('message'))
        <script>
            toastr.error("{{ session()->get('message') }}");
        </script>
    @endif

    <script>
        $('#color').on('input', function() {
            $('#color_preview').val(this.value);
        });
    </script>
@endpush
