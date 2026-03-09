@extends('base')

@section('content')
    @include('partials.header', ['breadcrumb' => $breadcrumb ?? []])
    @include('partials.sidebar')
    @include('partials.loading-indicator')
    <main class="main-content">
        @yield('main-content')
    </main>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
@endpush

@push('head.js')
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
@endpush

@push('js')
    <script src="{{ asset('js/utils.js') }}"></script>
@endpush