@extends('main')

@section('main-content')
    <x-custom-card>
        <form id="form-task" class="row g-3" method="POST" action="{{ route('tasks_create') }}">
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
                <label for="category_id" class="form-label">Category</label>
                <select name="category_id" id="category_id"
                    class="form-select border-grey @error('category_id') is-invalid @enderror" required>
                    <option value="">Pilih category</option>
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label for="title" class="form-label">Judul Task</label>
                <input type="text" name="title" id="title"
                    class="form-control border-grey @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea name="description" id="description" cols="30" rows="8"
                    class="form-control border-grey @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-3">
                <label for="priority" class="form-label">Prioritas</label>
                <select name="priority" id="priority" class="form-select border-grey @error('priority') is-invalid @enderror"
                    required>
                    <option value="low" @selected(old('priority') === 'low')>Low</option>
                    <option value="medium" @selected(old('priority', 'medium') === 'medium')>Medium</option>
                    <option value="high" @selected(old('priority') === 'high')>High</option>
                </select>
                @error('priority')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select border-grey @error('status') is-invalid @enderror"
                    required>
                    <option value="todo" @selected(old('status', 'todo') === 'todo')>To Do</option>
                    <option value="in_progress" @selected(old('status') === 'in_progress')>In Progress</option>
                    <option value="review" @selected(old('status') === 'review')>Review</option>
                    <option value="done" @selected(old('status') === 'done')>Done</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-3">
                <label for="due_date" class="form-label">Due Date</label>
                <input type="date" name="due_date" id="due_date"
                    class="form-control border-grey @error('due_date') is-invalid @enderror" value="{{ old('due_date') }}">
                @error('due_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-3">
                <label for="assigned_admin_id" class="form-label">Assignee</label>
                <select name="assigned_admin_id" id="assigned_admin_id"
                    class="form-select border-grey @error('assigned_admin_id') is-invalid @enderror">
                    <option value="">Tidak ditentukan</option>
                    @foreach ($admins as $admin)
                        <option value="{{ $admin->id }}" @selected(old('assigned_admin_id') == $admin->id)>{{ $admin->name }}</option>
                    @endforeach
                </select>
                @error('assigned_admin_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 mt-4 text-end">
                <a href="{{ route(name: 'tasks') }}" class="btn btn-outline-secondary">Kembali</a>
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
        const projectCategoryMap = @json($projects->mapWithKeys(fn($p) => [$p->id => $p->categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->values()]));
        const oldProjectId = "{{ old('project_id') }}";
        const oldCategoryId = "{{ old('category_id') }}";

        $(() => {
            rebuildCategoryOptions(oldProjectId || $('#project_id').val(), oldCategoryId || null);

            $('#project_id').on('change', function() {
                rebuildCategoryOptions(this.value, null);
            });
        });

        function rebuildCategoryOptions(projectId, selectedCategoryId) {
            const categories = projectId ? (projectCategoryMap[projectId] || []) : [];
            const $category = $('#category_id');

            $category.html('<option value="">Pilih category</option>');
            categories.forEach((category) => {
                const selected = String(selectedCategoryId || '') === String(category.id) ? 'selected' : '';
                $category.append(`<option value="${category.id}" ${selected}>${category.name}</option>`);
            });
        }
    </script>
@endpush
