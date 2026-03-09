<div id="{{ $value ?? -1 }}" class="col-12 col-sm-6 col-md-4 col-lg-3 single-image-container @isset($value) set @endisset position-relative">
    <input type="hidden" name="{{ $imageGroup }}" class="image-id" value="{{ $value ?? -1 }}">
    <img class="single-image-preview" src="{{ $link ?? asset('images/image-placeholder.png') }}" alt="">
    <div class="single-image-action-button position-absolute bottom-0 start-50 translate-middle d-flex align-items-center">
        <button type="button" class="btn btn-blue me-1 btn-change">Ubah</button>
        <button type="button" class="btn btn-danger btn-delete">Hapus</button>
    </div>
</div>