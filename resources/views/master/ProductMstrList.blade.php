<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Product Master</h3>
        </div>
        <div class="page-content">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-outline-primary btn-sm rounded" type="button" data-bs-toggle="modal"
                        data-bs-target="#modalProduct">
                        Add Product
                    </button>
                    <button class="btn btn-outline-success btn-sm rounded"
                        onclick="window.open('{{ route('ProductBundle.create') }}')">
                        Add Bundle
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="productTable" class="table table-striped table-bordered table-sm nowrap"
                            style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width:5%; text-align: center">No</th>
                                    <th style="width:15%; text-align: center">Code</th>
                                    <th style="width:20%; text-align: center">Name</th>
                                    <th style="text-align: center">Description</th>
                                    <th style="width:10%; text-align: center">Measurement</th>
                                    <th style="width:10%; text-align: center">Category</th>
                                    <th style="width:10%; text-align: center">Margin</th>
                                    <th style="width:10%; text-align: center">Type</th>
                                    <th style="width:5%; text-align: center">Action</th>
                                    {{-- <th>Product</th> --}}
                                    {{-- <th>Status</th> --}}
                                    {{-- <th>Aksi</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td style="text-align: right">{{ $loop->iteration }}</td>
                                        <td>{{ $product->code }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->description }}</td>
                                        <td>{{ $product->measurement->name }}</td>
                                        <td>{{ $product->cat->product_cat_name ?? '-' }}</td>
                                        <td style="text-align: right">
                                            {{ $product->margin ? numfmt($product->margin) . '%' : '-' }}</td>
                                        <td>{{ $product->type }}</td>
                                        <td style="text-align: center">
                                            <button type="button" class="btn btn-sm btn-info rounded"
                                                onclick="window.location.href='{{ route('EditPrdMeasurement', $product->id) }}'">
                                                <i class="bi bi-folder"></i>
                                            </button>
                                            @if ($product->type == 'single')
                                                <button class="btn btn-warning btn-sm"
                                                    onclick="editProduct({
                                                    id: {{ $product->id }},
                                                    code: '{{ $product->code }}',
                                                    name: '{{ $product->name }}',
                                                    description: '{{ $product->description }}',
                                                    satuan: '{{ $product->measurement_id }}',
                                                    cat: '{{ $product->category }}',
                                                    margin: {{ $product->margin }},
                                                    type: '{{ $product->type }}',
                                                    is_stockable: {{ $product->is_stockable }},
                                                    is_visible: {{ $product->is_visible }}
                                                })">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                            @else
                                                <a href="{{ route('ProductBundle.edit', $product->id) }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endif
                                            <button class="btn btn-danger btn-sm"
                                                onclick="deleteProduct({{ $product->id }}, '{{ $product->name }}')">
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

    {{-- Satu form yang action-nya akan diubah via JS --}}
    <form id="productForm" action="{{ route('ProductMstr.store') }}" method="POST">
        @csrf
        <div id="methodField"></div> {{-- Untuk @method('PUT') --}}

        <div class="modal fade" id="modalProduct" tabindex="-1" aria-labelledby="modalProductLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalProductLabel">Product Master</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Kode</label>
                                <input type="text" class="form-control form-control-sm" name="code" id="edit_code"
                                    required>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" class="form-control form-control-sm" name="name" id="edit_name"
                                    required>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label class="form-label">Deskripsi</label>
                                <input type="text" class="form-control form-control-sm" name="description"
                                    id="edit_description" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Satuan</label>
                                <select name="satuan" id="edit_satuan" class="form-control form-control-sm">
                                    @foreach ($satuans as $satuan)
                                        <option value="{{ $satuan->id }}">{{ $satuan->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="cat" id="edit_cat" class="form-control form-control-sm">
                                    @foreach ($cats as $cat)
                                        <option value="{{ $cat->product_cat_id }}">{{ $cat->product_cat_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Margin (%)</label>
                                <input type="number" class="form-control form-control-sm" name="margin"
                                    id="edit_margin" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Type</label>
                                <select name="type" id="edit_type" class="form-control form-control-sm">
                                    <option value="single">Single</option>
                                    <option value="bundle">Bundle</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_stockable"
                                        value="1" id="edit_is_stockable">
                                    <label class="form-check-label">Stok dikelola?</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_visible" value="1"
                                        id="edit_is_visible">
                                    <label class="form-check-label">Tampilkan di Kasir?</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="btnSubmit">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>


    @push('scripts')
        <script src="{{ 'assets/js/ProductMstr/getData.js' }}"></script>
        <script src="{{ 'assets/js/alert.js' }}"></script>
        <script>
            const productModal = new bootstrap.Modal(document.getElementById('modalProduct'));
            const productForm = document.getElementById('productForm');
            const methodField = document.getElementById('methodField');
            const modalTitle = document.getElementById('modalProductLabel');

            // Fungsi Reset untuk Tambah Baru
            function openAddProductModal() {
                modalTitle.innerText = "Add New Product";
                productForm.action = "{{ route('ProductMstr.store') }}";
                methodField.innerHTML = ''; // Kosongkan method (default POST)
                productForm.reset();
                productModal.show();
            }

            // Fungsi Isi Data untuk Edit
            function editProduct(data) {
                modalTitle.innerText = "Edit Product: " + data.name;

                // Ubah URL action ke arah update (misal: /products/12)
                productForm.action = `/ProductMstr/${data.id}`;

                // Tambahkan @method('PUT') secara dinamis
                methodField.innerHTML = `<input type="hidden" name="_method" value="PUT">`;

                // Isi masing-masing input
                document.getElementById('edit_code').value = data.code;
                document.getElementById('edit_name').value = data.name;
                document.getElementById('edit_description').value = data.description;
                document.getElementById('edit_satuan').value = data.satuan;
                document.getElementById('edit_cat').value = data.cat;
                document.getElementById('edit_margin').value = data.margin;
                document.getElementById('edit_type').value = data.type;

                // Handle Checkbox (Switch)
                document.getElementById('edit_is_stockable').checked = data.is_stockable == 1;
                document.getElementById('edit_is_visible').checked = data.is_visible == 1;

                productModal.show();
            }
        </script>
        <script>
            function deleteProduct(id, name) {
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: `Produk "${name}" akan dihapus permanen!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Jalankan AJAX Delete
                        fetch(`/ProductMstr/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    Swal.fire('Terhapus!', data.message, 'success')
                                        .then(() => location.reload()); // Refresh halaman
                                } else {
                                    Swal.fire('Gagal!', data.message, 'error');
                                }
                            })
                            .catch(error => {
                                Swal.fire('Error!', 'Terjadi kesalahan sistem.', 'error');
                            });
                    }
                })
            }
        </script>
    @endpush
</x-app-layout>
