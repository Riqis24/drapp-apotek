<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>TS Form</h3>
        </div>
        <div class="page-content">
            <div class="card card-body">
                <form method="POST" action="{{ route('TsMstr.store') }}">
                    @csrf

                    <div class="row mb-3">
                        <div class="col">
                            <label>Tanggal</label>
                            <input type="date" name="date" class="form-control" required>
                        </div>

                        <div class="col">
                            <label>Dari Gudang</label>
                            <select name="from_loc" class="form-control" required>
                                @foreach ($locations as $loc)
                                    <option value="{{ $loc->loc_mstr_id }}">
                                        {{ $loc->loc_mstr_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col">
                            <label>Ke Gudang</label>
                            <select name="to_loc" class="form-control" required>
                                @foreach ($locations as $loc)
                                    <option value="{{ $loc->loc_mstr_id }}">
                                        {{ $loc->loc_mstr_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <hr>

                    <table class="table" id="itemTable">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Batch</th>
                                <th>Qty</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <button type="button" class="btn btn-secondary" onclick="addRow()">+ Item</button>

                    <hr>
                    <button class="btn btn-success">Simpan</button>
                    <button type="button" onclick="window.location.href='{{ route('TsMstr.index') }}'"
                        class="btn btn-dark">Back</button>
                </form>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            let rowIndex = 0;

            function addRow() {
                const locId = $('[name=from_loc]').val();
                if (!locId) {
                    alert('Pilih gudang asal dulu');
                    return;
                }

                const row = `
    <tr data-row="${rowIndex}">
        <td>
            <select name="items[${rowIndex}][product_id]"
                class="form-control product-select" style="width:100%"
                data-row="${rowIndex}">
            </select>
        </td>

        <td>
            <select name="items[${rowIndex}][batch_id]"
                class="form-control batch-select"
                data-row="${rowIndex}">
            </select>
        </td>

        <td>
            <input type="number"
                name="items[${rowIndex}][qty]"
                class="form-control qty-input"
                step="0.01"
                min="0">
        </td>

        <td>
            <button type="button"
                class="btn btn-danger btn-sm"
                onclick="$(this).closest('tr').remove()">
                ✕
            </button>
        </td>
    </tr>
    `;

                $('#itemTable tbody').append(row);

                initProductSelect(rowIndex);
                rowIndex++;
            }
        </script>
        <script>
            function initProductSelect(row) {
                const $el = $(`.product-select[data-row="${row}"]`);

                if ($el.hasClass("select2-hidden-accessible")) {
                    $el.select2('destroy');
                }

                $el.select2({
                    placeholder: 'Pilih produk',
                    width: '100%',
                    dropdownParent: $('body'), // atau modal
                    ajax: {
                        url: "{{ route('TsMstr.items') }}",
                        delay: 250,
                        data: function(params) {
                            return {
                                loc_id: $('[name=from_loc]').val(),
                                q: params.term
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.map(item => ({
                                    id: item.product_id,
                                    text: item.text
                                }))
                            };
                        },
                        cache: true
                    },
                    // minimumInputLength: 1
                }).on('change', function() {
                    loadBatch(row, $(this).val());
                });
            }
        </script>

        <script>
            function loadBatch(row, productId) {
                const $batch = $(`.batch-select[data-row="${row}"]`);

                // destroy dulu kalau sudah select2
                if ($batch.hasClass('select2-hidden-accessible')) {
                    $batch.select2('destroy');
                }

                $batch.empty().append('<option value="">Pilih batch</option>');

                if (!productId) return;

                $.get("{{ route('TsMstr.batches') }}", {
                    loc_id: $('[name=from_loc]').val(),
                    product_id: productId
                }, function(res) {

                    res.forEach(b => {
                        $batch.append(
                            `<option value="${b.batch_id}">
                    ${b.text} (${b.qty_base})
                </option>`
                        );
                    });

                    // init ulang select2
                    $batch.select2({
                        placeholder: 'Pilih batch',
                        width: '100%',
                        dropdownParent: $('body') // atau modal
                    });

                    // optional: auto select batch pertama
                    // $batch.val(res[0]?.batch_id).trigger('change');
                });
            }
        </script>
    @endpush
</x-app-layout>
