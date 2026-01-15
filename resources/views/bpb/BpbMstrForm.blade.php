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
                <form action="{{ route('BpbMstr.store') }}" method="POST">
                    @csrf

                    <div class="row g-3 mb-3">

                        <div class="col-md-5">
                            <label>PO</label>
                            <select name="poId" class="form-control select2" id="poSelect" required>
                                <option value="">-- Pilih PO --</option>
                                @foreach ($pos as $po)
                                    <option value="{{ $po->po_mstr_id }}">{{ $po->po_mstr_nbr }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Gudang</label>
                            <select name="loc_id" class="form-control select2" required>
                                @foreach ($locs as $loc)
                                    <option value="{{ $loc->loc_mstr_id }}">{{ $loc->loc_mstr_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Tanggal BPB</label>
                            <input type="date" name="bpb_date" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label>No Faktur</label>
                            <input type="text" name="nofaktur" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label>No SJ</label>
                            <input type="text" name="nosj" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label>Catatan</label>
                            <input type="text" name="note" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label>Supplier</label>
                            <input type="hidden" name="suppid" id="suppid" class="form-control" readonly>
                            <input type="text" id="suppname" class="form-control" readonly>
                        </div>

                        <div class="col-md-3">
                            <label>Jenis Pembayaran</label>
                            <select name="payment_type" id="payment_type" class="form-control" required>
                                <option value="cash">Tunai</option>
                                <option value="credit">Hutang</option>
                            </select>
                        </div>

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
                            <select name="ppntype" id="ppntype" class="form-control">
                                <option value="none">None</option>
                                <option value="include">Active</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>PPN Rate (%)</label>
                            <input type="number" name="ppnrate" id="ppnrate" class="form-control">
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered" style="overflow-y:auto;" id="bpbTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th style="width: 8%">UM</th>
                                        <th style="width: 12%">Qty</th>
                                        <th style="width: 12%">Harga</th>
                                        <th style="width: 12%">Disc</th>
                                        <th style="width: 16%">Batch</th>
                                        <th style="width: 6%">Expired</th>
                                        <th style="width: 5%">Update Price</th>
                                        <th style="width: 5%"></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-3 text-end">
                        <button class="btn btn-success">Simpan BPB</button>
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
                        <i class="bi bi-clock-history"></i> 20 Pembelian Terakhir
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Tgl. Terima</th>
                                    <th>Supplier</th>
                                    <th>Qty</th>
                                    <th class="text-end">Harga Beli</th>
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
            let rowIndex = 0;

            $('#poSelect').on('change', function() {
                let poId = $(this).val();
                if (!poId) return;

                $('#bpbTable tbody').empty();
                rowIndex = 0;

                $.get(`/BpbMstr/getPoItems/${poId}`, function(items) {
                    items.forEach(item => {
                        // 1. Logika Perbandingan Harga (Letakkan ini sebelum merender row)
                        const bpbPrice = parseFloat(item.po_det_price) || 0;
                        const lastBuyPrice = item.pm ? parseFloat(item.pm.last_buy_price) : 0;
                        // console.log(lastBuyPrice);
                        // Penanda jika harga baru lebih mahal dari HPP Average
                        const isPriceUp = (bpbPrice > lastBuyPrice && lastBuyPrice > 0);

                        // Style untuk baris dan teks
                        const rowClass = isPriceUp ? 'table-warning' :
                            ''; // Memberi warna kuning pada baris
                        const textPriceStyle = isPriceUp ?
                            'style="color: #dc3545; font-weight: bold;"' : '';

                        // 2. Render Row
                        let row = `
    <tr class="bpb-line ${rowClass}">
        <td data-label="Produk">
            <input type="hidden" name="items[${rowIndex}][po_det_id]" value="${item.po_det_id}">
            <input type="hidden" name="items[${rowIndex}][productid]" value="${item.po_det_productid}">
            <span class="fw-bold text-primary">${item.product.name}</span>
            <br>
            <button type="button" class="btn btn-sm btn-link p-0 text-info" onclick="viewHistory(${item.po_det_productid})">
                <i class="bi bi-clock-history"></i> History Harga
            </button>
            ${isPriceUp ? `<div class="text-xs text-danger mt-1"><i class="bi bi-exclamation-triangle-fill"></i> Harga naik dari Rp ${new Intl.NumberFormat('id-ID').format(lastBuyPrice)}</div>` : ''}
        </td>

        <td data-label="Satuan (UM)">
            <input type="hidden" name="items[${rowIndex}][umid]" value="${item.po_det_um}">
            <input type="hidden" name="items[${rowIndex}][umconv]" value="${item.po_det_umconv}">
            <span class="badge bg-light-secondary text-dark">${item.um.name}</span>
        </td>

        <td data-label="Qty Terima">
            <input type="number" name="items[${rowIndex}][qty]" class="form-control" 
                   value="${item.po_det_qtyremain}" max="${item.po_det_qtyremain}">
            <small class="text-muted text-xs">Sisa PO: ${item.po_det_qtyremain}</small>
        </td>

        <td data-label="Harga Beli">
            <div class="d-flex align-items-center">
                <input type="number" name="items[${rowIndex}][price]" class="form-control bg-light bpb-price-input" 
                       value="${item.po_det_price}" ${textPriceStyle}>
                ${isPriceUp ? '<i class="fas fa-arrow-up text-danger ms-2" title="Harga naik!"></i>' : ''}
            </div>
        </td>

        <td data-label="Disc Amt">
            <div class="d-flex align-items-center">
                <input type="number" name="items[${rowIndex}][discamt]" class="form-control bg-light bpb-discamt-input" 
                       value="${item.po_det_discamt}">
            </div>
        </td>

        <td data-label="No. Batch">
            <input type="text" name="items[${rowIndex}][batch_no]" class="form-control" 
                   placeholder="No. Batch" required>
        </td>

        <td data-label="Tgl Expired">
            <input type="date" name="items[${rowIndex}][expired_date]" class="form-control" required>
        </td>

        <td data-label="Update Price" class="text-md-center">
            <input type="hidden" name="items[${rowIndex}][margin]" value="${item.product.margin}">
            <div class="form-check form-switch d-inline-block">
                <input class="form-check-input chk-update-price" 
                       type="checkbox" 
                       name="items[${rowIndex}][updateprice]" 
                       value="1"
                       onclick="handleUpdatePriceCheckbox(this, '${item.po_det_productid}', '${item.po_det_um}')">
                <label class="d-md-none">Update Harga Jual?</label>
            </div>
        </td>
    </td>
    
    <td>
        <button type="button" class="btn btn-danger btn-sm removeRow w-100">
            <i class="bi bi-trash"></i> <span class="d-md-none">Hapus Barang</span>
        </button>
    </td>
</tr>`;

                        $('#bpbTable tbody').append(row);
                        rowIndex++;
                    });
                });
            });
        </script>
        <script>
            function viewHistory(productId) {
                // Tampilkan modal loading
                $('#historyModal').modal('show');
                $('#historyContent').html('<tr><td colspan="4" class="text-center">Loading...</td></tr>');

                $.get(`/BpbMstr/getPriceHistory/${productId}`, function(res) {
                    let html = '';
                    res.forEach(h => {
                        html += `
                <tr>
                    <td>${h.bpb_date}</td>
                    <td>${h.supplier_name}</td>
                    <td>${h.qty} ${h.um}</td>
                    <td class="text-end">${new Intl.NumberFormat('id-ID').format(h.price)}</td>
                </tr>`;
                    });
                    $('#historyContent').html(html ||
                        '<tr><td colspan="4" class="text-center">Belum ada history</td></tr>');
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
    @endpush
</x-app-layout>
