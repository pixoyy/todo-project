toastr.options.progressBar = true;

const loadingIndicator = new bootstrap.Modal($("#loading-indicator"));
function loading() {
    loadingIndicator.show();
}
function doneLoading() {
    loadingIndicator.hide();
}

function showDeleteAlert(message, onConfirmed) {
    Swal.fire({
        title: "Apakah Anda yakin?",
        text: message
            ? message
            : "Anda tidak akan bisa mengembalikan data yang dihapus!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "red",
        cancelButtonColor: "#c4c4c4",
        confirmButtonText: "Ya, hapus data ini!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            onConfirmed();
        }
    });
}

function showSaveAlert(message, onConfirmed) {
    Swal.fire({
        title: "Apakah Anda yakin ingin menyimpan data ini?",
        text: message,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "orange",
        cancelButtonColor: "#c4c4c4",
        confirmButtonText: "Simpan",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            onConfirmed();
        }
    });
}

function setInvalid(el, error) {
    $(el).addClass("is-invalid");
    $(el).siblings(".invalid-feedback").addClass("d-block").text(error);
}

function clearInvalid(formId) {
    $(".invalid-feedback").removeClass("d-block");
    $(`#${formId} .is-invalid`).removeClass("is-invalid");
}
