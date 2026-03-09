<table class="table">
    <thead>
        <tr class="table-blue">
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($users as $i => $user)
            <tr>
                <x-padded-td>{{ $i + $users->firstItem() }}</x-padded-td>
                <x-padded-td>{{ $user->name }}</x-padded-td>
                <x-padded-td>{{ $user->email }}</x-padded-td>
                <x-padded-td>{{ $user->role->name ?? '-' }}</x-padded-td>
                <x-padded-td>{{ $user->phone_number }}</x-padded-td>
                @if ($user->status == 1)
                    <x-padded-td green="true">Aktif</x-padded-td>
                @else
                    <x-padded-td red="true">Tidak Aktif</x-padded-td>
                @endif
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <x-view-action-button :route="route('users_detail', $user->id)" />
                        @can('access', 'update')
                            <x-edit-action-button :route="route('users_edit', $user->id)" />
                        @endcan
                        @can('access', 'delete')
                            @if ((int) auth()->id() !== (int) $user->id && ($user->role->name ?? '') !== 'Master')
                                <x-delete-action-button :route="route('users_delete', $user->id)" />
                            @endif
                        @endcan
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">User tidak ditemukan</td>
            </tr>
        @endforelse
    </tbody>
</table>
{{ $users->links() }}
