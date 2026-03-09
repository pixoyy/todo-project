@extends('main')

@section('main-content')
    <x-custom-card>
        <div class="row gap-2 justify-content-center justify-content-md-between align-items-center mb-4 mx-0">
            <div class="col-12 col-md-5 p-0">
                <x-search-bar />
            </div>
            <div class="col-12 col-md-4 p-0 d-flex align-items-center gap-2">
                <select id="status" name="status" class="form-select border-grey">
                    <option value="">Semua Status</option>
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
                @can('access', 'create')
                    <a href="{{ route('projects_add') }}" class="btn btn-blue">Tambah</a>
                @endcan
            </div>
        </div>
        <div id="projects"></div>
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
            $('#projects').on('click', '.pagination a', function(event) {
                event.preventDefault();
                const page = $(this).attr('href').split('page=')[1];
                loadData(page);
            });

            $('#query, #status').on('change', function() {
                loadData();
            });

            loadData();
        });

        function loadData(page) {
            $.ajax({
                url: "{{ route('projects_data') }}",
                method: 'GET',
                data: {
                    query: $('#query').val(),
                    status: $('#status').val(),
                    page: page,
                },
                success: (data) => {
                    $('#projects').html(data);
                },
                error: (jqXHR, status, err) => {
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
