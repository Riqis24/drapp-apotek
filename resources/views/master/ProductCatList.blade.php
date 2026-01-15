<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Product Category Master</h3>
        </div>
        <div class="page-content">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-outline-primary btn-sm rounded" type="button" onclick="openAddModal()">
                        Add Category
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="productTable" class="table table-striped table-bordered table-sm nowrap"
                            style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width:5%; text-align: center">No</th>
                                    <th style="width:30%; text-align: center">Name</th>
                                    <th style="text-align: center">Description</th>
                                    <th style="width:15%; text-align: center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item->product_cat_name }}</td>
                                        <td>{{ $item->product_cat_desc }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-info btn-sm"
                                                onclick="editCategory({{ $item->product_cat_id }}, '{{ $item->product_cat_name }}', '{{ $item->product_cat_desc }}')">
                                                <i class="bi bi-pencil"></i>
                                            </button>

                                            <button class="btn btn-danger btn-sm"
                                                onclick="deleteCategory({{ $item->product_cat_id }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Add/Edit (Single Form) --}}
    <div class="modal fade" id="modalCategory" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="categoryForm" action="{{ route('ProductCat.store') }}" method="POST">
                    @csrf
                    <div id="methodField"></div>
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalTitle">Add Product Category</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">Category Name</label>
                                <input type="text" class="form-control" name="product_cat_name" id="input_name"
                                    required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="product_cat_desc" id="input_desc" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ 'assets/js/ProductMstr/getData.js' }}"></script>
        <script>
            const modalElement = new bootstrap.Modal(document.getElementById('modalCategory'));
            const form = document.getElementById('categoryForm');
            const modalTitle = document.getElementById('modalTitle');
            const methodField = document.getElementById('methodField');

            // Fungsi Tambah Baru
            function openAddModal() {
                modalTitle.innerText = "Add Product Category";
                form.action = "{{ route('ProductCat.store') }}";
                methodField.innerHTML = ""; // Kosongkan (default POST)
                form.reset();
                modalElement.show();
            }

            // Fungsi Edit
            function editCategory(id, name, desc) {
                modalTitle.innerText = "Edit Product Category";
                // Ubah action form ke route update
                form.action = `/ProductCat/${id}`;
                // Tambahkan spoofing method PUT untuk Laravel
                methodField.innerHTML = `@method('PUT')`;

                // Isi data ke input
                document.getElementById('input_name').value = name;
                document.getElementById('input_desc').value = desc;

                modalElement.show();
            }

            // Fungsi Delete (Tetap disarankan pakai Fetch/AJAX agar bisa konfirmasi Swal dulu)
            function deleteCategory(id) {
                Swal.fire({
                    title: 'Hapus Kategori?',
                    text: "Pastikan kategori tidak sedang digunakan oleh produk apapun.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/ProductCat/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => response.json().then(data => ({
                                status: response.status,
                                body: data
                            })))
                            .then(res => {
                                if (res.status === 200) {
                                    Swal.fire('Berhasil', res.body.message, 'success').then(() => location
                                        .reload());
                                } else {
                                    Swal.fire('Gagal', res.body.message, 'error');
                                }
                            });
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
