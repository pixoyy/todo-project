@extends('main')

@section('main-content')
    <x-custom-card>
        <div class="row g-2 align-items-center mb-4">
            <div class="col-12 col-lg-3">
                <x-search-bar />
            </div>
            <div class="col-12 col-lg-9">
                <div class="d-flex flex-wrap align-items-center justify-content-start justify-content-lg-end gap-2">
                    <select id="project_id" name="project_id" class="form-select border-grey" style="min-width: 150px; max-width: 200px;">
                        <option value=""> Project</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>

                    <select id="category_id" name="category_id" class="form-select border-grey" style="min-width: 150px; max-width: 200px;">
                        <option value=""> Category</option>
                    </select>

                    <select id="status" name="status" class="form-select border-grey" style="min-width: 140px; max-width: 170px;">
                        <option value=""> Status</option>
                        <option value="todo">To Do</option>
                        <option value="in_progress">In Progress</option>
                        <option value="review">Review</option>
                        <option value="done">Done</option>
                    </select>

                    <select id="priority" name="priority" class="form-select border-grey" style="min-width: 120px; max-width: 150px;">
                        <option value="">Prioritas</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>

                    @can('access', 'create')
                        <a href="{{ route('tasks_add') }}" class="btn btn-blue text-nowrap">Tambah</a>
                    @endcan
                </div>
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
