<label for="{{ $id }}" class="form-label">{{ $label }}</label>
<div id="{{ $id }}" class="border-grey rounded-2"></div>
@error($id)
    <div class="invalid-feedback d-block">{{ $message }}</div>
@enderror

@push('js')
    <script>
        createEditor('{{ $id }}', `{!! $value !!}`);

        @error($id)
            $('#{{ $id }}').addClass('is-invalid');
        @enderror
    </script>
@endpush

<style>
    .cke_widget_embed {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 aspect ratio */
        height: 0;
        overflow: hidden;
        max-width: {{ $maxWidth ?? '60%' }}; /* Default 60%, customizable */
        margin: 0 auto;
    }

    .cke_widget_embed iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: {{ $iframeWidth ?? '50%' }} !important; /* Default 50% */
        height: {{ $iframeHeight ?? '50%' }} !important; /* Default 50% */
    }

    .cke_widget_embed:hover {
        padding: 5px !important;
        border-width: 1px !important;
    }

    @media (max-width: 768px) {
        .cke_widget_embed {
            max-width: {{ $responsiveWidth ?? '50%' }}; /* Default 50% on small screens */
        }
    }
</style>
