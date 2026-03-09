<table class="table">
    <thead>
        <tr class="table-blue">
            <th scope="col">No</th>
            <th scope="col" width="50%">Nama Peran</th>
            <th scope="col">Status</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($roles as $i => $role)
            <tr>
                <x-padded-td>{{ $i + $roles->firstItem() }}</x-padded-td>
                <x-padded-td>{{ $role->name }}</x-padded-td>
                @if ($role->status == 1)
                    <x-padded-td green="true">Aktif</x-padded-td>
                @else
                    <x-padded-td red="true">Tidak Aktif</x-padded-td>
                @endif
                <td>
                    <div class="d-flex align-items-center gap-2">
                        @if ($role->name != 'Master')
                            @can('access', 'update')
                                <x-edit-action-button :route="route('role_edit', $role->id)" />  
                            @endcan
                            @can('access', 'delete')
                                <x-delete-action-button :route="route('role_delete', $role->id)" />
                            @endcan
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center">Peran tidak ditemukan</td></tr>
        @endforelse
    </tbody>
</table>
{{ $roles->links() }}