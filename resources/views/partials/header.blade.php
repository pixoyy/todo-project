<header id="header" class="header fixed-top d-flex align-items-center bg-blue font-grotesk">
    <i class="bi bi-list toggle-sidebar-btn text-white d-block d-xl-none pe-2"></i>
    <nav style="--bs-breadcrumb-divider: '|';" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 fs-5 font-grotesk">
            <li class="breadcrumb-item"><a href="{{ route(Helper::getModuleRoute()) }}">{{ Helper::getModuleName() }}</a>
            </li>
            @foreach ($breadcrumb as $i => $item)
                @if ($i < count($breadcrumb) - 1)
                    <li class="breadcrumb-item d-none d-lg-block"><a href="{{ $item['route'] }}">{{ $item['name'] }}</a>
                    </li>
                @else
                    <li class="breadcrumb-item d-none d-lg-block active" aria-current="page">{{ $item['name'] }}</li>
                @endif
            @endforeach
        </ol>
    </nav>

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <li class="nav-item dropdown pe-3 pe-md-4 pe-xl-5">
                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <img src="{{ asset('images/profile.png') }}" alt="Profile" class="rounded-circle">
                    <span
                        class="d-none d-md-block dropdown-toggle ps-2 fs-5 text-white">{{ auth()->user()->name }}</span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile font-poppins">
                    <li class="dropdown-header">
                        <h6>{{ auth()->user()->name }}</h6>
                        <span>{{ auth()->user()->role->name }}</span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>



                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item d-flex align-items-center" type="submit">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Log Out</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</header>

@push('js')
    <script>
        $('.toggle-sidebar-btn').on('click', function() {
            if ($('.breadcrumb').css('display') == 'none') {
                $('#sidebar').removeAttr('style')
                $('.breadcrumb').removeAttr('style');
                $('.toggle-sidebar-btn').removeAttr('style');
            } else {
                $('#sidebar').css('left', '0');
                $('.breadcrumb').css('display', 'none');
                $('.toggle-sidebar-btn').css('padding-left', '310px');
            }
        });

        function onWindowResize() {
            if ($(window).width() >= 1200 && $('.breadcrumb').css('display') == 'none') {
                $('.toggle-sidebar-btn').trigger('click');
            }
        }

        $(window).on('resize', onWindowResize);
    </script>
@endpush
