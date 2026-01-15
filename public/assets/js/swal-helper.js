const SwalBase = Swal.mixin({
    width: window.innerWidth < 768 ? "90%" : "32em",
    allowOutsideClick: false,
    confirmButtonColor: "#0d6efd",
    cancelButtonColor: "#6c757d",
});

function swalWarning(title, text) {
    return SwalBase.fire({
        icon: "warning",
        title,
        text,
    });
}

function swalError(title, text) {
    return SwalBase.fire({
        icon: "error",
        title,
        text,
    });
}

function swalSuccess(title, text, timer = 2000) {
    return SwalBase.fire({
        icon: "success",
        title,
        text,
        timer,
        showConfirmButton: false,
    });
}

function swalConfirm({
    title = "Konfirmasi",
    text = "Apakah Anda yakin?",
    confirmText = "Ya",
    cancelText = "Batal",
}) {
    return SwalBase.fire({
        icon: "question",
        title,
        text,
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: cancelText,
    });
}

function swalLoading(title = "Sedang Memproses...") {
    return SwalBase.fire({
        title,
        didOpen: () => {
            Swal.showLoading();
        },
    });
}

function swalClose() {
    Swal.close();
}

