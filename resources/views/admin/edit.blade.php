@extends('main')

@section('main-content')
    <x-custom-card>
        <form class="row g-3" method="POST" action="{{ route('users_update', $user->id) }}">
            @csrf
            @method('PATCH')

            <div class="col-12 col-md-6">
                <label for="role_id" class="form-label">Role</label>
                <select name="role_id" id="role_id" class="form-select border-grey @error('role_id') is-invalid @enderror" required>
                    <option value="">Pilih role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id) == $role->id)>{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('role_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6">
                <label for="name" class="form-label">Nama</label>
                <input type="text" name="name" id="name" class="form-control border-grey @error('name') is-invalid @enderror"
                    value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control border-grey @error('email') is-invalid @enderror"
                    value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6">
                <label for="phone_number" class="form-label">Nomor Telepon</label>
                <input type="text" name="phone_number" id="phone_number"
                    class="form-control border-grey @error('phone_number') is-invalid @enderror"
                    value="{{ old('phone_number', $user->phone_number) }}" required>
                @error('phone_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6">
                <label for="password" class="form-label">Password Baru (opsional)</label>
                <input type="password" name="password" id="password"
                    class="form-control border-grey @error('password') is-invalid @enderror">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6">
                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control border-grey">
            </div>

            <div class="col-12 col-md-4">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select border-grey @error('status') is-invalid @enderror" required>
                    <option value="1" @selected(old('status', $user->status) == 1)>Aktif</option>
                    <option value="0" @selected((string) old('status', $user->status) === '0')>Tidak Aktif</option>
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
@endpush
