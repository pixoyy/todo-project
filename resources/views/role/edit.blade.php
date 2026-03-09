@extends('main')

@section('main-content')
    <x-custom-card>
        <form id="form-edit" class="row g-3" method="POST" action="{{ route('role_update', $role->id) }}">
            @method('PATCH')
            @csrf
            <div class="col-12">
                <label for="name" class="form-label">Nama Peran</label>
                <input type="text" name="name" class="form-control border-grey @error('name') is-invalid @enderror"
                    id="name" value="{{ $role->name }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-4">
                <label for="status" class="form-label">Status</label>
                <select name="status" class="form-select border-grey @error('status') is-invalid @enderror" id="status" required>
                    <option value="1" @selected($role->status == 1)>Aktif</option>
                    <option value="0" @selected($role->status === 0)>Tidak Aktif</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 mt-5 text-end">
                <a href="{{ route('role') }}" class="btn btn-secondary">Back</a>
                <button id="btn-submit" class="btn btn-blue" type="button">Simpan</button>
            </div>
        </form>
    </x-custom-card>
@endsection

@push('js')
    <script>
        @if (session()->has('message'))
            toastr.error("{{ session()->get('message') }}");
        @endif

        $('#btn-submit').on('click', function () {
            if ($('#status').val() == 0) {
                showSaveAlert('Semua akun staff dengan peran ini juga akan dinonaktifkan!', () => $('#form-edit').submit());
            } else {
                $('#form-edit').submit();
            }
        });
    </script>
@endpush
