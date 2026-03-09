@foreach ($modules as $module)
    <tr data-module="{{ $module->id }}">
        <x-padded-td>{{ $module->name }}</x-padded-td>
        <x-padded-td centered="true">
            <input type="checkbox" class="form-check-input border-grey" name="authorization_all"
                @checked($module->authorizations->count() == 4) disabled />
        </x-padded-td>
        <x-padded-td centered="true">
            <input type="checkbox" class="form-check-input border-grey" name="authorization" value="1"
                @checked($module->authorizations->contains('authorization_type_id', '1')) disabled />
        </x-padded-td>
        <x-padded-td centered="true">
            <input type="checkbox" class="form-check-input border-grey" name="authorization" value="2"
                @checked($module->authorizations->contains('authorization_type_id', '2')) disabled />
        </x-padded-td>
        <x-padded-td centered="true">
            <input type="checkbox" class="form-check-input border-grey" name="authorization" value="3"
                @checked($module->authorizations->contains('authorization_type_id', '3')) disabled />
        </x-padded-td>
        <x-padded-td centered="true">
            <input type="checkbox" class="form-check-input border-grey" name="authorization" value="4"
                @checked($module->authorizations->contains('authorization_type_id', '4')) disabled />
        </x-padded-td>
    </tr>
@endforeach