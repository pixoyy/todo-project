@extends('main')

@section('main-content')
    <x-custom-card>
        <form class="row g-3" method="POST" action="{{ route('roles_create') }}">
            @csrf
            <div class="col-12">
                <label for="name" class="form-label">Nama Peran</label>
                <input type="text" name="name" class="form-control border-grey @error('name') is-invalid @enderror"
                    id="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-4">
                <label for="status" class="form-label">Status</label>
                <select name="status" class="form-select border-grey @error('status') is-invalid @enderror" id="status" required>
                    <option value="1" @selected(old('status') == 1)>Aktif</option>
                    <option value="0" @selected(old('status') === 0)>Tidak Aktif</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 mt-5 text-end">
                  <a href="{{ route('roles') }}" class="btn btn-outline-secondary">Kembali</a>
                <button class="btn btn-blue" type="submit">Simpan</button>
            </div>
        </form>
    </x-custom-card>
@endsection

@if (session()->has('message'))
    @push('js')
        <script>
            toastr.error("{{ session()->get('message') }}");
        </script>
    @endpush
@endif
