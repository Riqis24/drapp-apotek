<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Form Penerimaan Barang</h3>
        </div>
        <div class="page-content">
            <div class="card card-body">
                <form action="{{ isset($bpb) ? route('BpbMstr.update', $bpb->bpb_mstr_id) : route('BpbMstr.store') }}"
                    method="POST">
                    @csrf
                    @if (isset($bpb))
                        @method('PUT')
                    @endif

                    {{-- <input type="text" name="nofaktur" value="{{ old('nofaktur', $bpb->bpb_mstr_nofaktur ?? '') }}"> --}}

                    <div class="row g-3 mb-3">
                        <div class="col-md-5">
                            <label>PO</label>
                            <div class="input-group">
                                <select name="poId" class="form-control select2" id="poSelect"
                                    {{ isset($bpb) ? 'disabled' : '' }}>
                                    <option value="">-- Pilih PO --</option>
                                    @foreach ($pos as $po)
                                        <option value="{{ $po->po_mstr_id }}"
                                            {{ old('poId', $bpb->bpb_mstr_poid ?? '') == $po->po_mstr_id ? 'selected' : '' }}>
                                            {{ $po->po_mstr_nbr . ' [' . $po->supplier->supp_mstr_name . ']' }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-danger" id="btnResetPo" title="Ganti PO">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </button>
                            </div>


                            {{-- Jika mode edit, select di-disable, maka kita butuh input hidden agar poId tetap terkirim --}}
                            @if (isset($bpb))
                                <input type="hidden" name="poId" value="{{ $bpb->bpb_mstr_poid }}">
                            @endif
                        </div>

                        {{-- Gudang: Default ID tertentu jika perlu --}}
                        <div class="col-md-4">
                            <label>Gudang</label>
                            <select name="loc_id" class="form-select select2" required>
                                @foreach ($locs as $loc)
                                    <option value="{{ $loc->loc_mstr_id }}"
                                        {{ old('loc_id', $bpb->bpb_mstr_locid ?? '') == $loc->loc_mstr_id ? 'selected' : '' }}>
                                        {{ $loc->loc_mstr_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tanggal BPB: Default hari ini jika Tambah Baru --}}
                        <div class="col-md-3">
                            <label>Tanggal BPB</label>
                            <input type="date" name="bpb_date" class="form-control" required
                                value="{{ old('bpb_date', $bpb->bpb_mstr_date ?? date('Y-m-d')) }}">
                        </div>

                        <div class="col-md-4">
                            <label>No Faktur</label>
                            <input type="text" name="nofaktur" class="form-control"
                                value="{{ old('nofaktur', $bpb->bpb_mstr_nofaktur ?? '') }}">
                        </div>

                        <div class="col-md-4">
                            <label>No SJ</label>
                            <input type="text" name="nosj" class="form-control"
                                value="{{ old('nosj', $bpb->bpb_mstr_nosj ?? '') }}">
                        </div>

                        <div class="col-md-4">
                            <label>Catatan</label>
                            <input type="text" name="note" class="form-control"
                                value="{{ old('note', $bpb->bpb_mstr_note ?? '') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Supplier</label>

                            @if (isset($bpb))
                                <input type="hidden" name="suppid" id="suppid"
                                    value="{{ old('suppid', $bpb->bpb_mstr_suppid) }}">
                                <input type="text" id="suppname" class="form-control bg-light" readonly
                                    value="{{ old('suppname', $bpb->supplier->supp_mstr_name ?? '') }}">
                            @else
                                <select name="suppid" id="suppid" class="form-control select2" required>
                                    <option value="">-- Pilih Supplier --</option>
                                    @foreach ($suppliers as $s)
                                        <option value="{{ $s->supp_mstr_id }}"
                                            {{ old('suppid') == $s->supp_mstr_id ? 'selected' : '' }}>
                                            {{ $s->supp_mstr_name }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        {{-- Jenis Pembayaran --}}
                        <div class="col-md-3">
                            <label>Jenis Pembayaran</label>
                            <select name="payment_type" id="payment_type" class="form-control" required>
                                <option value="cash"
                                    {{ old('payment_type', $bpb->bpb_mstr_payment ?? '') == 'cash' ? 'selected' : '' }}>
                                    Tunai</option>
                                <option value="credit"
                                    {{ old('payment_type', $bpb->bpb_mstr_payment ?? '') == 'credit' ? 'selected' : '' }}>
                                    Hutang</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Jatuh Tempo</label>
                            <input type="date" name="due_date" id="due_date" class="form-control"
                                value="{{ old('due_date', $bpb->bpb_mstr_duedate ?? '') }}">
                        </div>

                        <div class="col-md-3">
                            <label>Diskon PO</label>
                            <select name="disctype" id="po_disctype" class="form-control">
                                <option value="amount"
                                    {{ old('disctype', $bpb->bpb_mstr_disctype ?? 'amount') == 'amount' ? 'selected' : '' }}>
                                    Nominal</option>
                                <option value="percent"
                                    {{ old('disctype', $bpb->bpb_mstr_disctype ?? '') == 'percent' ? 'selected' : '' }}>
                                    %</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Nilai Diskon</label>
                            <input type="number" name="discvalue" id="po_discvalue" class="form-control"
                                value="{{ old('discvalue', $bpb->bpb_mstr_discvalue ?? 0) }}">
                        </div>

                        <div class="col-md-3">
                            <label>PPN</label>
                            <select name="ppntype" id="ppntype" class="form-control">
                                <option value="none"
                                    {{ old('ppntype', $bpb->bpb_mstr_ppntype ?? 'none') == 'none' ? 'selected' : '' }}>
                                    None</option>
                                <option value="include"
                                    {{ old('ppntype', $bpb->bpb_mstr_ppntype ?? '') == 'include' ? 'selected' : '' }}>
                                    Active</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>PPN Rate (%)</label>
                            <input type="number" name="ppnrate" id="ppnrate" class="form-control"
                                value="{{ old('ppnrate', $bpb->bpb_mstr_ppnrate ?? 11) }}">
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="fw-bold">Tambah Produk Manual (Tanpa PO / Tambahan)</label>
                                    <select id="manualProductSelect" class="form-control select2">
                                        <option value="">-- Cari Nama Barang --</option>
                                        {{-- Loop semua produk dari database --}}
                                        @foreach ($allProducts as $p)
                                            <option value="{{ $p->product_id }}"
                                                data-margin="{{ $p->product->margin }}"
                                                data-name="{{ $p->product->name }}"
                                                data-um="{{ $p->measurement_id }}"
                                                data-umname="{{ $p->measurement->name }}"
                                                data-umconv="{{ $p->conversion }}">
                                                {{ $p->product->name . ' [' . $p->measurement->name . ']' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-primary" id="btnAddManual">
                                        <i class="bi bi-plus-circle"></i> Tambah
                                    </button>
                                </div>
                            </div>
                            <table class="table table-bordered" style="overflow-y:auto;" id="bpbTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th style="width: 6%">Satuan</th>
                                        <th style="width: 8%">Qty</th>
                                        <th style="width: 12%">Harga</th>
                                        <th style="width: 16%">Disc</th>
                                        <th style="width: 16%">Batch</th>
                                        <th style="width: 12%">Sub Total</th>
                                        <th style="width: 5%">Update Price</th>
                                        <th style="width: 5%"></th>
                                    </tr>
                                </thead>
                                <tbody id="bpbTableBody"></tbody>
                            </table>
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
                        <button type="submit" class="btn btn-primary">
                            {{ isset($bpb) ? 'Update BPB' : 'Simpan BPB' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modal update harga --}}
    <div class="modal fade" id="priceUpdateModal" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white">Pengaturan Harga Jual</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="fw-bold mb-1">Pengaturan Harga Jual Berdasarkan Perubahan Harga Pembelian</p>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="bg-info text-center">
                                <tr class="text-white">
                                    <th>Kode</th>
                                    <th>Nama Barang</th>
                                    <th>Unit</th>
                                    <th>Hrg. Beli Lama</th>
                                    <th>Hrg. Beli Baru</th>
                                    <th>Hrg. Jual Lama</th>
                                    <th style="width: 200px">Hrg. Jual Baru</th>
                                </tr>
                            </thead>
                            <tbody id="priceUpdateContent">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="saveNewPrices()">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
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

                /* Sembunyikan Header Tabel Original */
                #bpbTable thead {
                    display: none;
                }

                /* Ubah Baris Tabel menjadi Kartu */
                #bpbTable tr.bpb-line {
                    display: block;
                    margin-bottom: 1.5rem;
                    padding: 1rem;
                    border: 1px solid #dee2e6;
                    border-radius: 0.5rem;
                    background: #fff;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
                }

                #bpbTable td {
                    display: block;
                    width: 100% !important;
                    padding: 0.25rem 0;
                    border: none;
                    text-align: left;
                }

                /* Label untuk Mobile menggunakan data-label */
                #bpbTable td::before {
                    content: attr(data-label);
                    font-weight: bold;
                    display: block;
                    text-transform: uppercase;
                    font-size: 0.7rem;
                    color: #6c757d;
                    margin-bottom: 2px;
                }

                /* Khusus kolom Checkbox agar rapi di HP */
                #bpbTable td[data-label="Update Price"] {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    border-top: 1px dashed #eee;
                    margin-top: 10px;
                    padding-top: 10px;
                }

                .removeRow {
                    width: 100%;
                    margin-top: 10px;
                }
            }
        </style>
        <script>
            $('#manualProductSelect').select2({
                theme: "bootstrap-5"
            })
            $('#suppid').select2({
                theme: "bootstrap-5"
            })
            $('#poSelect').select2({
                theme: "bootstrap-5"
            }).on('change', function() {
                let poId = $(this).val();

                if (!poId) {
                    resetPoForm();
                    return;
                }

                $.get("{{ route('po.detail', ':id') }}".replace(':id', poId), function(res) {

                    $('#suppname').val(res.supplier_name);

                    $('#suppid').val(res.supplier_id);

                    $('#payment_type').val(res.payment_type).trigger('change');

                    $('#due_date').val(res.due_date);

                    $('#po_disctype').val(res.disc_type);
                    $('#po_discvalue').val(res.disc_value);
                    $('#ppnrate').val(res.ppn_rate);

                    $('#ppntype').val(res.ppn_type).trigger('change');
                });
            });

            function resetPoForm() {
                $('#suppid').val('');
                $('#payment_type').val('cash');
                $('#due_date').val('');
                $('#po_disctype').val('');
                $('#po_discvalue').val(0);
                $('#ppntype').val('none');
                $('#ppnrate').val('');
            }
        </script>
        <script>
            function saveNewPrices() {
                let measurementsData = [];

                // Ambil semua input harga jual baru yang ada di tabel modal
                $('.new-sell-price').each(function() {
                    measurementsData.push({
                        id: $(this).data('pmid'), // ID dari product_measurements
                        price: $(this).val() // Nilai harga jual baru
                    });
                });

                if (measurementsData.length === 0) {
                    Swal.fire('Peringatan', 'Tidak ada data harga yang bisa disimpan.', 'warning');
                    return;
                }

                // Tampilkan loading saat proses simpan
                Swal.fire({
                    title: 'Menyimpan Harga...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '/BpbMstr/updateSellPrices', // Sesuaikan dengan route kamu
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'), // Pastikan ada meta tag CSRF
                        measurements: measurementsData
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Berhasil!', 'Harga jual telah diperbarui.', 'success');
                            $('#priceUpdateModal').modal('hide');
                        } else {
                            Swal.fire('Gagal', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan sistem.';
                        Swal.fire('Error', errorMsg, 'error');
                    }
                });
            }
        </script>
        <style>
            .row-price-up {
                background-color: #fff3cd !important;
                /* Warna kuning peringatan */
            }

            .text-price-up {
                color: #dc3545;
                /* Warna merah untuk angka harga */
                font-weight: bold;
            }

            .badge-price-up {
                font-size: 0.7rem;
                margin-left: 5px;
            }
        </style>
        <script>
            $('#btnResetPo').on('click', function() {
                // 1. Aktifkan kembali dropdown PO
                $('#poSelect').attr('readonly', false);
                $('#poSelect').parent().find('.select2-selection').css("background-color", "");
                $('#poSelect').parent().find('.select2-container').css("pointer-events", "");

                // 2. Kosongkan tabel dan reset supplier
                $('#poSelect').val(null).trigger('change');
                $('#bpbTable tbody').empty();
                $('#suppid').val(null).trigger('change');
                $('#suppname').val('');

                if ($('#suppid').is('select')) {
                    $('#suppid').val(null).trigger('change');
                    $('#suppid').parent().find('.select2-container').css("pointer-events", "");
                    $('#suppid').parent().find('.select2-selection').css("background-color", "");
                } else {
                    $('#suppid').val('');
                    $('#suppname').val('').removeClass('bg-light');
                }
            });
            let rowIndex = 0;

            $('#poSelect').on('change', function() {
                let poId = $(this).val();
                if (!poId) return;

                // Kunci dropdown PO agar tidak bisa diganti (Mode Readonly)
                $(this).attr('readonly', true);
                $(this).parent().find('.select2-selection').css("background-color",
                    "#e9ecef"); // Beri warna abu-abu khas readonly
                $(this).parent().find('.select2-container').css("pointer-events", "none"); // Kunci klik

                $('#bpbTable tbody').empty();
                rowIndex = 0;

                $.get(`/BpbMstr/getPoItems/${poId}`, function(response) {
                    // PERBAIKAN: Pastikan response berisi data supplier dan items
                    // Asumsi: response = { supplier_id: 1, supplier_name: 'PT ABC', items: [...] }

                    let supplierId = response.supplier_id;
                    let supplierName = response.supplier_name;
                    let items = response.items;

                    // ISI DATA SUPPLIER
                    if ($('#suppid').is('select')) {
                        $('#suppid').val(supplierId).trigger('change').attr('readonly', true);
                        $('#suppid').parent().find('.select2-selection').css("background-color", "#e9ecef");
                        $('#suppid').parent().find('.select2-container').css("pointer-events", "none");
                    } else {
                        // Jika mode Edit atau Readonly Input
                        $('#suppid').val(supplierId);
                        $('#suppname').val(supplierName);
                    }

                    // LOOP RENDER ITEMS
                    items.forEach(item => {
                        const bpbPrice = parseFloat(item.po_det_price) || 0;
                        const lastBuyPrice = item.pm ? parseFloat(item.pm.last_buy_price) : 0;
                        const isPriceUp = (bpbPrice > lastBuyPrice && lastBuyPrice > 0);

                        const rowClass = isPriceUp ? 'table-warning' : '';
                        const textPriceStyle = isPriceUp ?
                            'style="color: #dc3545; font-weight: bold;"' : '';

                        let row = `
                <tr class="bpb-line ${rowClass}">
                    <td data-label="Produk">
                        <input type="hidden" name="items[${rowIndex}][po_det_id]" value="${item.po_det_id}">
                        <input type="hidden" name="items[${rowIndex}][productid]" value="${item.po_det_productid}">
                        <span class="fw-bold text-primary">${item.product.name}</span>
                        <br>
                        <button type="button" class="btn btn-sm btn-link p-0 text-info" onclick="viewHistory(${item.po_det_productid})">
                            <i class="bi bi-clock-history"></i> History
                        </button>
                        ${isPriceUp ? `<div class="text-xs text-danger mt-1"><i class="bi bi-exclamation-triangle-fill"></i> Harga naik dari Rp ${new Intl.NumberFormat('id-ID').format(lastBuyPrice)}</div>` : ''}
                    </td>

                    <td data-label="Satuan (UM)">
                        <input type="hidden" name="items[${rowIndex}][umid]" value="${item.po_det_um}">
                        <input type="hidden" name="items[${rowIndex}][umconv]" value="${item.po_det_umconv}">
                        <span class="badge bg-light-secondary text-dark">${item.um.name}</span>
                    </td>

                    <td data-label="Qty Terima">
                        <input type="number" name="items[${rowIndex}][qty]" class="form-control bpb-qty-input qty" 
                               value="${item.po_det_qtyremain}" max="${item.po_det_qtyremain}">
                        <small class="text-muted text-xs">Sisa PO: ${item.po_det_qtyremain}</small>
                    </td>

                    <td data-label="Harga Beli">
                        <input type="number" name="items[${rowIndex}][price]" class="form-control bg-light bpb-price-input" 
                               value="${item.po_det_price}" ${textPriceStyle}>
                    </td>

                    <td data-label="Diskon">
                        <div class="input-group">
                            <select name="items[${rowIndex}][disctype]" class="form-select bpb-disctype-input" style="width: 70px;">
                                <option value="amount" ${item.po_det_disctype === 'amount' ? 'selected' : ''}>Rp</option>
                                <option value="percent" ${item.po_det_disctype === 'percent' ? 'selected' : ''}>%</option>
                            </select>
                            <input type="number" name="items[${rowIndex}][discvalue]" class="form-control bpb-discvalue-input" 
                                   value="${item.po_det_discvalue || 0}">
                        </div>
                    </td>

                    <td data-label="Batch & Exp">
                        <input type="text" name="items[${rowIndex}][batch_no]" class="form-control mb-1" placeholder="Batch" required>
                        <input type="date" name="items[${rowIndex}][expired_date]" class="form-control" required>
                    </td>

                    <td data-label="Subtotal">
                        <input type="text" class="form-control bg-light bpb-subtotal-display" readonly value="0">
                    </td>

                    <td data-label="Update" class="text-center">
                        <input type="hidden" name="items[${rowIndex}][margin]" value="${item.product.margin}">
                        <div class="form-check form-switch d-inline-block">
                            <input class="form-check-input chk-update-price" type="checkbox" name="items[${rowIndex}][updateprice]" value="1">
                        </div>
                    </td>
                    
                    <td>
                        <button type="button" class="btn btn-danger btn-sm removeRow">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>`;

                        $('#bpbTable tbody').append(row);
                        rowIndex++;
                    });

                    calculateLineTotals(); // Hitung ulang total setelah semua row masuk

                    // Fokuskan ke Qty baris pertama agar user bisa langsung input
                    focusRow($('#bpbTable tbody tr:first'));
                });
            });

            function calculateLineTotals() {
                document.querySelectorAll('.bpb-line').forEach(row => {
                    let qty = parseFloat(row.querySelector('.bpb-qty-input')?.value) || 0;
                    let price = parseFloat(row.querySelector('.bpb-price-input')?.value) || 0;
                    let discValue = parseFloat(row.querySelector('.bpb-discvalue-input')?.value) || 0;
                    let discType = row.querySelector('.bpb-disctype-input')?.value || 'amount';

                    let lineTotal = qty * price;
                    let discountCalculated = 0;

                    if (discType === 'percent') {
                        discountCalculated = lineTotal * (discValue / 100);
                    } else {
                        discountCalculated = discValue;
                    }

                    let subtotal = lineTotal - discountCalculated;

                    // Tampilkan ke input read-only dengan format ribuan
                    row.querySelector('.bpb-subtotal-display').value = new Intl.NumberFormat('id-ID').format(subtotal);
                });
            }

            // Tambahkan event listener untuk update otomatis
            document.addEventListener('input', function(e) {
                if (e.target.matches('.bpb-qty-input, .bpb-price-input, .bpb-discvalue-input, .bpb-disctype-input')) {
                    calculateLineTotals();
                }
            });
        </script>
        <script>
            function calculateBpbSummary() {
                let subtotal = 0;

                // 1. Hitung Total per Baris (Line Total)
                $('#bpbTableBody tr').each(function() {
                    const $row = $(this);
                    const qty = parseFloat($row.find('.bpb-qty-input').val()) || 0;
                    const price = parseFloat($row.find('.bpb-price-input').val()) || 0;
                    const discType = $row.find('.bpb-disctype-input').val();
                    const discVal = parseFloat($row.find('.bpb-discvalue-input').val()) || 0;

                    const lineGross = qty * price;
                    let lineDisc = 0;

                    if (discType === 'percent') {
                        lineDisc = lineGross * (discVal / 100);
                    } else {
                        lineDisc = discVal;
                    }

                    const lineTotal = lineGross - lineDisc;
                    subtotal += lineTotal;

                    // Opsional: Jika Anda punya kolom "Total" di setiap baris untuk display:
                    // $row.find('.line-total-display').text(lineTotal.toLocaleString('id-ID'));
                });

                // 2. Ambil Input Diskon Global & PPN (dari bagian header/footer form)
                const globalDiscType = $('#po_disctype').val(); // Contoh id: disctype
                const globalDiscVal = parseFloat($('#po_discvalue').val()) || 0;
                const ppnRate = parseFloat($('#ppnrate').val()) || 0;
                const ppnType = $('#ppntype').val(); // 'include', 'exclude', atau 'non'

                // 3. Hitung Diskon Global
                let totalDiscAmt = 0;
                if (globalDiscType === 'percent') {
                    totalDiscAmt = subtotal * (globalDiscVal / 100);
                } else {
                    totalDiscAmt = globalDiscVal;
                }

                const dpp = subtotal - totalDiscAmt;

                // 4. Hitung PPN
                let ppnAmt = 0;
                if (ppnType === 'include') {
                    ppnAmt = dpp * (ppnRate / 100);
                }

                const grandTotal = dpp + ppnAmt;

                // 5. Update Tampilan Label (Sesuai ID yang Anda berikan sebelumnya)
                $('#subtotal').text(subtotal.toLocaleString('id-ID'));
                $('#disc_global').text(totalDiscAmt.toLocaleString('id-ID'));
                $('#ppnTtl').text(ppnAmt.toLocaleString('id-ID'));
                $('#grand_total').text(grandTotal.toLocaleString('id-ID'));
            }

            $(document).ready(function() {
                // Listener untuk perubahan di dalam baris tabel (Qty, Harga, Diskon Line)
                $(document).on('input change',
                    '.bpb-qty-input, .bpb-price-input, .bpb-disctype-input, .bpb-discvalue-input',
                    function() {
                        // Validasi max qty jika diperlukan
                        const $input = $(this);
                        // if ($input.hasClass('bpb-qty-input')) {
                        //     const max = parseFloat($input.attr('max'));
                        //     if ($input.val() > max) {
                        //         alert('Qty tidak boleh melebihi sisa PO!');
                        //         $input.val(max);
                        //     }
                        // }
                        calculateBpbSummary();
                    });

                // Listener untuk perubahan di Master Diskon & PPN
                $(document).on('input change', '#po_disctype, #po_discvalue, #ppntype, #ppnrate', function() {
                    calculateBpbSummary();
                });

                // Jalankan kalkulasi pertama kali (untuk mode Edit)
                setTimeout(calculateBpbSummary, 500);
            });
        </script>
        <script>
            $(document).ready(function() {

                // Event listener untuk tombol hapus baris
                $(document).on('click', '.removeRow', function() {
                    let row = $(this).closest('tr');

                    // Opsional: Beri konfirmasi jika perlu
                    if (confirm('Hapus item ini dari daftar terima?')) {
                        row.fadeOut(300, function() {
                            $(this).remove();
                            // WAJIB: Hitung ulang totalan setelah baris dihapus
                            calculateBpbSummary();
                        });
                    }
                });

                // Modifikasi listener input agar lebih responsif
                $(document).on('input change',
                    '.bpb-qty-input, .bpb-price-input, .bpb-disctype-input, .bpb-discvalue-input, #disctype, #discvalue, #ppntype, #ppnrate',
                    function() {
                        calculateAllLogic();
                    });

            });

            // Fungsi pembantu agar tidak duplikasi penulisan
            function calculateAllLogic() {
                // Validasi Qty Max jika diinput manual
                // $('.bpb-qty-input').each(function() {
                //     let max = parseFloat($(this).attr('max'));
                //     if (parseFloat($(this).val()) > max) {
                //         $(this).val(max);
                //     }
                // });

                calculateBpbSummary(); // Panggil fungsi kalkulasi yang sudah dibuat sebelumnya
            }
        </script>
        <script>
            function viewHistory(productId) {
                // 1. Tampilkan modal
                $('#historyModal').modal('show');

                // 2. Hancurkan DataTable lama jika sudah ada (mencegah error reinitialise)
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

                    // 4. Inisialisasi DataTables jika ada data
                    if (res.length > 0) {
                        $('#tablePriceHistory').DataTable({
                            searching: true, // User bisa cari supplier/tanggal tertentu
                            info: false, // Mematikan info "Showing x of y"
                            scrollY: "400px", // Beri scroll vertikal jika data sangat banyak
                            scrollCollapse: true,
                            order: [
                                [0, 'desc']
                            ] // Urutkan berdasarkan tanggal terbaru
                        });
                    }
                });
            }

            function handleUpdatePriceCheckbox(chk, productId, measurementId) {
                if ($(chk).is(':checked')) {
                    let row = $(chk).closest('tr');
                    // Ambil harga dari input bpb-price-input (yang sebelumnya kita set readonly)
                    let bpbPrice = parseFloat(row.find('.bpb-price-input').val()) || 0;

                    if (bpbPrice <= 0) {
                        Swal.fire('Error', 'Harga beli belum tersedia.', 'error');
                        $(chk).prop('checked', false);
                        return;
                    }

                    openUpdatePriceModal(productId, bpbPrice, measurementId);
                }
            }

            function openUpdatePriceModal(productId, bpbPrice, selectedMeasurementId) {
                $('#priceUpdateModal').modal('show');
                $('#priceUpdateContent').html('<tr><td colspan="7" class="text-center">Loading...</td></tr>');

                $.get(`/BpbMstr/getMeasurementPrices/${productId}`, function(res) {
                    // 1. Cari nilai konversi dari unit yang dipilih di baris BPB

                    let currentUnit = res.measurements.find(m => m.measurement_id == selectedMeasurementId);

                    let conversionInBpb = currentUnit ? currentUnit.conversion : 1;

                    // 2. Hitung Harga Dasar (Harga per 1 Tablet/satuan terkecil)
                    let basePrice = bpbPrice / conversionInBpb;
                    // console.log("baseprice:", basePrice);

                    // 3. SORTING: Dari Konversi Terbesar ke Terkecil (Box -> Strip -> Tablet)
                    // b.conversion (40) - a.conversion (1) = Positif (b pindah ke atas)
                    let sortedMeasurements = res.measurements.sort((a, b) => b.conversion - a.conversion);

                    let html = '';
                    sortedMeasurements.forEach((m, index) => {
                        // 4. HITUNG ULANG: Harga per baris = Base Price x Konversi baris tersebut
                        let estBuyPriceNew = basePrice * m.conversion;
                        let margin = estBuyPriceNew * (res.product.margin / 100);
                        // console.log("margin: ", margin);
                        html += `
                <tr>
                    <td class="text-center">${index === 0 ? res.product.code : ''}</td>
                    <td>${index === 0 ? res.product.name : ''}</td>
                    <td class="text-center"><b>${m.unit_name}</b></td>
                    <td class="text-end text-muted">--</td>
                    <td class="text-end text-danger fw-bold">
                        ${new Intl.NumberFormat('id-ID').format(estBuyPriceNew)}
                    </td>
                    <td class="text-end">
                        ${new Intl.NumberFormat('id-ID').format(m.old_sell_price || 0)}
                    </td>
                    <td>
                        <input type="number" class="form-control text-end new-sell-price" 
                               data-pmid="${m.pm_id}" 
                               value="${estBuyPriceNew + margin || 0}">
                    </td>
                </tr>`;
                    });
                    $('#priceUpdateContent').html(html);
                });
            }
        </script>
        <script>
            // Ganti tag <button> simpan di HTML kamu menjadi:
            // <button type="button" onclick="confirmBpb()" class="btn btn-success">Simpan BPB</button>

            function confirmBpb() {
                Swal.fire({
                    title: 'Simpan Penerimaan?',
                    text: "Stok akan bertambah otomatis ke gudang yang dipilih.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    confirmButtonText: 'Ya, Terima Barang',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tampilkan loading
                        Swal.fire({
                            title: 'Sedang Memproses...',
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        // Submit form
                        $('form').submit();
                    }
                });
            }
        </script>
        <script>
            // Fungsi general untuk menambah baris ke tabel
            function addRowToTable(data) {
                let row = `
    <tr class="bpb-line">
        <td data-label="Produk">
            <input type="hidden" name="items[${rowIndex}][bpb_det_id]" value="${data.bpb_det_id || ''}">
            <input type="hidden" name="items[${rowIndex}][po_det_id]" value="${data.po_det_id || ''}">
            <input type="hidden" name="items[${rowIndex}][productid]" value="${data.productid}">
            <span class="fw-bold text-primary">${data.productname}</span>
            <br>
            <button type="button" class="btn btn-sm btn-link p-0 text-info" onclick="viewHistory(${data.productid})">
                <i class="bi bi-clock-history"></i> History
            </button>
        </td>
        <td data-label="Satuan (UM)">
            <input type="hidden" name="items[${rowIndex}][umid]" value="${data.umid}">
            <input type="hidden" name="items[${rowIndex}][umconv]" value="${data.umconv}">
            <span class="badge bg-light-secondary text-dark">${data.umname}</span>
        </td>
        <td data-label="Qty">
            <input type="number" name="items[${rowIndex}][qty]" class="form-control bpb-qty-input" value="${data.qty || 0}">
        </td>
        <td data-label="Harga">
            <input type="number" name="items[${rowIndex}][price]" class="form-control bpb-price-input" value="${data.price || 0}">
        </td>
        <td data-label="Diskon">
            <div class="input-group">
                <select name="items[${rowIndex}][disctype]" class="form-select bpb-disctype-input">
                    <option value="amount" ${data.disctype == 'amount' ? 'selected' : ''}>Rp</option>
                    <option value="percent" ${data.disctype == 'percent' ? 'selected' : ''}>%</option>
                </select>
                <input type="number" name="items[${rowIndex}][discvalue]" class="form-control bpb-discvalue-input" value="${data.discvalue || 0}">
            </div>
        </td>
        <td data-label="Batch & Exp">
            <input type="text" name="items[${rowIndex}][batch_no]" class="form-control mb-1" placeholder="No. Batch" value="${data.batch_no || ''}" required>
            <input type="date" name="items[${rowIndex}][expired_date]" class="form-control" value="${data.expired_date || ''}" required>
        </td>
       
        <td data-label="Subtotal">
            <input type="text" class="form-control bg-light bpb-subtotal-display" readonly value="0">
        </td>
         <td data-label="Update Price" class="text-md-center">
            <input type="hidden" name="items[${rowIndex}][margin]" value="${data.margin}">
            <div class="form-check form-switch d-inline-block">
                <input class="form-check-input chk-update-price" 
                       type="checkbox" 
                       name="items[${rowIndex}][updateprice]" 
                       value="1"
                       onclick="handleUpdatePriceCheckbox(this, '${data.productid}', '${data.umid}')">
                <label class="d-md-none">Update Harga Jual?</label>
            </div>
        </td>
         <td>
            <button type="button" class="btn btn-danger btn-sm removeRow w-100">
                <i class="bi bi-trash"></i> <span class="d-md-none">Hapus Barang</span>
            </button>
        </td>
    </tr>`;

                $('#bpbTable tbody').append(row);
                rowIndex++;
                calculateLineTotals();
            }

            // Logic untuk Tombol Tambah Manual
            $('#btnAddManual').on('click', function() {
                let selected = $('#manualProductSelect option:selected');
                if (!selected.val()) return;

                addRowToTable({
                    productid: selected.val(),
                    productname: selected.data('name'),
                    umid: selected.data('um'),
                    umconv: selected.data('umconv'),
                    umname: selected.data('umname'),
                    margin: selected.data('margin'),
                    qty: 1,
                    price: 0
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                @if (isset($bpb))
                    // Loop detail dari server ke fungsi addRowToTable JavaScript
                    @foreach ($bpb->details as $index => $item)
                        addRowToTable({
                            bpb_det_id: "{{ $item->bpb_det_id }}",
                            po_det_id: "{{ $item->bpb_det_podetid }}",
                            productid: "{{ $item->bpb_det_productid }}",
                            productname: "{{ $item->product->name }}",
                            umname: "{{ $item->measurement->name }}",
                            umid: "{{ $item->measurement->id }}",
                            umconv: 0,
                            qty: "{{ $item->bpb_det_qty }}",
                            // Hitung sisa PO: Sisa saat ini + yang sudah diterima di BPB ini
                            po_det_qtyremain: {{ ($item->po_det->po_det_qtyremain ?? 0) + $item->bpb_det_qty }},
                            price: "{{ $item->bpb_det_price }}",
                            disctype: "{{ $item->bpb_det_disctype }}", // 'amount' atau 'percent'
                            discvalue: "{{ $item->bpb_det_discvalue }}",
                            batch_no: "{{ $item->batch->batch_mstr_no ?? '' }}",
                            expired_date: "{{ $item->bpb_det_expired }}",
                            updateprice: "{{ $item->bpb_det_updateprice }}"
                        }, {{ $index }});
                    @endforeach

                    // Hitung totalan summary setelah semua baris masuk
                    calculateBpbSummary();

                    // Kunci beberapa field yang tidak boleh diubah saat edit (opsional)
                    $('#suppid').prop('readonly', true);
                    // $('#poSelect').trigger('change');
                @endif
            });
        </script>
    @endpush
</x-app-layout>
