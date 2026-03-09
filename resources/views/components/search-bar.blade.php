<div class="search-bar d-flex align-items-center">
    <button type="button" title="Search"><i class="bi bi-search"></i></button>
    <input type="text" id="query" name="query" placeholder="Search" title="Enter search keyword" class="border-grey rounded-2">
</div>

@push('js')
    <script>
        $('.search-bar button').on('click', function () {
            $(this).siblings('#query').focus();
        });
    </script>
@endpush