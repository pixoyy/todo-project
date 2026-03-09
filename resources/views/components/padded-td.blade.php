<td valign="middle">
    <div @class(['py-1', 'text-success' => $green ?? false, 'text-danger' => $red ?? false,'text-primary' => $blue ?? false, 'text-center' => $centered ?? false])>
        {{ $slot }}
    </div>
</td>
