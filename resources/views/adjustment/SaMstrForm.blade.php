<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Adjustment Form</h3>
        </div>
        <div class="page-content">
            <div class="card card-body">
                <form method="POST" action="{{ route('SaMstr.store') }}">
                    @csrf

                    {{-- HEADER --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>Location</label>
                            <select name="loc_id" id="loc_id" class="form-select" required>
                                <option value="">-- Select Location --</option>
                                @foreach ($locations as $loc)
                                    <option value="{{ $loc->loc_mstr_id }}">{{ $loc->loc_mstr_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Reference</label>
                            <input type="text" name="ref" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label>Reason</label>
                            <input type="text" name="reason" class="form-control">
                        </div>
                    </div>

                    {{-- ITEMS --}}
                    <table class="table table-bordered" id="itemTable">
                        <thead class="table-light">
                            <tr>
                                <th width="22%">Product</th>
                                <th width="28%">Batch</th>
                                <th width="10%">Qty System</th>
                                <th width="10%">Qty Physical</th>
                                <th>Note</th>
                                <th width="5%"></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <button type="button" class="btn btn-secondary mb-3" id="btnAddRow">
                        + Add Item
                    </button>


                    <div class="text-end">
                        <button class="btn btn-primary">Save Draft</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    @push('scripts')
        <script>
            let rowIndex = 0;

            /* ==========================
               ADD ROW
            ========================== */
            $('#btnAddRow').on('click', function() {
                const loc = $('#loc_id').val();
                if (!loc) {
                    alert('Please select location first');
                    return;
                }

                rowIndex++;

                $('#itemTable tbody').append(`
<tr data-row="${rowIndex}">
    <td>
        <select name="items[${rowIndex}][product_id]"
                class="form-select product-select"
                data-row="${rowIndex}" required>
            <option value="">-- Select Product --</option>
            @foreach ($products as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endforeach
        </select>
    </td>

    <td>
        <select name="items[${rowIndex}][batch_id]"
                class="form-select batch-select"
                data-row="${rowIndex}">
            <option value="">-- Existing Batch --</option>
        </select>

        <div class="mt-1 d-none new-batch-area">
            <input type="text"
                   name="items[${rowIndex}][batch_no]"
                   class="form-control mb-1"
                   placeholder="New Batch No">

            <input type="date"
                   name="items[${rowIndex}][expired_date]"
                   class="form-control">
        </div>

        <div class="form-check mt-1">
            <input class="form-check-input new-batch-toggle"
                   type="checkbox">
            <label class="form-check-label">New Batch</label>
        </div>
    </td>

    <td>
        <input type="number"
               name="items[${rowIndex}][qty_system]"
               class="form-control text-end qty-system"
               value="0"
               readonly>
    </td>

    <td>
        <input type="number"
               name="items[${rowIndex}][qty_physical]"
               class="form-control text-end"
               required>
    </td>

    <td>
        <input type="text"
               name="items[${rowIndex}][note]"
               class="form-control">
    </td>

    <td class="text-center">
        <button type="button"
                class="btn btn-danger btn-sm btn-remove">✕</button>
    </td>
</tr>
`);
            });

            /* ==========================
               REMOVE ROW
            ========================== */
            $(document).on('click', '.btn-remove', function() {
                $(this).closest('tr').remove();
            });

            /* ==========================
               TOGGLE NEW BATCH
            ========================== */
            $(document).on('change', '.new-batch-toggle', function() {
                const tr = $(this).closest('tr');
                const isNew = $(this).is(':checked');

                tr.find('.batch-select').toggleClass('d-none', isNew).val('');
                tr.find('.new-batch-area').toggleClass('d-none', !isNew);
                tr.find('.qty-system').val(0);
            });

            /* ==========================
               LOAD BATCH BY PRODUCT
            ========================== */
            $(document).on('change', '.product-select', function() {
                const tr = $(this).closest('tr');
                const product = $(this).val();
                const loc = $('#loc_id').val();
                const batchSelect = tr.find('.batch-select');

                batchSelect.html('<option value="">Loading...</option>');
                tr.find('.qty-system').val(0);

                if (!product || !loc) {
                    batchSelect.html('<option value="">-- Existing Batch --</option>');
                    return;
                }

                $.ajax({
                    url: "{{ route('batches.by-product') }}",
                    data: {
                        product_id: product,
                        loc_id: loc
                    },
                    success: function(res) {
                        batchSelect.empty().append('<option value="">-- Existing Batch --</option>');

                        if (res.length === 0) {
                            batchSelect.append('<option value="">(No batch found)</option>');
                            return;
                        }

                        res.forEach(b => {
                            batchSelect.append(`
<option value="${b.batch_mstr_id}">
${b.batch_mstr_no} | Exp: ${b.batch_mstr_expireddate}
</option>
`);
                        });
                    },
                    error: function() {
                        batchSelect.html('<option value="">Error loading batch</option>');
                    }
                });
            });

            /* ==========================
               LOAD QTY SYSTEM
            ========================== */
            $(document).on('change', '.batch-select', function() {
                const tr = $(this).closest('tr');
                const product = tr.find('.product-select').val();
                const batch = $(this).val();
                const loc = $('#loc_id').val();

                tr.find('.qty-system').val(0);

                if (!batch) return;

                $.get("{{ route('stock.qty') }}", {
                    product_id: product,
                    batch_id: batch,
                    loc_id: loc
                }, function(res) {
                    tr.find('.qty-system').val(res.quantity ?? 0);
                });
            });

            /* ==========================
               RESET TABLE IF LOCATION CHANGE
            ========================== */
            $('#loc_id').on('change', function() {
                $('#itemTable tbody').empty();
                rowIndex = 0;
            });
        </script>
    @endpush


</x-app-layout>
