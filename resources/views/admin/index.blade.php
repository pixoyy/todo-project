@extends('main')

@section('main-content')
    <x-custom-card>
        <div class="row g-2 align-items-center mb-4">
            <div class="col-12 col-lg-3">
                <x-search-bar />
            </div>
            <div class="col-12 col-lg-9">
                <div class="d-flex flex-wrap align-items-center justify-content-start justify-content-lg-end gap-2">
                    <select id="role_id" class="form-select border-grey" style="min-width: 150px; max-width: 220px;">
                        <option value="">Semua Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>

                    <select id="status" class="form-select border-grey" style="min-width: 140px; max-width: 170px;">
                        <option value="">Semua Status</option>
                        <option value="1">Aktif</option>
                        <option value="0">Tidak Aktif</option>
                    </select>

                    @can('access', 'create')
                        <a href="{{ route('users_add') }}" class="btn btn-blue text-nowrap">Tambah</a>
                    @endcan
                </div>
            </div>
        </div>

        <div id="users"></div>
    </x-custom-card>
@endsection

@push('js')
    @if (session()->has('message'))
        <script>
            toastr.success("{{ session()->get('message') }}");
        </script>
    @endif

    <script>
        $(() => {
            $('#users').on('click', '.pagination a', function(event) {
                event.preventDefault();
                const page = $(this).attr('href').split('page=')[1];
                loadData(page);
            });

            $('#query, #status, #role_id').on('change keyup', function() {
                loadData();
            });

            loadData();
        });

        function loadData(page) {
            $.ajax({
                url: "{{ route('users_data') }}",
                method: 'GET',
                data: {
                    query: $('#query').val(),
                    status: $('#status').val(),
                    role_id: $('#role_id').val(),
                    page: page,
                },
                success: (data) => {
                    $('#users').html(data);
                },
                error: () => {
                    toastr.error('Terjadi kesalahan! Silakan coba lagi!');
                },
            });
        }

        function deleteData(route) {
            showDeleteAlert(null, () => {
                $.ajax({
                    url: route,
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    beforeSend: () => {
                        loading();
                    },
                    success: (data) => {
                        if (data.status == true) {
                            toastr.success(data.message);
                            loadData();
                        } else {
                            toastr.error(data.message);
                        }
                    },
                    error: () => {
                        toastr.error('Terjadi kesalahan! Silakan coba lagi!');
                    },
                    complete: () => {
                        doneLoading();
                    },
                });
            });
        }
    </script>
@endpush


