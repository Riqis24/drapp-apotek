<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Purchase Order Form</h3>
        </div>
        <div class="page-content">
            <form action="{{ route('PurchaseOrder.store') }}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <strong>Purchase Order</strong>
                    </div>

                    <div class="card-body row g-3">

                        <div class="col-md-3">
                            <label>PO Date</label>
                            <input type="date" name="po_date" class="form-control" required>
                        </div>

                        <div class="col-md-3">
                            <label>ETA</label>
                            <input type="date" name="po_eta" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label>Supplier</label>
                            <select name="suppid" class="form-control select2" required>
                                <option value="">-- pilih supplier --</option>
                                @foreach ($suppliers as $s)
                                    <option value="{{ $s->supp_mstr_id }}">
                                        {{ $s->supp_mstr_code . ' | ' . $s->supp_mstr_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- PAYMENT TYPE --}}
                        <div class="col-md-3">
                            <label>Jenis Pembayaran</label>
                            <select name="payment_type" id="payment_type" class="form-control" required>
                                <option value="cash">Tunai</option>
                                <option value="credit">Hutang</option>
                            </select>
                        </div>

                        {{-- DUE DATE --}}
                        <div class="col-md-3">
                            <label>Jatuh Tempo</label>
                            <input type="date" name="due_date" id="due_date" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label>Diskon PO</label>
                            <select name="disctype" id="po_disctype" class="form-control">
                                <option value="">-</option>
                                <option value="percent">%</option>
                                <option value="amount">Nominal</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Nilai Diskon</label>
                            <input type="number" name="discvalue" id="po_discvalue" class="form-control"
                                value="0">
                        </div>

                        <div class="col-md-3">
                            <label>PPN</label>
                            <select name="ppntype" id="ppntype" class="form-control"
                                onchange="handlePpnType(this.value)">
                                <option value="none">None</option>
                                <option value="include">Active</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>PPN Rate (%)</label>
                            <input type="number" name="ppnrate" id="ppnrate" class="form-control">
                        </div>

                        {{-- NOTE --}}
                        <div class="col-md-12">
                            <label>Catatan PO</label>
                            <textarea name="po_note" class="form-control" rows="2"></textarea>
                        </div>

                    </div>
                </div>

                <div class="card">
                    <div class="card-body">

                        <table class="table table-bordered" id="poTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="35%">Produk</th>
                                    <th width="10%">Um</th>
                                    <th width="10%">Qty</th>
                                    <th width="15%">Harga</th>
                                    <th width="15%">Diskon</th>
                                    <th width="15%">Total</th>
                                    <th width="5%"></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                        <button type="button" class="btn btn-sm btn-primary" id="addRow">
                            + Tambah Item
                        </button>

                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-6"></div>

                    <div class="col-md-6">
                        <div class="card shadow-sm border-0">
                            <div class="card-body p-3">

                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Subtotal</span>
                                    <strong id="subtotal" class="fs-6">0</strong>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Diskon Global</span>
                                    <strong id="disc_global" class="fs-6">0</strong>
                                </div>

                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">PPN</span>
                                    <strong id="ppnTtl" class="fs-6">0</strong>
                                </div>

                                <hr class="my-2">

                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold fs-5">Grand Total</span>
                                    <span id="grand_total" class="fw-bold fs-4 text-primary">0</span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3 text-end">
                    <div class="mt-3">
                        <button class="btn btn-success w-100 d-md-none">
                            <i class="bi bi-save"></i> Simpan PO
                        </button>
                        <button class="btn btn-lg btn-success d-none d-md-inline-block float-end">
                            Simpan PO
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <div class="modal fade" id="priceHistoryModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Harga Lebih Murah</h5>
                </div>
                <div class="modal-body">
                    <p><strong>Supplier:</strong> <span id="ph-supplier"></span></p>
                    <p><strong>Harga:</strong> <span id="ph-price"></span></p>
                    <p><strong>Tanggal:</strong> <span id="ph-date"></span></p>
                </div>
                {{-- <div class="modal-footer">
                    <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div> --}}
            </div>
        </div>
    </div>

    {{-- modal history harga --}}
    <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="historyModalLabel text-white">
                        <i class="bi bi-clock-history"></i> History Pembelian
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered" id="tablePriceHistory">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Supplier</th>
                                    <th>Qty</th>
                                    <th class="text-end">Harga</th>
                                </tr>
                            </thead>
                            <tbody id="historyContent">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <style>
            @media (max-width: 767.98px) {

                /* Sembunyikan Header Tabel */
                #poTable thead {
                    display: none;
                }

                /* Ubah setiap baris menjadi card */
                #poTable tr.po-line {
                    display: block;
                    margin-bottom: 1.5rem;
                    padding: 1rem;
                    border: 1px solid #dee2e6;
                    border-radius: 0.5rem;
                    background: #fff;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
                }

                #poTable td {
                    display: block;
                    width: 100% !important;
                    padding: 0.25rem 0;
                    border: none;
                    text-align: left;
                }

                /* Tambahkan label bantu di HP */
                #poTable td::before {
                    content: attr(data-label);
                    font-weight: bold;
                    display: block;
                    text-transform: uppercase;
                    font-size: 0.7rem;
                    color: #6c757d;
                    margin-bottom: 2px;
                }

                .removeRow {
                    width: 100%;
                    margin-top: 10px;
                }

                /* Layouting Grand Total agar tidak makan tempat */
                .card-body.p-3 strong {
                    font-size: 1rem !important;
                }

                #grand_total {
                    font-size: 1.2rem !important;
                }
            }
        </style>
        <script>
            let rowIndex = 0;

            function addRow(item = {}) {
                let pId = item.productid || item.po_det_productid || '';
                let row = `
<tr class="po-line">
    <td data-label="Produk">
        <select name="items[${rowIndex}][productid]" 
                        class="form-control select2 product-select" 
                        onchange="updateInlineHistory(this, ${rowIndex})" required>
                    <option value="">-- pilih produk --</option>
                    @foreach ($products as $p)
                        <option value="{{ $p->id }}" ${pId == {{ $p->id }} ? 'selected' : ''}>
                            {{ $p->name }}
                        </option>
                    @endforeach
                </select>
        <div id="history-inline-${rowIndex}" class="mt-1"></div>
        <small class="text-warning price-warning"></small>
    </td>

    <td data-label="Satuan (UM)">
        <select name="items[${rowIndex}][umid]" class="form-control select2 um" required>
            <option value="">-- um --</option>
        </select>
    </td>

    <td data-label="Quantity">
        <input type="number" name="items[${rowIndex}][qty]" class="form-control qty" value="1">
    </td>

    <td data-label="Harga Satuan">
        <input type="number" name="items[${rowIndex}][price]" class="form-control price" value="0">
    </td>

    <td data-label="Diskon per Item">
        <div class="input-group">
            <select name="items[${rowIndex}][disctype]" class="form-select disctype" style="width: 40%;">
                <option value="amount">Rp</option>
                <option value="percent">%</option>
            </select>
            <input type="number" name="items[${rowIndex}][discvalue]" class="form-control disc" value="0" style="width: 60%;">
        </div>
    </td>

    <td data-label="Subtotal">
        <input type="number" class="form-control total" readonly>
    </td>

    <td>
        <button type="button" class="btn btn-danger btn-sm removeRow">×</button>
    </td>
</tr>
`;
                $('#poTable tbody').append(row);
                if (pId) {
                    // Beri jeda sebentar agar DOM selesai merender
                    setTimeout(() => {
                        updateInlineHistory($(`select[name="items[${rowIndex}][productid]"]`), rowIndex);
                    }, 200);
                }

                // Inisialisasi Select2 dengan width 100% agar tidak berantakan di HP
                $('.select2').select2({
                    width: '100%',
                    theme: "bootstrap-5"
                });

                rowIndex++;
            }
            $(document).on('click', '#addRow', addRow);

            $(document).on('click', '.removeRow', function() {
                $(this).closest('tr').remove();
            });

            $(document).on('input change', '.qty, .price, .disc, .disctype', function() {
                let row = $(this).closest('tr');

                // Ambil nilai input
                let qty = parseFloat(row.find('.qty').val()) || 0;
                let price = parseFloat(row.find('.price').val()) || 0;
                let discValue = parseFloat(row.find('.disc').val()) || 0;
                let discType = row.find('.disctype').val() || 'amount'; // Tambahkan ini

                let lineTotal = qty * price;
                let discCalculated = 0;

                // Logika Hitung Diskon
                if (discType === 'percent') {
                    discCalculated = lineTotal * (discValue / 100);
                } else {
                    discCalculated = discValue;
                }

                let finalTotal = lineTotal - discCalculated;

                // Set nilai ke input total di baris tersebut
                // Gunakan .toFixed(2) jika ingin mendukung angka desimal/pecahan
                row.find('.total').val(finalTotal);

                // Panggil fungsi hitung grand total (opsional jika ada)
                // calculateGrandTotal();
            });

            // init
            addRow();
        </script>
        <script>
            function updateInlineHistory(element, rowIndex) {
                const productId = $(element).val();
                const container = $(`#history-inline-${rowIndex}`);
                console.log(productId)
                if (!productId) {
                    container.html('');
                    return;
                }

                container.html('<small class="text-muted"><i class="fa fa-spinner fa-spin"></i> Checking...</small>');

                $.get(`/BpbMstr/getPriceHistory/${productId}`, function(res) {
                    if (res && res.length > 0) {
                        // Ambil data terbaru (index 0)
                        const latest = res[0];
                        const formattedPrice = new Intl.NumberFormat('id-ID').format(latest.price);

                        container.html(`
                <div class="d-flex justify-content-between align-items-center bg-light p-1 border-start border-info border-3" style="font-size: 0.75rem;">
                    <div>
                        <span class="text-muted">Last:</span> 
                        <strong class="text-primary">Rp ${formattedPrice}</strong>
                        <span class="text-muted d-block" style="font-size: 0.65rem;">${latest.bpb_date} - ${latest.supplier_name.substring(0, 15)}...</span>
                    </div>
                    <button type="button" class="btn btn-xs btn-outline-info p-0 px-1" 
                            onclick="viewHistory(${productId})" title="Lihat semua history">
                        <i class="bi bi-clock-history"></i>
                    </button>
                </div>
            `);
                    } else {
                        container.html(
                            '<small class="text-muted" style="font-size: 0.7rem;">Belum ada history</small>');
                    }
                });
            }

            function viewHistory(productId) {
                // 1. Tampilkan modal
                $('#historyModal').modal('show');

                // 2. Hancurkan DataTable lama jika sudah ada
                if ($.fn.DataTable.isDataTable('#tablePriceHistory')) {
                    $('#tablePriceHistory').DataTable().destroy();
                }

                // 3. Set loading state
                $('#historyContent').html('<tr><td colspan="4" class="text-center">Loading...</td></tr>');

                $.get(`/BpbMstr/getPriceHistory/${productId}`, function(res) {
                    let html = '';
                    res.forEach(h => {
                        html += `
            <tr>
                <td>${h.bpb_date}</td>
                <td>${h.supplier_name}</td>
                <td>${h.qty} ${h.um}</td>
                <td class="text-end" data-order="${h.price}">
                    ${new Intl.NumberFormat('id-ID').format(h.price)}
                </td>
            </tr>`;
                    });

                    $('#historyContent').html(html ||
                        '<tr><td colspan="4" class="text-center">Belum ada history</td></tr>');

                    // 4. Inisialisasi DataTables
                    if (res.length > 0) {
                        $('#tablePriceHistory').DataTable({
                            searching: true,
                            info: false,
                            scrollY: "400px",
                            scrollCollapse: true,
                            order: [
                                [0, 'desc']
                            ]
                        });
                    }
                });
            }
        </script>
        <script>
            $(document).on('change', '.item', function() {
                let row = $(this).closest('tr');

                // hanya trigger jika yang berubah adalah product select
                if (!$(this).is('select[name*="[productid]"]')) return;

                let productId = $(this).val();
                let umSelect = row.find('.um');

                umSelect.html('<option value="">loading...</option>').trigger('change');

                if (!productId) {
                    umSelect.html('<option value="">-- um --</option>');
                    return;
                }

                $.get(`/PurchaseOrder/product/${productId}/ums`, function(res) {
                    let opt = '<option value="">-- um --</option>';
                    res.forEach(u => {
                        opt += `<option value="${u.id}">${u.name}</option>`;
                    });

                    umSelect.html(opt).trigger('change.select2');
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const paymentType = document.getElementById('payment_type');
                const dueDate = document.getElementById('due_date');

                function toggleDueDate() {
                    if (paymentType.value === 'credit') {
                        dueDate.required = true;
                    } else {
                        dueDate.required = false;
                        dueDate.value = '';
                    }
                }

                paymentType.addEventListener('change', toggleDueDate);
                toggleDueDate();
            });
        </script>

        <script>
            function handlePpnType(val) {
                const rateInput = document.getElementById('ppnrate');

                if (val === 'include') {
                    rateInput.value = 11;
                } else {
                    rateInput.value = '';
                }
            }
        </script>
        <script>
            $(document).on('blur', '.price', function() {

                let row = $(this).closest('tr');

                let productId = row.find('select[name*="[productid]"]').val();
                let umId = row.find('select[name*="[umid]"]').val();
                let price = parseFloat($(this).val()) || 0;

                if (!productId || !umId || price <= 0) return;

                $.get('/priceHistory', {
                    product_id: productId,
                    um_id: umId,
                    price: price
                }, function(res) {
                    console.log(res);
                    let warn = row.find('.price-warning');

                    if (res.found && res.cheaper) {
                        warn.html(`
                ⚠ Harga lebih mahal dari histori
                <button
                     type="button"
                     class="btn btn-outline-warning btn-sm show-price-history"
                     data-bs-toggle="modal"
                     data-bs-target="#priceHistoryModal"
                     data-supplier="${res.supplier}"
                   data-price="${res.best_price}"
                   data-date="${res.date}">
                        💡 Harga Lebih Murah
                </button>
            `);
                    } else {
                        warn.html('');
                    }
                });
            });
        </script>
        <script>
            function rupiah(num) {
                return 'Rp ' + (num || 0).toLocaleString('id-ID');
            }
        </script>
        <script>
            $(document).on('click', '.show-price-history', function(e) {
                e.preventDefault();

                $('#ph-supplier').text($(this).data('supplier'));
                $('#ph-price').text(rupiah($(this).data('price')));
                $('#ph-date').text($(this).data('date'));

                new bootstrap.Modal(document.getElementById('priceHistoryModal')).show();
            });
        </script>


        <script>
            function calculateTotal() {
                let subtotal = 0;

                document.querySelectorAll('.po-line').forEach(row => {
                    let qty = parseFloat(row.querySelector('.qty')?.value) || 0;
                    let price = parseFloat(row.querySelector('.price')?.value) || 0;
                    let discValue = parseFloat(row.querySelector('.disc')?.value) || 0;
                    let discType = row.querySelector('.disctype')?.value || 'amount'; // Ambil tipe diskon

                    let lineTotal = qty * price;
                    let discCalculated = 0;

                    if (discType === 'percent') {
                        // Jika persen: (Qty * Harga) * (Nilai Persen / 100)
                        discCalculated = lineTotal * (discValue / 100);
                    } else {
                        // Jika amount: Langsung kurangi nilai diskon
                        discCalculated = discValue;
                    }

                    subtotal += (lineTotal - discCalculated);
                });

                // ==== GLOBAL DISCOUNT (AUTO) ====
                let discType = document.getElementById('po_disctype')?.value;
                let discValue = parseFloat(document.getElementById('po_discvalue')?.value) || 0;

                let globalDisc = 0;

                if (discType === 'percent') {
                    globalDisc = subtotal * (discValue / 100);
                } else if (discType === 'amount') {
                    globalDisc = discValue;
                }

                let afterDisc = Math.max(0, subtotal - globalDisc);

                // ==== PPN ====
                let ppnRate = parseFloat(document.getElementById('ppnrate')?.value) || 0;
                let ppn = afterDisc * (ppnRate / 100);

                let grandTotal = afterDisc + ppn;

                // ==== DISPLAY ====
                document.getElementById('subtotal').innerText = subtotal.toLocaleString();
                document.getElementById('disc_global').innerText = globalDisc.toLocaleString();
                document.getElementById('ppnTtl').innerText = ppn.toLocaleString();
                document.getElementById('grand_total').innerText = grandTotal.toLocaleString();
            }

            // trigger global
            document.addEventListener('input', calculateTotal);
        </script>


        <script src="{{ 'assets/js/ExpenseTr/getData.js' }}"></script>
        <script src="{{ 'assets/js/alert.js' }}"></script>
    @endpush
</x-app-layout>
