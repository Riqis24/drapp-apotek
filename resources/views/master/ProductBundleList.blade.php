<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            {{-- Cek apakah ini mode Edit atau Add --}}
            <h3>{{ isset($bundle) ? 'Edit Product Bundle' : 'Create Product Bundle' }}</h3>
        </div>
        <div class="page-content">
            <div class="card card-body">
                {{-- Action dinamis: Jika edit ke route update, jika baru ke route store --}}
                <form method="POST"
                    action="{{ isset($bundle) ? route('ProductBundle.update', $bundle->id) : route('ProductBundle.store') }}">
                    @csrf
                    @if (isset($bundle))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="code" class="form-label">Code</label>
                            <input type="text" class="form-control form-control-sm" name="code"
                                value="{{ old('code', $bundle->code ?? '') }}" placeholder="Contoh: OBAT-001" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="product" class="form-label">Product Name</label>
                            <input type="text" class="form-control form-control-sm" name="product"
                                value="{{ old('product', $bundle->name ?? '') }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control form-control-sm" name="description"
                                value="{{ old('description', $bundle->description ?? '') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="satuan" class="form-label">Satuan</label>
                            <input type="hidden" value="{{ $satuan->id }}" name="satuan">
                            <input type="text" value="bundle" class="form-control form-control-sm" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="cat" class="form-control form-control-sm">
                                @foreach ($cats as $cat)
                                    <option value="{{ $cat->product_cat_id }}"
                                        {{ isset($bundle) && $bundle->category == $cat->product_cat_id ? 'selected' : '' }}>
                                        {{ $cat->product_cat_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <input type="checkbox" name="is_stockable" value="1"
                                    {{ !isset($bundle) || $bundle->is_stockable == 1 ? 'checked' : '' }}>
                                Apakah termasuk dalam stok?
                            </label>
                        </div>
                    </div>

                    <table class="table" id="bundleTable">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Unit</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>
                                    <button type="button" class="btn btn-sm btn-success" id="addRow">+</button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Jika mode EDIT, tampilkan baris yang sudah ada --}}
                            @if (isset($bundle))
                                @foreach ($bundle->bundleItems as $index => $detail)
                                    <tr>
                                        <td>
                                            <select name="items[{{ $index }}][product_id]"
                                                class="form-select product-select" style="width:100%">
                                                <option value="{{ $detail->bundle_product_id }}" selected>
                                                    {{ $detail->productMeasurement->product->name }}</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="items[{{ $index }}][product_measurement_id]"
                                                class="form-select measurement-select" style="width:100%">
                                                <option value="{{ $detail->productMeasurement->id }}" selected>
                                                    {{ $detail->productMeasurement->measurement->name }}</option>
                                            </select>
                                        </td>
                                        <td></td>
                                        <td><input type="number" name="items[{{ $index }}][qty]"
                                                class="form-control" value="{{ $detail->quantity }}" min="1">
                                        </td>
                                        <td><button type="button" class="btn btn-danger btn-sm removeRow">x</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>

                    <div class="mt-4">
                        <button class="btn btn-primary">{{ isset($bundle) ? 'Update Bundle' : 'Save Bundle' }}</button>
                        <a href="{{ route('ProductMstr.index') }}" class="btn btn-light-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Set index awal berdasarkan jumlah baris yang ada (penting untuk mode edit)
            let idx = {{ isset($bundle) ? $bundle->bundleItems->count() : 0 }};

            // Fungsi ADD ROW (Sama seperti punya kamu)
            $('#addRow').on('click', function() {
                let row = $(`
                    <tr>
                        <td><select name="items[${idx}][product_id]" class="form-select product-select" style="width:100%"></select></td>
                        <td><select name="items[${idx}][product_measurement_id]" class="form-select measurement-select" style="width:100%"></select></td>
                        <td><input type="text" class="form-control price" readonly></td>
                        <td><input type="number" name="items[${idx}][qty]" class="form-control" value="1" min="1"></td>
                        <td><button type="button" class="btn btn-danger btn-sm removeRow">x</button></td>
                    </tr>
                `);
                $('#bundleTable tbody').append(row);
                initProductSelect(row.find('.product-select'));
                idx++;
            });

            // Inisialisasi Select2 untuk baris yang sudah ada (saat mode Edit)
            $(document).ready(function() {
                $('.product-select').each(function() {
                    if (!$(this).hasClass("select2-hidden-accessible")) {
                        initProductSelect($(this));
                    }
                });

                // Jika mode ADD dan tabel kosong, tambah baris pertama otomatis
                if (idx === 0) {
                    $('#addRow').click();
                }
            });

            // --- Fungsi initProductSelect & initMeasurementSelect tetap sama seperti punya kamu ---
            function initProductSelect(el) {
                el.select2({
                    placeholder: 'Pilih produk',
                    ajax: {
                        url: '/products/single',
                        dataType: 'json',
                        delay: 250,
                        processResults: res => ({
                            results: res
                        })
                    }
                });
            }

            function initMeasurementSelect(el, productId) {
                el.select2({
                    placeholder: 'Pilih satuan',
                    ajax: {
                        url: `/product-measurements/${productId}`,
                        dataType: 'json',
                        processResults: res => ({
                            results: res
                        })
                    }
                });
            }

            $(document).on('select2:select', '.product-select', function(e) {
                let row = $(this).closest('tr');
                let measurement = row.find('.measurement-select');
                measurement.empty().trigger('change');
                initMeasurementSelect(measurement, e.params.data.id);
            });

            $(document).on('select2:select', '.measurement-select', function(e) {
                $(this).closest('tr').find('.price').val(e.params.data.price);
            });

            $(document).on('click', '.removeRow', function() {
                $(this).closest('tr').remove();
            });
        </script>
    @endpush
</x-app-layout>
