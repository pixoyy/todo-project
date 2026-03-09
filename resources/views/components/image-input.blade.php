<label for="{{ $inputId }}" class="form-label">Upload Gambar</label>
<input id="{{ $inputId }}" data-image-id="{{ $imageId }}" class="image-input d-none" type="file" accept="image/jpg,image/jpeg,image/png">
<input type="hidden" id="{{ $imageId }}" class="image-id" value="{{ $value ?? null }}">
<div class="image-input-container"></div>
<div class="preview-container position-relative d-none">
    <img class="image-input-preview" src="{{ $link ?? null }}" alt="" >
    <div class="image-action-button position-absolute bottom-0 start-50 translate-middle d-flex align-items-center">
        <button type="button" class="btn btn-blue me-1 btn-change">Ubah</button>
        <button type="button" class="btn btn-danger btn-delete">Hapus</button>
    </div>
</div>

@push('js')
    <script>
        $(() => setUploadConfig('{{ $imageId }}', '{{ $path }}', 1));
    </script>
@endpush
