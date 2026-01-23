<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Form Edit Obat</h3>
        </div>
        <div class="page-content">
            <div class="card shadow-sm">
                <div class="card-header bg-white pt-3">
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-header bg-white pt-3 border-bottom-0">
                                <h5 class="fw-bold text-primary"><i class="bi bi-capsule-pill me-2"></i>Edit Obat
                                    {{ $product->name }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-7 border-end">
                                        <form action="{{ route('ProductMeasurement.updateProduct', $product->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="px-2">
                                                <div
                                                    class="mb-4 p-3 rounded-4 bg-primary-subtle border-start border-4 border-primary shadow-sm">
                                                    <h6 class="fw-bold text-primary mb-3 small text-uppercase"><i
                                                            class="bi bi-card-text me-1"></i> Identitas Produk</h6>
                                                    <div class="row mb-2">
                                                        <label class="col-sm-4 col-form-label fw-semibold small">KODE &
                                                            TIPE</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="code"
                                                                class="form-control form-control-sm bg-white fw-bold"
                                                                value="{{ $product->code }}" readonly>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <select name="type"
                                                                class="form-select form-select-sm bg-white">
                                                                <option value="single"
                                                                    {{ $product->type == 'single' ? 'selected' : '' }}>
                                                                    SINGLE</option>
                                                                <option value="bundle"
                                                                    {{ $product->type == 'bundle' ? 'selected' : '' }}>
                                                                    BUNDLE</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-2">
                                                        <label class="col-sm-4 col-form-label fw-semibold small">NAMA
                                                            OBAT*</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="name"
                                                                class="form-control form-control-sm border-primary"
                                                                value="{{ $product->name }}" required>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-0">
                                                        <label
                                                            class="col-sm-4 col-form-label fw-semibold small">DESKRIPSI</label>
                                                        <div class="col-sm-8">
                                                            <textarea name="description" class="form-control form-control-sm" rows="2">{{ $product->description }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div
                                                    class="mb-4 p-3 rounded-4 bg-success-subtle border-start border-4 border-success shadow-sm">
                                                    <h6 class="fw-bold text-success mb-3 small text-uppercase"><i
                                                            class="bi bi-grid-fill me-1"></i> Klasifikasi & Harga
                                                    </h6>
                                                    <div class="row mb-2">
                                                        <label
                                                            class="col-sm-4 col-form-label fw-semibold small">KATEGORI</label>
                                                        <div class="col-sm-8">
                                                            <select name="category"
                                                                class="form-select form-select-sm border-success">
                                                                @foreach ($cats as $cat)
                                                                    <option value="{{ $cat->product_cat_id }}"
                                                                        {{ $cat->product_cat_id == $product->category ? 'selected' : '' }}>
                                                                        {{ $cat->product_cat_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <label class="col-sm-4 col-form-label fw-semibold small">SATUAN
                                                            UTAMA (BASE)</label>
                                                        <div class="col-sm-8">
                                                            <select name="measurement_id"
                                                                class="form-select form-select-sm border-success">
                                                                @foreach ($ums as $u)
                                                                    <option value="{{ $u->id }}"
                                                                        {{ $u->id == $product->measurement_id ? 'selected' : '' }}>
                                                                        {{ $u->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-0">
                                                        <label class="col-sm-4 col-form-label fw-semibold small">MARGIN
                                                            LABA (%)</label>
                                                        <div class="col-sm-4">
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" name="margin"
                                                                    class="form-control border-success"
                                                                    value="{{ $product->margin }}">
                                                                <span
                                                                    class="input-group-text bg-success text-white border-success">%</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="p-3 rounded-4 bg-purple-subtle border-start border-4 border-purple shadow-sm"
                                                    style="background-color: #f3e5f5; border-color: #9c27b0;">
                                                    <h6 class="fw-bold mb-3 small text-uppercase"
                                                        style="color: #7b1fa2;"><i class="bi bi-eye-fill me-1"></i>
                                                        Visibilitas & Stok</h6>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-check form-switch custom-switch">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="is_stockable" value="1" id="stok"
                                                                    {{ $product->is_stockable ? 'checked' : '' }}>
                                                                <label class="form-check-label fw-bold small"
                                                                    for="stok">STOK AKTIF</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-check form-switch custom-switch">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="is_visible" value="1" id="visible"
                                                                    {{ $product->is_visible ? 'checked' : '' }}>
                                                                <label class="form-check-label fw-bold small"
                                                                    for="visible">TAMPIL DI KASIR</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="submit"
                                                class="btn btn-primary px-5 mt-2 shadow-sm rounded-pill"><i
                                                    class="bi bi-save2 me-2"></i>Simpan Perubahan</button>
                                        </form>
                                    </div>


                                    <div class="col-md-5 ps-md-4">
                                        <h6 class="fw-bold text-secondary mb-3 border-bottom pb-2">Konversi Satuan
                                        </h6>
                                        <div class="table-responsive border-0 shadow-sm rounded-4">
                                            <table class="table table-hover align-middle mb-0" id="tableMeasurement">
                                                <thead class="bg-info">
                                                    <tr class="small text-uppercase">
                                                        <th class="ps-4 text-white">Satuan (Unit)</th>
                                                        <th class="text-center text-white">Isi / Konversi</th>
                                                        <th class="text-white">Lokasi (Placement)</th>
                                                        <th class="text-center pe-4 text-white">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($product->ProductMeasurements as $pm)
                                                        <tr id="row-{{ $pm->id }}" class="measurement-row">
                                                            <td class="ps-4">
                                                                <span
                                                                    class="badge rounded-pill bg-info-subtle text-info px-3 py-2 fw-bold text-uppercase view-mode">
                                                                    {{ $pm->measurement->name }}
                                                                </span>
                                                                <select
                                                                    class="form-select form-select-sm edit-mode d-none measurement-select">
                                                                    @foreach ($ums as $m)
                                                                        <option value="{{ $m->id }}"
                                                                            {{ $m->id == $pm->measurement_id ? 'selected' : '' }}>
                                                                            {{ $m->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>

                                                            <td class="text-center">
                                                                <div class="view-mode fw-bold text-dark">
                                                                    {{ number_format($pm->conversion, 0) }}
                                                                </div>
                                                                <div class="edit-mode d-none px-3">
                                                                    <input type="number"
                                                                        class="form-control form-control-sm text-center fw-bold border-primary conversion-input"
                                                                        value="{{ $pm->conversion }}">
                                                                </div>
                                                            </td>

                                                            <td class="ps-4">
                                                                <span
                                                                    class="badge rounded-pill bg-info-subtle text-info px-3 py-2 fw-bold text-uppercase view-mode">
                                                                    {{ $pm->placement->description }}
                                                                </span>
                                                                <select
                                                                    class="form-select form-select-sm edit-mode d-none placement-input">
                                                                    @foreach ($placement as $p)
                                                                        <option value="{{ $p->id }}"
                                                                            {{ $p->id == $pm->placement_id ? 'selected' : '' }}>
                                                                            {{ $p->description }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>

                                                            <td class="text-center pe-4">
                                                                <div class="view-mode">
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-light text-primary border shadow-sm rounded-pill btn-edit-inline"
                                                                        title="Edit Baris">
                                                                        <i class="bi bi-pencil-fill"></i>
                                                                    </button>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-light text-danger border shadow-sm rounded-pill"
                                                                        onclick="deleteMeasurement({{ $pm->id }})"
                                                                        title="Hapus">
                                                                        <i class="bi bi-trash3-fill"></i>
                                                                    </button>
                                                                </div>
                                                                <div class="edit-mode d-none">
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-success rounded-pill shadow-sm btn-save-inline"
                                                                        onclick="saveInline({{ $pm->id }})">
                                                                        <i class="bi bi-check-lg"></i> Simpan
                                                                    </button>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-outline-secondary rounded-pill shadow-sm btn-cancel-inline">
                                                                        <i class="bi bi-x-lg"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <div
                                            class="d-flex justify-content-between align-items-center mb-3 mt-2 border-bottom pb-2">
                                            <h6 class="fw-bold text-secondary mb-0">Konversi Satuan</h6>
                                            <button type="button" class="btn btn-primary btn-sm"
                                                data-bs-toggle="modal" data-bs-target="#modalAddMeasurement">
                                                <i class="bi bi-plus-lg"></i> Tambah Satuan
                                            </button>
                                        </div>

                                        <div class="mt-4 p-3 rounded border border-dashed">
                                            <p class="small text-muted mb-1"><i
                                                    class="bi bi-info-circle-fill text-info"></i>
                                                Info Harga Terakhir</p>
                                            <h5 class="mb-0 fw-bold">Rp
                                                {{ number_format($product->ProductMeasurements->first()->last_buy_price ?? 0, 0, ',', '.') }}
                                            </h5>
                                            <small class="text-muted">Margin Saat Ini:
                                                {{ $product->margin }}%</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white d-flex justify-content-end gap-2 py-3">
                    <a href="{{ route('ProductMstr.index') }}"
                        class="btn btn-sm btn-outline-secondary px-4">Batal</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAddMeasurement" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Konversi Satuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formAddMeasurement">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Pilih Satuan</label>
                            <select name="measurement_id" class="form-select select2-modal" required>
                                <option value="">-- Pilih Satuan --</option>
                                @foreach ($ums as $m)
                                    <option value="{{ $m->id }}">{{ $m->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nilai Konversi (Isi)</label>
                            <input type="number" name="conversion" class="form-control" placeholder="Contoh: 10"
                                required>
                            <div class="form-text">Berapa isi satuan ini terhadap satuan terkecil.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pilih Lokasi</label>
                            <select name="placement_id" class="form-select select2-modal" required>
                                <option value="">-- Pilih Lokasi --</option>
                                @foreach ($placement as $p)
                                    <option value="{{ $p->id }}">{{ $p->description }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Satuan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @push('scripts')
        <script src="{{ url('assets/js/ProductMstr/getData.js') }}"></script>
        <script src="{{ url('assets/js/alert.js') }}"></script>
        <script>
            $(document).ready(function() {
                // 1. Fungsi Klik Edit
                $('.btn-edit-inline').on('click', function() {
                    let row = $(this).closest('tr');

                    // Sembunyikan semua mode edit yang mungkin sedang terbuka di baris lain (opsional)
                    // $('.measurement-row').find('.view-mode').removeClass('d-none');
                    // $('.measurement-row').find('.edit-mode').addClass('d-none');

                    // Aktifkan mode edit pada baris ini
                    row.find('.view-mode').addClass('d-none');
                    row.find('.edit-mode').removeClass('d-none');
                    row.addClass('table-primary-subtle border-primary'); // Highlight warna biru muda
                });

                // 2. Fungsi Batal Edit
                $('.btn-cancel-inline').on('click', function() {
                    let row = $(this).closest('tr');
                    row.find('.view-mode').removeClass('d-none');
                    row.find('.edit-mode').addClass('d-none');
                    row.removeClass('table-primary-subtle border-primary');
                });
            });

            // 3. Fungsi Simpan via AJAX
            function saveInline(id) {
                let row = $(`#row-${id}`);
                let data = {
                    _token: "{{ csrf_token() }}",
                    measurement_id: row.find('.measurement-select').val(),
                    conversion: row.find('.conversion-input').val(),
                    placement_id: row.find('.placement-input').val()
                };

                $.ajax({
                    url: `/ProductMeasurement/updateMeasurement/${id}`, // Sesuaikan URL update Anda
                    type: "PUT",
                    data: data,
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Tersimpan!',
                                timer: 1000,
                                showConfirmButton: false
                            });
                            location.reload(); // Refresh untuk update tampilan badge/teks
                        }
                    },
                    error: function(err) {
                        Swal.fire('Error', 'Gagal update data. Pastikan nilai valid.', 'error');
                    }
                });
            }
        </script>
        <script>
            function deleteMeasurement(id) {
                Swal.fire({
                    title: 'Hapus Satuan?',
                    text: "Data konversi ini akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    padding: '2em',
                    customClass: {
                        popup: 'rounded-4'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/ProductMeasurement/delete/${id}`, // Sesuaikan route delete Anda
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(res) {
                                $(`#row-${id}`).fadeOut(500, function() {
                                    $(this).remove();
                                });
                                Swal.fire({
                                    title: 'Terhapus!',
                                    icon: 'success',
                                    timer: 1000,
                                    showConfirmButton: false
                                });
                            }
                        });
                    }
                })
            }
        </script>
        <style>
            /* Efek Row Hover */
            .measurement-row {
                transition: all 0.3s ease;
            }

            .measurement-row:hover {
                background-color: #f8f9fc;
                transform: scale(1.005);
            }

            /* Soft Badge Colors */
            .bg-info-subtle {
                background-color: #e3f2fd !important;
                border: 1px solid #90caf9;
            }

            /* Transisi Fade untuk Edit Mode */
            .edit-mode {
                animation: fadeIn 0.3s;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(-5px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Style Input saat Edit */
            .form-control-sm:focus {
                box-shadow: 0 0 0 0.25 margin-rgba(13, 110, 253, 0.25);
                border-color: #0d6efd;
            }
        </style>
        <script>
            $('#formAddMeasurement').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('ProductMeasurement.store') }}", // Buat route ini di web.php
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            // Tutup modal
                            $('#modalAddMeasurement').modal('hide');
                            // Reset form
                            $('#formAddMeasurement')[0].reset();
                            // Refresh halaman atau append row ke tabel secara manual
                            location.reload();
                            // Anda bisa mengganti location.reload() dengan logika append <tr> 
                            // jika ingin UX yang lebih smooth tanpa refresh.
                        }
                    },
                    error: function(err) {
                        alert("Terjadi kesalahan. Pastikan satuan belum ada.");
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
