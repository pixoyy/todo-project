<table class="table">
    <thead>
        <tr class="table-blue">
            <th scope="col">No</th>
            <th scope="col">Nama </th>
            <th scope="col" >Start Date</th>
            <th scope="col" >End Date</th>
            <th scope="col">Status</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($projects as $i => $project)
            <tr>
                <x-padded-td>{{ $i + $projects->firstItem() }}</x-padded-td>
                <x-padded-td>{{ $project->name }}</x-padded-td>
                <x-padded-td>{{ $project->start_date }}</x-padded-td>
                <x-padded-td>{{ $project->end_date }}</x-padded-td>

                @if ($project->status == 1)
                    <x-padded-td green="true">Aktif</x-padded-td>
                @else
                    <x-padded-td red="true">Tidak Aktif</x-padded-td>
                @endif
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <x-view-action-button :route="route('projects_detail', $project->id)" />
                        @can('access', 'update')
                            <x-edit-action-button :route="route('projects_edit', $project->id)" />
                        @endcan
                        @can('access', 'delete')
                            <x-delete-action-button :route="route('projects_delete', $project->id)" />
                        @endcan
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center">Project tidak ditemukan</td></tr>
        @endforelse
    </tbody>
</table>
{{ $projects->links() }}
