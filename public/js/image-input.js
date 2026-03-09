let uploadConfigs = {};

function setUploadConfig(id, path, mode) {
    // mode 1: action for single image input
    // mode 2: action for multiple images input
    uploadConfigs[id] = { path, mode };
}

$('.image-input').on('input', function () {
    const file = this.files[0];
    if (file != null) {
        const imageId = $(this).data('image-id');
        const config = uploadConfigs[imageId];
        const formData = new FormData();
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('path', config.path);
        formData.append('image', file);

        $.ajax({
            url: '/upload/image',
            method: 'POST',
            contentType: false,
            processData: false,
            data: formData,
            beforeSend: () => {
                loading();
            },
            success: (res) => {
                if (res.status == true) {
                    if (config.mode == 1) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            $(this).siblings('.preview-container').find('.image-input-preview').attr('src', e.target.result);
                            $(this).siblings('.preview-container').removeClass('d-none');
                            $(this).siblings('.image-input-container').addClass('d-none');
                            $(this).siblings('.image-id').val(res.data.file_storage_id);
                            toastr.success(res.message);
                        }
                        reader.readAsDataURL(file);

                    } else if (config.mode == 2) {
                        const containerId = $(this).data('container-id');
                        const multipleImagesContainer = $(this).siblings('.multiple-images-container');
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            $(multipleImagesContainer).find(`.single-image-container[id="${containerId}"] .single-image-preview`).attr('src', e.target.result);
                            $(multipleImagesContainer).find(`.single-image-container[id="${containerId}"]`).addClass('set');
                            $(multipleImagesContainer).find(`.single-image-container[id="${containerId}"] .image-id`).val(res.data.file_storage_id);
                            $(multipleImagesContainer).find(`.single-image-container[id="${containerId}"]`).attr('id', res.data.file_storage_id);
                            if (containerId == -1) {
                                $(multipleImagesContainer).append(buildSingleImageContainer(imageId));
                            }
                            toastr.success(res.message);
                        }
                        reader.readAsDataURL(file);
                    }
                } else {
                    toastr.error(res.message);
                }
            },
            error: (jqXHR, status, err) => {
                if (jqXHR.status == 422) {
                    toastr.error(jqXHR.responseJSON.message);
                } else {
                    toastr.error('Terjadi kesalahan! Silakan coba lagi!');
                }
            },
            complete: () => {
                doneLoading();
            },
        });
    }
});

/*  For single image input
-------------------------------------
*/
$('.image-input-container').on('click', function () {
    $(this).siblings('.image-input').click();
});

$('.preview-container').on('mouseover', function () {
    $(this).find('.image-input-preview').css('opacity', '0.6');
    $(this).find('.image-action-button').css('opacity', '1');
});

$('.preview-container').on('mouseleave', function () {
    $(this).find('.image-input-preview').css('opacity', '1');
    $(this).find('.image-action-button').css('opacity', '0');
});

$('.image-input-preview, .image-action-button .btn-change').on('click', function () {
    $(this).closest('.preview-container').siblings('.image-input').click();
});

$('.image-action-button .btn-delete').on('click', function () {
    $(this).closest('.preview-container').find('.image-input-preview').attr('src', '');
    $(this).closest('.preview-container').addClass('d-none');
    $(this).closest('.preview-container').siblings('.image-input-container').removeClass('d-none');
    $(this).closest('.preview-container').siblings('.image-id').val('');
});

/*  For multiple images input
-------------------------------------
*/
$('.multiple-images-container').on('click', '.single-image-preview, .single-image-action-button .btn-change', function () {
    $(this).closest('.multiple-images-container').siblings('.image-input').data('container-id', $(this).closest('.single-image-container').attr('id'));
    $(this).closest('.multiple-images-container').siblings('.image-input').click();
});

$('.multiple-images-container').on('mouseover', '.single-image-container.set', function () {
    $(this).find('.single-image-preview').css('opacity', '0.6');
    $(this).find('.single-image-action-button').css('opacity', '1');
});

$('.multiple-images-container').on('mouseleave', '.single-image-container.set', function () {
    $(this).find('.single-image-preview').css('opacity', '1');
    $(this).find('.single-image-action-button').css('opacity', '0');
});

$('.multiple-images-container').on('click', '.single-image-action-button .btn-delete', function () {
    $(this).closest('.single-image-container').remove();
});
