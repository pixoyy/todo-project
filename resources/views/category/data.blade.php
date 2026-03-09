<table class="table">
    <thead>
        <tr class="table-blue">
            <th scope="col">No</th>
            <th scope="col">Project</th>
            <th scope="col">Nama Category</th>
            <th scope="col">Warna</th>
            <th scope="col">Urutan</th>
            <th scope="col">Jumlah Task</th>
            <th scope="col">Status</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($categories as $i => $category)
            <tr>
                <x-padded-td>{{ $i + $categories->firstItem() }}</x-padded-td>
                <x-padded-td>{{ $category->project->name ?? '-' }}</x-padded-td>
                <x-padded-td>{{ $category->name }}</x-padded-td>
                <x-padded-td>
                    @if (!empty($category->color))
                        <span class="d-inline-flex align-items-center gap-2">
                            <span style="width: 14px; height: 14px; border-radius: 50%; background-color: {{ $category->color }}; display: inline-block;"></span>
                            {{ $category->color }}
                        </span>
                    @else
                        -
                    @endif
                </x-padded-td>
                <x-padded-td>{{ $category->order }}</x-padded-td>
                <x-padded-td>{{ $category->tasks_count }}</x-padded-td>

                @if ($category->status == 1)
                    <x-padded-td green="true">Aktif</x-padded-td>
                @else
                    <x-padded-td red="true">Tidak Aktif</x-padded-td>
                @endif

                <td>
                    <div class="d-flex align-items-center gap-2">
                        <x-view-action-button :route="route('categories_detail', $category->id)" />
                        @can('access', 'update')
                            <x-edit-action-button :route="route('categories_edit', $category->id)" />
                        @endcan
                        @can('access', 'delete')
                            <x-delete-action-button :route="route('categories_delete', $category->id)" />
                        @endcan
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">Category tidak ditemukan</td>
            </tr>
        @endforelse
    </tbody>
</table>
{{ $categories->links() }}
