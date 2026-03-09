@extends('main')

@section('main-content')
    <x-custom-card>
        {{-- <div class="d-flex align-items-center gap-2 flex-nowrap overflow-auto mb-4 pb-1">
            <div style="min-width: 240px;">
                <x-search-bar />
            </div>

            <div class="d-flex align-items-center gap-2 flex-nowrap">
                <select id="project_id" class="form-select border-grey" style="max-width: 200px;">
                    <option value="">Semua Project</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>

                <select id="category_id" class="form-select border-grey" style="max-width: 200px;">
                    <option value="">Semua Category</option>
                </select>

                <select id="status" class="form-select border-grey" style="max-width: 170px;">
                    <option value="">Semua Status</option>
                    <option value="todo">To Do</option>
                    <option value="in_progress">In Progress</option>
                    <option value="review">Review</option>
                    <option value="done">Done</option>
                </select>

                <select id="priority" class="form-select border-grey" style="max-width: 150px;">
                    <option value="">Semua Prioritas</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>

                @can('access', 'create')
                    <a href="{{ route('tasks_add') }}" class="btn btn-blue">Tambah</a>
                @endcan
            </div>
        </div> --}}
        <div class="row gap-2 justify-content-center justify-content-md-between align-items-center mb-4 mx-0">
            <div class="col-12 col-md-5 p-0">
                <x-search-bar />
            </div>
            <div class="col-12 col-md-4 p-0 d-flex align-items-center gap-2">

                <select id="project_id" name="project_id" class="form-select border-grey">
                    <option value="">Semua Project</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>


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
        <div id="tasks"></div>
    </x-custom-card>
@endsection

@push('js')
    @if (session()->has('message'))
        <script>
            toastr.success("{{ session()->get('message') }}");
        </script>
    @endif

    <script>
        const projectCategoryMap = @json($projects->mapWithKeys(fn($p) => [$p->id => $p->categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->values()]));

        $(() => {
            $('#tasks').on('click', '.pagination a', function(event) {
                event.preventDefault();
                const page = $(this).attr('href').split('page=')[1];
                loadData(page);
            });

            $('#query, #status, #priority, #project_id, #category_id').on('change keyup', function() {
                loadData();
            });

            $('#project_id').on('change', function() {
                rebuildCategoryOptions($(this).val(), null);
            });

            rebuildCategoryOptions(null, null);
            loadData();
        });

        function rebuildCategoryOptions(projectId, selectedCategoryId) {
            const categories = projectId ? (projectCategoryMap[projectId] || []) : [];
            const $category = $('#category_id');

            $category.html('<option value="">Semua Category</option>');
            categories.forEach((category) => {
                const selected = String(selectedCategoryId || '') === String(category.id) ? 'selected' : '';
                $category.append(`<option value="${category.id}" ${selected}>${category.name}</option>`);
            });
        }

        function loadData(page) {
            $.ajax({
                url: "{{ route('tasks_data') }}",
                method: 'GET',
                data: {
                    query: $('#query').val(),
                    status: $('#status').val(),
                    priority: $('#priority').val(),
                    project_id: $('#project_id').val(),
                    category_id: $('#category_id').val(),
                    page: page,
                },
                success: (data) => {
                    $('#tasks').html(data);
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
