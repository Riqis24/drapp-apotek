$(document).ready(function () {
    $("#priceTable").DataTable({
        responsive: true,
        autoWidth: true,
        pageLength: 10,
        scrollY: "350px",
    });

    $(".select2").select2({
        dropdownParent: $("#modalAddPrice"),
        placeholder: "Choose!",
    });
});
