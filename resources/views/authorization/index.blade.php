@extends('main')

@section('main-content')
    <x-custom-card>
        <div class="row g-2 align-items-center mb-4">
            <div class="col-12 col-lg-3">
                <select id="role" name="role" class="form-select border-grey">
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-lg-9">
                <div class="d-flex align-items-center justify-content-start justify-content-lg-end">
                    @can('access', 'update')
                        <button type="button" id="btn-save" class="btn btn-blue d-none text-nowrap" disabled>Simpan Perubahan</button>
                    @endcan
                </div>
            </div>
        </div>
        <div id="records">
            <table class="table">
                <thead>
                    <tr class="table-blue">
                        <th scope="col" width="50%">Menu</th>
                        <th scope="col" class="text-center">Semua</th>
                        <th scope="col" class="text-center">Read</th>
                        <th scope="col" class="text-center">Create</th>
                        <th scope="col" class="text-center">Update</th>
                        <th scope="col" class="text-center">Delete</th>
                    </tr>
                </thead>
                <tbody>{!! $data !!}</tbody>
            </table>
        </div>
    </x-custom-card>
@endsection

@push('js')
    <script>
        $('#records tbody').on('change', 'input[name="authorization_all"]', function () {
            const parent = $(this).closest('tr');
            if ($(this).is(':checked')) {
                parent.find('input[name="authorization"]').prop('checked', true);
            } else {
                parent.find('input[name="authorization"]').prop('checked', false);
            }
        });

        $('#records tbody').on('change', 'input[name="authorization"]', function () {
            const parent = $(this).closest('tr');
            if (parent.find('input[name="authorization"]:checked').length < 4) {
                parent.find('input[name="authorization_all"]').prop('checked', false);
            } else {
                parent.find('input[name="authorization_all"]').prop('checked', true);
            }
        });

        $('#role').on('change', function () {
            const roleId = $(this).val();
            $.ajax({
                url: "{{ route('authorization_change_role') }}",
                method: 'GET',
                data: {
                    id: $(this).val(),
                },
                beforeSend: () => {
                    loading();
                },
                success: (data) => {
                    $('tbody').html(data);
                    if (roleId == 1) {
                        $('#btn-save').prop('disabled', true).addClass('d-none');
                        $('input[type="checkbox"]').prop('disabled', true);
                    } else {
                        $('#btn-save').prop('disabled', false).removeClass('d-none');
                        $('input[type="checkbox"]').prop('disabled', false);
                    }
                },
                error: (jqXHR, status, err) => {
                    toastr.error('Terjadi kesalahan! Silakan coba lagi!');
                },
                complete: () => {
                    doneLoading();
                },
            });
        });

        $('#btn-save').on('click', function () {
            const authorizations = {};
            $('#records tbody tr').each(function () {
                const moduleId = $(this).data('module');
                authorizations[`${moduleId}`] = $(this).find('input[name="authorization"]:checked').map((_, el) => $(el).val()).get();
            });
            
            $.ajax({
                url: "{{ route('authorization_save') }}",
                method: 'PATCH',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    role_id: $('#role').val(),
                    authorizations: authorizations,
                },
                beforeSend: () => {
                    loading();
                },
                success: (data) => {
                    if (data.status == true) {
                        Swal.fire({
                            title: 'Berhasil menyimpan perubahan otorisasi!',
                            text: 'Perubahan dapat terlihat setelah melakukan refresh!',
                            icon: 'success',
                            confirmButtonColor: 'green',
                            confirmButtonText: 'Refresh!',
                        }).then(result => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                    } else {
                        toastr.error(data.message);
                    }
                },
                error: (jqXHR, status, err) => {
                    toastr.error('Terjadi kesalahan! Silakan coba lagi!');
                },
                complete: () => {
                    doneLoading();
                },
            });
        });
    </script>
@endpush