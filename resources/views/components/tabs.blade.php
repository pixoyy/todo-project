<ul class="nav nav-tabs border-0 fs-5" id="tabs" role="tablist">
    @foreach ($tabs as $i => $tab)
        <li class="nav-item @if($i < count($tabs)-1) me-2 @endif" role="presentation">
            <button class="nav-link border-0 px-5 @if($i == 0) active @endif"
                id="{{ $tab['id'] }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $tab['id'] }}" type="button"
                role="tab" aria-controls="{{ $tab['id'] }}" aria-selected="{{ $i == 0 }}"
                >
                {{ $tab['name'] }}
            </button>
        </li>
    @endforeach
</ul>
<div class="tab-content">
    {{ $slot }}
</div>