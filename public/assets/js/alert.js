document.addEventListener("DOMContentLoaded", function () {
    const flash = document.getElementById("flash-data");

    if (!flash) return;

    const success = flash.dataset.success;
    const error = flash.dataset.error;
    const validation = flash.dataset.validation;

    if (success) {
        Toast.fire({
            icon: "success",
            title: success,
        });
    }

    if (error) {
        Toast.fire({
            icon: "error",
            title: error,
        });
    }

    if (validation) {
        Toast.fire({
            icon: "error",
            title: validation,
        });
    }

    
});
