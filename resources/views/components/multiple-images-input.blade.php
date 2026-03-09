<label for="{{ $inputId }}" class="form-label">Upload Gambar</label>
<input id="{{ $inputId }}" data-image-id="{{ $imageId }}" class="image-input d-none" type="file" accept="image/jpg,image/jpeg,image/png">
<div class="multiple-images-container d-flex flex-wrap gap-2">
    @if (isset($images))
        @foreach ($images as $image)
            <x-single-image-container :image-group="$imageId" :link="$image['link']" :value="$image['value']" />
        @endforeach
    @endif
    <x-single-image-container :image-group="$imageId" />
</div>

@push('js')
    <script>
        $(() => setUploadConfig('{{ $imageId }}', '{{ $path }}', 2));
    </script>
@endpush