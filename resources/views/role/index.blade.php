@extends('main')

@section('main-content')
    <x-custom-card>
        <div class="d-flex justify-content-end mb-4">
            @can('access', 'create')
                <a href="{{ route('role_add') }}" class="btn btn-blue">Tambah</a>
            @endcan
        </div>
        <div id="records"></div>
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
            $('#records').on('click', '.pagination a', function (event) {
                event.preventDefault();
                const page = $(this).attr('href').split('page=')[1];
                loadData(page);
            });

            loadData();
        });

        function loadData(page) {
            $.ajax({
                url: "{{ route('role_data') }}",
                method: 'GET',
                data: {
                    page,
                },
                success: (data) => {
                    $('#records').html(data);
                },
                error: (jqXHR, status, err) => {
                    toastr.error('Terjadi kesalahan! Silakan coba lagi!');
                },
            });
        }

        function deleteData(route) {
            showDeleteAlert('Semua akun staff dengan peran ini akan dinonaktifkan!', () => {
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
                    error: (jqXHR, status, err) => {
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
