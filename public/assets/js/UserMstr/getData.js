$(document).ready(function () {
    $("#userTable").DataTable({
        responsive: true,
        autoWidth: true,
        pageLength: 10,
        scrollY: "350px",
    });
});

$(document).ready(function () {
    $(".edit-btn").on("click", function () {
        const row = $(this).closest("tr");
        row.find(
            ".name-display, .email-display, .role-display, .password-display"
        ).addClass("d-none");
        row.find(
            ".name-input, .email-input, .role-input, .password-input"
        ).removeClass("d-none");
        row.find(".edit-btn").addClass("d-none");
        row.find(".update-btn").removeClass("d-none");
    });

    $(".update-btn").on("click", function () {
        const row = $(this).closest("tr");
        // ambil nilai dari input dan update span
        row.find(".name-display").text(row.find(".name-input").val());
        row.find(".email-display").text(row.find(".email-input").val());
        row.find(".password-display").text(row.find(".password-input").val());
        row.find(".role-display").text(row.find(".role-input").val());

        row.find(
            ".name-input, .email-input, .password-input, .role-input"
        ).addClass("d-none");
        row.find(
            ".name-display, .email-display, .password-display, .role-display"
        ).removeClass("d-none");
        row.find(".update-btn").addClass("d-none");
        row.find(".edit-btn").removeClass("d-none");

        // AJAX call bisa disisipkan di sini kalau mau update ke server
    });

    $(".update-btn").on("click", function () {
        let row = $(this).closest("tr");
        let url = $(this).data("url");
        let id = row.data("id");
        let name = row.find(".name-input").val();
        let password = row.find(".password-input").val();
        let email = row.find(".email-input").val();
        let role = row.find(".role-input").val();

        $.ajax({
            url: url,
            type: "PUT",
            data: {
                name: name,
                email: email,
                password: password,
                role: role,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                // Update UI
                row.find(".name-display").text(name);
                row.find(".email-display").text(email);
                row.find(".role-display").text(role);
                row.find(".password-display").text(password);

                row.find(
                    ".name-input, .email-input, .role-input, .password-input"
                ).addClass("d-none");
                row.find(
                    ".name-display, .email-display, .role-display, .password-display"
                ).removeClass("d-none");
                row.find(".update-btn").addClass("d-none");
                row.find(".edit-btn").removeClass("d-none");

                Toast.fire({
                    icon: "success",
                    title: "User berhasil diperbarui!",
                });
                // alert("✅ " + response.message);
            },
            error: function (xhr) {
                Toast.fire({
                    icon: "error",
                    title: "Gagal Update",
                });
                // alert("❌ Gagal update!");
                console.log(xhr.responseText);
            },
        });
    });
});

// document.getElementById("addRow").addEventListener("click", function () {
//     const row = document.createElement("div");
//     row.classList.add("role-row");
//     row.innerHTML = `
//   <div class="row mt-2">
//        <div class="col-md-9">
//           <input type="text" class="form-control form-control-sm" name="roles[]"
//                   placeholder="Contoh: Admin" required>
//       </div>
//       <div class="col-md-3">
//           <button type="button" class="btn btn-danger btn-sm rounded removeRow">❌</button>
//        </div>
//   </div>
//   `;
//     document.getElementById("dynamicRoleInputs").appendChild(row);
// });

// // remove baris
// document.addEventListener("click", function (e) {
//     if (e.target && e.target.classList.contains("removeRow")) {
//         e.preventDefault();
//         e.target.closest(".role-row").remove();
//     }
// });
