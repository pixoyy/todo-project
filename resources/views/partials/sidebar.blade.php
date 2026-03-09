<aside id="sidebar" class="sidebar">
    <div class="d-flex align-items-center justify-content-center pb-4">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" width="75" height="75">
        {{-- <h5 class="text-center text-blue py-0 pe-1 mb-0 fs-5 fw-bold">Sangha Theravada Indonesia</h5> --}}
    </div>

    <ul class="sidebar-nav" id="sidebar-nav">
        @php
            $currentModuleRoute = Helper::getModuleRoute();
            $modules = Helper::getModules();
        @endphp
        @foreach ($modules as $module)
            @if (isset($module->route))
                @php
                    $active = $currentModuleRoute == $module->route;
                @endphp
                <li class="nav-item">
                    <a class="nav-link @if ($active) active @endif" href="{{ route($module->route) }}">
                        @if ($active)
                            <img src="{{ asset('icons/' . $module->icon . '_active.svg') }}" alt="" class="me-2" style="width: auto; height: 25px;">
                        @else
                            <img src="{{ asset('icons/' . $module->icon . '.svg') }}" alt="" class="me-2" style="width: auto; height: 25px;">
                        @endif
                        <span>{{ $module->name }}</span>
                    </a>
                </li>
            @else
                @php
                    $active = $module->modules->contains('route', $currentModuleRoute);
                @endphp
                <li class="nav-item">
                    <a class="nav-link @if ($active) active @else collapsed @endif" data-bs-target="#nav-{{ $module->id }}" data-bs-toggle="collapse" href="#">
                        @if ($active)
                            <img src="{{ asset('icons/' . $module->icon . '_active.svg') }}" alt="" class="me-2" style="width: auto; height: 25px;">
                        @else
                            <img src="{{ asset('icons/' . $module->icon . '.svg') }}" alt="" class="me-2" style="width: auto; height: 25px;">
                        @endif
                        <span>{{ $module->name }}</span><i class="bi bi-chevron-down ms-auto @if ($active) text-white @endif"></i>
                    </a>
                    <ul id="nav-{{ $module->id }}" class="nav-content collapse @if ($active) show @endif" data-bs-parent="#sidebar-nav">
                        @foreach ($module->modules as $child)
                            @php
                                $active = $currentModuleRoute == $child->route;
                            @endphp
                            <li>
                                <a class="child-link @if ($active) active @endif" href="{{ route($child->route) }}">
                                    <span>{{ $child->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endif
        @endforeach
    </ul>
</aside>
