$(document).ready(function () {
    $("#myTable").DataTable({
        responsive: true,
        autoWidth: true,
        pageLength: 10,
        scrollY: "350px",
    });
});

document.getElementById("addRow").addEventListener("click", function () {
    const row = document.createElement("div");
    row.classList.add("permission-row");
    row.innerHTML = `
  <div class="row mt-2">
       <div class="col-md-9">
          <input type="text" class="form-control form-control-sm" name="permissions[]"
                  placeholder="Contoh: produk.view" required>
      </div>
      <div class="col-md-3">
          <button type="button" class="btn btn-danger btn-sm rounded removeRow">❌</button>
       </div>
  </div>
  `;
    document.getElementById("dynamicPermissionInputs").appendChild(row);
});

// remove baris
document.addEventListener("click", function (e) {
    if (e.target && e.target.classList.contains("removeRow")) {
        e.preventDefault();
        e.target.closest(".permission-row").remove();
    }
});
