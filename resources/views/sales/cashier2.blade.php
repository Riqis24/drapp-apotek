<x-guest-layout>
    <style>
        /* Mencegah scroll horizontal (space kosong kanan) */
        html,
        body {
            max-width: 100%;
            overflow-x: hidden !important;
            position: relative;
        }

        /* Pastikan wrapper utama selalu pas dengan layar */
        #pos-wrapper {
            width: 100% !important;
            overflow-x: hidden !important;
        }

        /* Row Bootstrap sering punya margin negatif -15px, ini penyebab meluber */
        .row {
            margin-right: 0 !important;
            margin-left: 0 !important;
        }

        /* Memperbaiki posisi SweetAlert agar tidak menggeser layout body */
        body.swal2-shown {
            padding-right: 0 !important;
            overflow-x: hidden !important;
        }
    </style>
    <style>
        @media (max-width: 767.98px) {

            /* Sembunyikan Header Tabel Asli */
            .responsive-cart-table thead {
                display: none;
            }

            .responsive-cart-table,
            .responsive-cart-table tbody,
            .responsive-cart-table tr,
            .responsive-cart-table td {
                display: block;
                width: 100%;
            }

            .responsive-cart-table tr {
                margin-bottom: 15px;
                border: 1px solid #eee;
                border-radius: 8px;
                padding: 10px;
                background: #fff;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            }

            .responsive-cart-table td {
                text-align: right;
                padding: 8px 10px !important;
                border: none !important;
                position: relative;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            /* Munculkan label kolom di sisi kiri */
            .responsive-cart-table td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #666;
                font-size: 0.85rem;
                text-align: left;
            }

            /* Penyesuaian khusus kolom produk agar lebih menonjol */
            .responsive-cart-table td:first-child {
                background-color: #f8f9fa;
                border-radius: 5px;
                justify-content: center;
                font-weight: bold;
                color: #333;
                margin-bottom: 5px;
            }

            .responsive-cart-table td:first-child::before {
                content: "";
                /* Produk tidak perlu label "Produk:" */
            }

            /* Tombol hapus diletakkan di posisi strategis */
            .responsive-cart-table td:last-child {
                justify-content: flex-end;
                border-top: 1px solid #eee !important;
                margin-top: 5px;
            }
        }

        .pos-header {
            background: #669aca;
            color: white;
            padding: 12px 12px;
            border-radius: 10px;
        }

        .pos-search input {
            height: 48px;
            font-size: 16px;
        }

        .pos-table th {
            background: #829ded;
            color: #ffffff;
            font-size: 13px;
            text-transform: uppercase;
        }

        .pos-table td {
            vertical-align: middle;
        }

        .qty-control {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 6px;
            overflow: hidden;
            width: 110px;
        }

        /* .qty-control button {
            width: 32px;
            border: none;
            background: #E9ECF7;
            font-weight: bold;
        } */

        .qty-control input {
            /* width: 46px; */
            border: none;
            text-align: center;
            font-weight: bold;
        }

        .price-input {
            text-align: right;
        }

        .summary-box {
            background: white;
            border-radius: 12px;
            padding: 16px;
        }

        .summary-total {
            font-size: 26px;
            font-weight: bold;
            color: #ffffff;
        }

        .suggest-box {
            position: absolute;
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, .15);
            width: 100%;
            z-index: 99;
        }

        .suggest-item {
            padding: 10px 14px;
            cursor: pointer;
        }

        .suggest-item:hover {
            background: #F0F2F8;
        }

        #paymentInput {
            border: 2px solid #0d6efd;
        }

        #paymentInput:focus {
            box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, .25);
        }

        #change {
            border: 2px solid #198754;
        }

        #change.negative {
            color: #dc3545;
        }

        #change.positive {
            color: #198754;
        }
    </style>
    <style>
        .fab-wrapper {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 9999;
        }

        /* FIX ICON CENTER */
        .fab-main,
        .fab-item {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            border: none;
            display: flex;
            align-items: center;
            /* center vertical */
            justify-content: center;
            /* center horizontal */
            color: #fff;
            background: #0d6efd;
            font-size: 22px;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(0, 0, 0, .25);
            transition: all .3s ease;
            text-decoration: none;
            padding: 0;
            /* 🔥 penting */
            line-height: 1;
            /* 🔥 penting */
        }

        /* optional hover */
        .fab-main:hover,
        .fab-item:hover {
            background: #0b5ed7;
            transform: scale(1.08);
        }

        /* FAB ITEM POSITION */
        .fab-item {
            position: absolute;
            left: 0;
            bottom: 0;
            opacity: 0;
            pointer-events: none;
            transform: translateY(0);
        }

        /* ACTIVE STATE */
        .fab-wrapper.active .fab-item {
            opacity: 1;
            pointer-events: auto;
        }

        /* 🔥 JARAK NAIK DIPERBESAR */
        .fab-wrapper.active .fab-item:nth-child(2) {
            transform: translateY(-80px);
        }

        .fab-wrapper.active .fab-item:nth-child(3) {
            transform: translateY(-160px);
        }

        .fab-wrapper.active .fab-item:nth-child(4) {
            transform: translateY(-240px);
        }
    </style>
    <style>
        /* Menghilangkan panah default select di beberapa browser jika perlu */
        .pos-header .form-select {
            background-position: right center;
            background-size: 12px;
        }

        /* Label kecil yang rapi */
        .pos-label-clean {
            display: block;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #95a5a6;
            margin-bottom: 0px;
            letter-spacing: 0.5px;
        }

        /* Menghilangkan border focus yang mengganggu visual */
        .pos-header .form-select:focus {
            box-shadow: none;
            color: #4257a3;
        }

        /* Avatar Soft Color */
        .bg-light-primary {
            background-color: #ebf3ff !important;
        }

        /* Card Rounded */
        .rounded-4 {
            border-radius: 1rem !important;
        }

        .border-end {
            border-right: 1px solid #f1f1f1 !important;
        }

        .pos-label-mini {
            display: block;
            font-size: 0.6rem;
            font-weight: 800;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: -2px;
            letter-spacing: 0.5px;
        }

        .pos-select-minimal {
            border: none !important;
            background-color: transparent !important;
            padding-left: 0 !important;
            padding-right: 20px !important;
            font-weight: 700;
            font-size: 0.95rem;
            color: #334155;
            box-shadow: none !important;
            cursor: pointer;
        }

        .pos-select-minimal:focus {
            color: #435ebe;
        }

        /* Memperhalus tampilan tombol di grup */
        .btn-group .btn-white {
            background: #fff;
            border-color: #f1f5f9;
        }

        .btn-group .btn-white:hover {
            background: #f8fafc;
        }

        /* Efek Shadow Card */
        .rounded-4 {
            border-radius: 12px !important;
        }

        .shadow-sm {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px 0 rgba(0, 0, 0, 0.06) !important;
        }

        /* Responsive Mobile */
        @media (max-width: 768px) {
            .col-md-3.border-end {
                border-end: none !important;
                border-bottom: 1px solid #f1f5f9;
            }

            .border-end {
                border-right: none !important;
            }
        }
    </style>
    <style>
        /* Memberikan garis pemisah antar kolom hanya di desktop */
        @media (min-width: 992px) {
            .border-start-md {
                border-left: 1px dashed #dee2e6 !important;
                padding-left: 20px;
            }
        }

        /* Mengatur ketebalan border input agar lebih "modern" */
        .border-2 {
            border: 2px solid #f1f5f9 !important;
        }

        .border-2:focus {
            border-color: #74bcff !important;
            background-color: #fff;
        }

        /* Label mini konsisten dengan header sebelumnya */
        .pos-label-mini {
            display: block;
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            color: #94a3b8;
            letter-spacing: 0.5px;
        }

        /* Menyesuaikan tampilan select agar teks di tengah secara vertikal */
        #itemInput+.select2-container .select2-selection--single {
            height: 45px !important;
            border-radius: 10px !important;
            border: 2px solid #f1f5f9 !important;
            display: flex;
            align-items: center;
        }
    </style>
    <style>
        /* Menghaluskan input number (menghapus spinner default browser jika mau) */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Efek hover pada tombol pay */
        #button_pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.4) !important;
            transition: all 0.2s ease;
        }
    </style>
    <style>
        /* Titik Indikator Status */
        .status-indicator-dot {
            width: 8px;
            height: 8px;
            background-color: #2ecc71;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(46, 204, 113, 0.7);
            animation: pulse-green 2s infinite;
        }

        @keyframes pulse-green {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(46, 204, 113, 0.7);
            }

            70% {
                transform: scale(1);
                box-shadow: 0 0 0 6px rgba(46, 204, 113, 0);
            }

            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(46, 204, 113, 0);
            }
        }

        /* Penyesuaian teks session */
        #active_session_name {
            font-family: 'Monaco', 'Consolas', monospace;
            background: #e0e7ff;
            padding: 1px 4px;
            border-radius: 4px;
        }
    </style>
    {{-- <div id="main"> --}}
    <div id="pos-wrapper" class="container-fluid p-0">
        <div class="container-fluid px-3 mt-3">
            <div class="pos-header mb-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-md-3 bg-light-primary p-3 d-flex align-items-center border-end">
                                <div class="col-md-3 bg-light-primary p-3 d-flex align-items-center border-end">
                                    <div class="avatar-custom-wrapper me-3">
                                        <img src="{{ asset('assets/images/cashier.png') }}" alt=""
                                            class="d-none d-md-block mx-auto" style="width: 100%;">
                                    </div>
                                </div>
                                <div class="overflow-hidden">
                                    <h6 class="mb-0 fw-bold text-dark text-truncate">
                                        {{ auth()->user()->user_mstr_name }}</h6>
                                    <span class="text-muted small text-uppercase fw-semibold"
                                        style="font-size: 0.6rem; letter-spacing: 0.5px;">
                                        {{ auth()->user()->getRoleNames()->first() ?? 'Cashier' }}
                                    </span>
                                    <br>
                                    <span class="text-muted text-uppercase fw-bold"
                                        style="font-size: 0.6rem; letter-spacing: 0.5px;">
                                        Session: <span class="text-primary"
                                            id="active_session_name">#{{ $session ?? 'close' }}</span>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6 p-2 d-flex align-items-center">
                                <div class="row w-100 g-3 px-2">
                                    <div class="col-6 border-end">
                                        <label class="pos-label-mini">Outlet</label>
                                        <select class="form-select pos-select-minimal" name="location" id="location">
                                            @foreach ($locations as $item)
                                                <option value="{{ $item->loc_mstr_id }}">{{ $item->loc_mstr_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="pos-label-mini">Pelanggan</label>
                                        <select class="form-select pos-select-minimal" name="customer_id"
                                            id="customer_id">
                                            @foreach ($customers as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}
                                                    ({{ $item->type }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 p-3 bg-white d-flex align-items-center justify-content-end gap-2">
                                <div class="text-end me-3 d-none d-lg-block">
                                    <small class="text-muted d-block fw-bold" style="font-size: 0.6rem;">SESSION
                                        TOTAL</small>
                                    <h5 class="mb-0 fw-bold text-primary">Rp <span class="render-grand-total"
                                            id="grandTotal2">0</span>
                                    </h5>
                                </div>
                                <div class="btn-group shadow-sm">
                                    <button class="btn btn-white btn-sm border" data-bs-toggle="modal"
                                        data-bs-target="#openCashierModal" title="Buka Kasir">
                                        <i class="bi bi-door-open text-success"></i>
                                    </button>
                                    <button class="btn btn-white btn-sm border" id="closeCashierBtn"
                                        title="Tutup Kasir">
                                        <i class="bi bi-door-closed text-danger"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card card-body">
                <div class="card border-0 shadow-sm rounded-4 mb-3">
                    <div class="row g-3">
                        <div class="col-lg-9">
                            <div class="card border-0 shadow-sm rounded-4 mb-3">
                                <div class="card-body p-3">
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-9">
                                            <div class="form-group mb-0">
                                                <label class="pos-label-mini mb-1">Cari Produk (F2)</label>
                                                <select id="itemInput" class="form-select border-2 shadow-none"
                                                    style="border-radius: 10px; height: 45px;">
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="d-none d-md-block pos-label-mini mb-1">&nbsp;</label>
                                            <button type="button"
                                                class="btn shadow-sm d-flex align-items-center justify-content-center"
                                                id="btn-buka-resep"
                                                style="background-color: #74bcff; border-radius: 10px; height: 45px; width:100%"
                                                data-bs-toggle="modal" data-bs-target="#modalRacik">
                                                <i class="bi bi-capsule-pill me-2 text-white"></i>
                                                <span class="text-white fw-bold small">Racik (F3)</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="row g-3 pt-3 border-top">
                                        <div class="col-md-6">
                                            <label class="pos-label-mini mb-1">Diskon Global</label>
                                            <div class="input-group">
                                                <select id="disc_global_type" class="form-select fw-bold border-2"
                                                    style="max-width: 80px; border-radius: 10px 0 0 10px;">
                                                    <option value="amount">Rp</option>
                                                    <option value="percent">%</option>
                                                </select>
                                                <input type="number" id="disc_global_value"
                                                    class="form-control text-end fw-bold border-2"
                                                    style="border-radius: 0 10px 10px 0;" value="0">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="pos-label-mini mb-1">Pajak (PPN)</label>
                                            <select id="ppn_type" class="form-select fw-bold border-2"
                                                style="border-radius: 10px; height: 40px;">
                                                <option value="none">Non PPN</option>
                                                <option value="include">PPN 11%</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm rounded-4">
                                <div class="card-body">
                                    <div class="card shadow-sm border-0">
                                        <div class="card-body p-0">
                                            <div class="table-responsive-sm">
                                                <table class="table pos-table mb-0 responsive-cart-table"
                                                    id="cartTable">
                                                    <thead>
                                                        <tr>
                                                            <th>Produk</th>
                                                            <th width="80">Qty</th>
                                                            <th width="160">Satuan</th>
                                                            <th width="120">Harga</th>
                                                            <th width="140">Diskon</th>
                                                            <th width="120">Subtotal</th>
                                                            <th width="40"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="cartBody">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="sticky-top" style="top: 20px;">
                                <div class="card border-0 shadow rounded-4 overflow-hidden">
                                    <div class="bg-light p-3 border-bottom">
                                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-receipt me-2"></i>Ringkasan
                                            Pesanan</h6>
                                    </div>

                                    <div class="card-body p-3">
                                        <div class="billing-details mb-3">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-muted small">Subtotal</span>
                                                <strong id="subtotal" class="text-dark">Rp 0</strong>
                                            </div>
                                            <div class="d-flex justify-content-between text-warning mb-1">
                                                <span class="small">Diskon Global</span>
                                                <strong id="discGlobal">Rp 0</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-muted small">PPN 11%</span>
                                                <strong id="ppnVal" class="text-dark">Rp 0</strong>
                                            </div>
                                        </div>

                                        <div class="bg-primary text-white rounded-3 p-3 mb-4 shadow-sm">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="small fw-bold opacity-75">TOTAL</span>
                                                <span id="grandTotal" class="fs-4 fw-bold">Rp 0</span>
                                            </div>
                                        </div>

                                        <div class="mb-3 pt-2 border-top">
                                            <label class="pos-label-mini mb-2">Jenis Transaksi</label>
                                            <select class="form-select border-2 fw-bold" name="payment_type"
                                                id="payment_type"
                                                style="border-radius: 10px; height: 45px; background-color: #f8fafc;"
                                                onchange="togglePaymentMethod(this.value)">
                                                <option value="cash">💰 TUNAI (LUNAS)</option>
                                                <option value="credit">📝 PIUTANG (TEMPO)</option>
                                            </select>
                                        </div>

                                        <div id="payment_method_wrapper" class="mb-3">
                                            <label class="pos-label-mini mb-2">Metode Pembayaran</label>
                                            <select class="form-select border-2 fw-bold" name="payment_method"
                                                id="payment_method"
                                                style="border-radius: 10px; height: 45px; border-color: #e2e8f0;">
                                                <option value="cash">💵 Cash / Tunai</option>
                                                <option value="transfer">🏦 Transfer Bank</option>
                                                <option value="qris">📱 QRIS</option>
                                                <option value="credit">💳 Kartu Debit/Kredit</option>
                                            </select>
                                        </div>

                                        <div class="mb-3" id="payment_input_wrapper">
                                            <label class="pos-label-mini mb-1">Uang Diterima (F8)</label>
                                            <div class="input-group">
                                                <span
                                                    class="input-group-text bg-light border-2 border-end-0 text-muted fw-bold"
                                                    style="border-radius: 12px 0 0 12px;">Rp</span>
                                                <input type="number" id="paymentInput"
                                                    class="form-control form-control-lg text-end fw-bold border-2 border-start-0 shadow-none"
                                                    placeholder="0" min="0" inputmode="numeric"
                                                    style="border-radius: 0 12px 12px 0; font-size: 1.6rem; color: #435ebe; height: 60px;">
                                            </div>
                                        </div>

                                        <div class="mb-4 p-3 rounded-3"
                                            style="background-color: #f8fafc; border: 1px dashed #cbd5e1;">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="pos-label-mini mb-0">Kembalian</span>
                                                <input type="number" id="change"
                                                    class="form-control border-0 bg-transparent p-0 text-end text-success fw-bold fs-5"
                                                    value="0" readonly>
                                            </div>
                                        </div>

                                        <div class="d-grid gap-2">
                                            <button class="btn btn-success btn-lg fw-bold py-3 shadow-sm mb-1"
                                                id="button_pay"
                                                style="border-radius: 12px; background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); border: none;">
                                                <i class="bi bi-printer-fill me-2"></i> BAYAR SEKARANG <br>
                                                <small class="fw-normal opacity-75" style="font-size: 0.7rem;">(ALT +
                                                    P)</small>
                                            </button>

                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <button class="btn btn-outline-secondary w-100 py-2 fw-semibold"
                                                        id="button_hold" style="border-radius: 10px;">
                                                        <i class="bi bi-pause-fill"></i> Hold (H)
                                                    </button>
                                                </div>
                                                <div class="col-6">
                                                    <button class="btn btn-outline-info w-100 py-2 fw-semibold"
                                                        id="button_hold_list" style="border-radius: 10px;">
                                                        <i class="bi bi-list-ul"></i> List
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <template id="rowTemplate">
        <tr class="align-middle">
            <td data-label="Produk">
                <input type="hidden" id="hold_id">
                <input type="hidden" class="form-control form-control-sm product_id">
                <div class="item-name-container">
                    <strong class="item-name text-wrap"></strong>
                </div>
            </td>

            <td data-label="Qty">
                <input type="number" class="form-control form-control-sm qty text-center mx-md-auto" value="1"
                    min="1" step="any" style="max-width: 80px;">
            </td>

            {{-- <td data-label="Satuan">
                <select name="items[${rowIndex}][measurement_id]"
                    class="form-select form-select-sm select-measurement" required>
                    <option value="">-- Pilih Satuan --</option>
                </select>
                <input type="hidden" name="items[${rowIndex}][umconv]" class="umconv" value="1">
            </td> --}}

            <td class="unit-area" data-label="Satuan">
            </td>

            @role(['Super Admin', 'Owner'])
                <td data-label="Harga">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text d-md-none">Rp</span>
                        <input type="number" class="form-control form-control-sm price-input price text-end"
                            value="0">
                    </div>
                </td>
            @endrole

            @role(['Admin', 'Kasir'])
                <td data-label="Harga">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text d-md-none">Rp</span>
                        <input type="number" readonly class="form-control form-control-sm price-input price text-end"
                            value="0">
                    </div>
                </td>
            @endrole

            <td data-label="Diskon">
                <div class="input-group input-group-sm">
                    <span class="input-group-text d-md-none">Rp</span>
                    <input type="number" class="form-control form-control-sm disc-item text-end" placeholder="0">
                </div>
            </td>

            <td data-label="Subtotal" class="text-end fw-bold">
                <span class="subtotal">0</span>
            </td>

            <td class="text-center">
                <button class="btn btn-sm btn-outline-danger removerow border-0">
                    <i class="bi bi-trash"></i> <span class="d-md-none">Hapus</span>
                </button>
            </td>
        </tr>
    </template>


    <div class="modal fade" id="holdListModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Transaksi HOLD</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No SO</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="holdListBody">
                            @foreach ($holds as $hold)
                                <tr>
                                    <td>{{ $hold->sales_mstr_nbr ? $hold->sales_mstr_nbr : '' }}</td>
                                    <td>{{ $hold->sales_mstr_grandtotal ? number_format($hold->sales_mstr_grandtotal, 0, ',', '.') : '' }}
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary"
                                            onclick="resumeHold({{ $hold->sales_mstr_id }})">Lanjutkan</button>
                                        <button class="btn btn-sm btn-danger"
                                            onclick="cancelHold({{ $hold->sales_mstr_id }})">Batal</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Buka Kasir --}}
    <form action="{{ route('CashierSession.open') }}" method="POST">
        @csrf
        <div class="modal fade" id="openCashierModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content p-3">
                    <h5>Buka Kasir</h5>
                    <div class="mb-2">
                        <label>Lokasi</label>
                        <select name="open_loc_id" id="open_loc_id" class="form-select">
                            @foreach ($locations as $item)
                                <option value="{{ $item->loc_mstr_id }}">{{ $item->loc_mstr_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Opening Amount</label>
                        <input type="number" name="opening_amount" id="open_amount" class="form-control"
                            placeholder="0">
                    </div>
                    <button type="submit" class="btn btn-primary w-100" id="openCashierBtn">Buka
                        Kasir</button>
                </div>
            </div>
        </div>
    </form>



    <div class="modal fade" id="modalRacik" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header text-white" style="background:#adc7fd">
                    <h5 class="modal-title">
                        <i class="bi bi-pill"></i> Buat Obat Racikan (Resep)
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <!-- Body -->
                <div class="modal-body">

                    <!-- Nama Racikan -->
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Nama Racikan</label>
                            <input type="text" id="namaRacikan" class="form-control"
                                placeholder="Cth: Puyer Batuk Pilek Anak">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">
                                Jumlah Hasil
                            </label>
                            <div class="input-group">
                                <input type="number" id="jumlahHasil" class="form-control text-end" value="10">

                                <select name="umRacik" id="umRacik" class="form-select" style="max-width: 150px;">
                                    <option value="">--satuan--</option>
                                    @foreach ($ums as $um)
                                        <option value="{{ $um->id }}">{{ $um->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Cari Bahan -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Cari Bahan Baku</label>
                        <select id="racikItemInput" class="form-select"></select>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Bahan</th>
                                    <th width="120">Satuan</th>
                                    <th width="80">Stock</th>
                                    <th width="80">Qty</th>
                                    <th width="140" class="text-end">Total Harga</th>
                                    <th width="50">#</th>
                                </tr>
                            </thead>
                            <tbody id="racikTableBody">
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4" id="racikEmpty">
                                        Belum ada bahan baku dipilih.<br>
                                        Gunakan pencarian di atas untuk menambahkan obat.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary -->
                    <div class="row mt-3">
                        <div class="col-md-8">
                            <div class="p-3 rounded" style="background:#fffbe6">
                                <div class="mb-2 d-flex justify-content-between align-items-center">
                                    <strong>Total Harga Bahan:</strong>
                                    <span id="totalHargaBahan" class="fw-bold">0</span>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Jasa Racik / Embalase</label>
                                    <input type="number" id="jasaRacik" class="form-control text-end"
                                        value="5000">
                                </div>

                                <div>
                                    <label class="form-label">Markup Tambahan</label>
                                    <input type="number" id="markupTambahan" class="form-control text-end"
                                        value="0">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 text-end">
                            <div class="fw-bold mb-2">Harga Jual Total</div>
                            <div id="hargaJualTotal" style="font-size:28px;color:#493df1">
                                Rp 0
                            </div>
                            <span id="hargaPerBungkus" class="text-muted">≈ 0 / bungkus</span>
                        </div>
                    </div>

                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button class="btn text-white" style="background:#5f92ff" id="btnMasukCartRacik">
                        <i class="bi bi-cart-plus"></i> Masuk Keranjang
                    </button>
                </div>

            </div>
        </div>
    </div>

    <div class="fab-wrapper">
        <button class="fab-main" id="fabToggle">
            <i class="bi bi-plus-lg"></i>
        </button>

        <a href="{{ route('SalesMstr.index') }}" class="fab-item" title="Sales">
            <i class="bi bi-cash-stack"></i>
        </a>

        <a href="{{ route('Stock.index') }}" class="fab-item" title="Stock">
            <i class="bi bi-box-seam"></i>
        </a>

        <a href="{{ route('CustMstr.index') }}" class="fab-item" title="Customer">
            <i class="bi bi-people"></i>
        </a>
    </div>


    @if ($dueAps->count() > 0)
        <div class="modal fade" id="apDueModal" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header bg-danger">
                        <h5 class="modal-title  text-white">
                            ⚠ Reminder Hutang Supplier
                        </h5>
                    </div>
                    <div class="modal-body">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Supplier</th>
                                    <th>No AP</th>
                                    <th>Due Date</th>
                                    <th class="text-end">Sisa</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dueAps as $ap)
                                    @php
                                        $days = now()->diffInDays($ap->ap_mstr_duedate, false);
                                    @endphp
                                    <tr>
                                        <td>{{ $ap->supplier->supp_mstr_name }}</td>
                                        <td>{{ $ap->ap_mstr_nbr }}</td>
                                        <td>{{ $ap->ap_mstr_duedate }}</td>
                                        <td class="text-end">
                                            {{ number_format($ap->ap_mstr_balance, 2) }}
                                        </td>
                                        <td>
                                            @if ($days < 0)
                                                <span class="badge bg-danger">Overdue</span>
                                            @elseif ($days == 0)
                                                <span class="badge bg-warning">Jatuh Tempo</span>
                                            @else
                                                <span class="badge bg-info">H-{{ $days }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                    <div class="modal-footer">
                        <a href="{{ route('ApMstr.AgingHutang') }}" class="btn btn-danger">
                            Lihat Aging Hutang
                        </a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif



    @push('scripts')
        <script src="{{ asset('assets/js/alert.js') }}"></script>
        <script>
            $(document).ready(function() {
                // Cek variabel dari Laravel
                const hasActiveSession = @json($session);

                if (!hasActiveSession) {
                    // Jika menggunakan Bootstrap 5
                    var myModal = new bootstrap.Modal(document.getElementById('openCashierModal'));
                    myModal.show();

                    // Fokuskan ke input opening amount
                    $('#openCashierModal').on('shown.bs.modal', function() {
                        $('#open_amount').focus();
                    });
                }
            });
        </script>
        <script>
            function togglePaymentMethod(status) {
                const methodWrapper = document.getElementById('payment_method_wrapper');
                const paymentInput = document.getElementById('paymentInput');
                const paymentInputWrapper = document.getElementById('payment_input_wrapper');

                if (status === 'credit') {
                    // Sembunyikan metode bayar & input uang
                    methodWrapper.style.display = 'none';
                    paymentInput.value = 0;
                    paymentInputWrapper.style.opacity = '0.5'; // Memberi kesan disabled
                    paymentInput.readOnly = true;
                } else {
                    // Tampilkan kembali
                    methodWrapper.style.display = 'block';
                    paymentInputWrapper.style.opacity = '1';
                    paymentInput.readOnly = false;
                }
            }

            // Jalankan saat halaman pertama kali dimuat untuk sinkronisasi
            document.addEventListener('DOMContentLoaded', function() {
                togglePaymentMethod(document.getElementById('payment_type').value);
            });
        </script>
        <script>
            document.addEventListener('keydown', function(e) {
                // F2 untuk buka modal resep
                if (e.key === 'F3') {
                    e.preventDefault(); // Mencegah fungsi bawaan browser (jika ada)
                    const btnResep = document.getElementById('btn-buka-resep'); // Sesuaikan ID tombolmu
                    if (btnResep) btnResep.click();
                }
            });
        </script>
        @if (session('swal'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: "{{ session('swal.icon') }}",
                        title: "{{ session('swal.title') }}",
                        text: "{{ session('swal.text') }}"
                    });
                });
            </script>
        @endif
        <script>
            function updateCartUI(item) {
                let row = `
        <tr>
            <td data-label="Produk"><strong>${item.name}</strong></td>
            <td data-label="Qty"><input type="number" class="form-control form-control-sm text-center" value="${item.qty}"></td>
            <td data-label="Satuan">${item.unit_name}</td>
            <td data-label="Harga">Rp ${formatNumber(item.price)}</td>
            <td data-label="Diskon">Rp ${formatNumber(item.discount)}</td>
            <td data-label="Subtotal" class="fw-bold">Rp ${formatNumber(item.subtotal)}</td>
            <td><button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button></td>
        </tr>
    `;
                $('#cartBody').append(row);
            }
        </script>
        <script>
            $('#customer_id').select2({
                placeholder: 'Cari Pelanggan',
                width: '100%',
                allowClear: true,
                theme: "bootstrap-5",
                minimumResultsForSearch: 0,
            });
            $('#location').select2({
                placeholder: 'Cari gudang',
                width: '100%',
                allowClear: true,
                theme: "bootstrap-5",
                minimumResultsForSearch: 0
            });

            $('#payment_type').select2({
                placeholder: 'Tipe Pembayaran',
                width: '100%',
                allowClear: true,
                theme: "bootstrap-5",
                minimumResultsForSearch: 0
            });
            $('#payment_method').select2({
                placeholder: 'Metode Pembayaran',
                width: '100%',
                theme: "bootstrap-5",
                allowClear: true,
                minimumResultsForSearch: 0
            });
        </script>
        <script>
            document.getElementById('fabToggle').addEventListener('click', function() {
                document.querySelector('.fab-wrapper').classList.toggle('active');
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('itemInput').focus();
            });
        </script>
        @if ($dueAps->count() > 0)
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    let modal = new bootstrap.Modal(
                        document.getElementById('apDueModal')
                    );
                    modal.show();
                });
            </script>
        @endif

        <script>
            const cartBody = document.getElementById('cartBody');

            const items = @json($items);

            function initItemSelect(selector, data, placeholder) {
                $(selector).empty().select2({
                    data: data,
                    placeholder: placeholder,
                    width: '100%',
                    allowClear: true,
                    minimumResultsForSearch: 0,
                    theme: 'bootstrap-5',
                    templateResult: formatObat, // Memanggil fungsi custom untuk list
                    // templateSelection: formatObatSelection, // Memanggil fungsi custom untuk hasil terpilih
                    escapeMarkup: function(m) {
                        return m;
                    } // PENTING: Agar HTML tidak dianggap teks biasa
                });

                // Fungsi untuk mendesain list dropdown
                function formatObat(item) {
                    if (item.loading) return item.text;
                    if (!item.id) return item.text; // Untuk placeholder seperti "Cari Obat..."

                    // Mengambil data dari object item (hasil response JSON dari backend)
                    const product = item.product || '-';
                    const measurement = item.measurement || '-';
                    const rak = item.rak || '-';
                    const price = parseFloat(item.price) || 0;
                    const batch = item.batch_number || '-';
                    const exp = item.batch_exp || '-';

                    // HTML Custom yang diselipkan ke dropdown
                    return `
        <div class="py-1">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="fw-bold text-dark" style="font-size: 1rem;">${product}</div>
                    <div class="text-muted small">
                        <i class="bi bi-tag"></i> ${measurement} 
                        <span class="mx-1">|</span> 
                        <i class="bi bi-geo-alt"></i> Rak: ${rak}
                    </div>
                </div>
                <div class="text-end">
                    <div class="text-primary fw-bold">Rp ${price.toLocaleString('id-ID')}</div>
                    <span class="badge bg-light text-dark border" style="font-size: 0.7rem;">Batch: ${batch}</span>
                </div>
            </div>
            <div class="mt-1" style="font-size: 0.75rem;">
                <span class="text-secondary italic">Expired: ${exp}</span>
            </div>
        </div>
    `;
                }

            }

            function filterItemsByLocation(locId) {
                return items.filter(item =>
                    item.loc_id === null || item.loc_id == locId
                );
            }

            function loadPosItems() {
                const locId = $('#location').val();
                if (!locId) return;

                const filtered = filterItemsByLocation(locId);

                initItemSelect(
                    '#itemInput',
                    filtered,
                    'Scan barcode / ketik nama barang'
                );
            }

            // first load
            loadPosItems();

            // on location change
            $('#location').on('change', loadPosItems);

            $('#modalRacik').on('shown.bs.modal', function() {
                const locId = $('#location').val();
                if (!locId) return;

                const filtered = filterItemsByLocation(locId);

                $('#racikItemInput')
                    .empty()
                    .select2({
                        data: [{
                            id: "",
                            text: ""
                        }].concat(filtered), // Tambahkan item kosong di awal
                        placeholder: 'Cari bahan baku obat',
                        width: '100%',
                        allowClear: true,
                        minimumResultsForSearch: 0,
                        dropdownParent: $('#modalRacik'),
                        theme: 'bootstrap-5',
                        templateResult: formatObat, // Pakai fungsi format yang tadi
                        escapeMarkup: function(m) {
                            return m;
                        }
                    })
                    .val(null) // Paksa null di awal
                    .trigger('change');


                function formatObat(item) {
                    if (item.loading) return item.text;
                    if (!item.id) return item.text; // Untuk placeholder seperti "Cari Obat..."

                    // Mengambil data dari object item (hasil response JSON dari backend)
                    const product = item.product || '-';
                    const measurement = item.measurement || '-';
                    const rak = item.rak || '-';
                    const price = parseFloat(item.price) || 0;
                    const batch = item.batch_number || '-';
                    const exp = item.batch_exp || '-';

                    // HTML Custom yang diselipkan ke dropdown
                    return `
        <div class="py-1">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="fw-bold text-dark" style="font-size: 1rem;">${product}</div>
                    <div class="text-muted small">
                        <i class="bi bi-tag"></i> ${measurement} 
                        <span class="mx-1">|</span> 
                        <i class="bi bi-geo-alt"></i> Rak: ${rak}
                    </div>
                </div>
                <div class="text-end">
                    <div class="text-primary fw-bold">Rp ${price.toLocaleString('id-ID')}</div>
                    <span class="badge bg-light text-dark border" style="font-size: 0.7rem;">Batch: ${batch}</span>
                </div>
            </div>
            <div class="mt-1" style="font-size: 0.75rem;">
                <span class="text-secondary italic">Expired: ${exp}</span>
            </div>
        </div>
    `;
                }

            });

            // POS
            $('#itemInput').on('select2:select', function(e) {
                const item = e.params.data;
                console.log(item);
                addItem(item);
                $(this).val(null).trigger('change');
            });

            // Racikan
            $('#racikItemInput').on('select2:select', function(e) {
                const item = e.params.data;
                addBahanRacik(item);
                $(this).val(null).trigger('change');
            });
        </script>
        {{-- add bahan racik --}}
        <script>
            function addBahanRacik(item) {

                // hilangkan empty row
                $('#racikEmpty').remove();

                const rowId = `racik-${item.id}`;

                // ❗ cegah item duplikat
                if ($(`#${rowId}`).length) {
                    const qtyInput = $(`#${rowId} .racik-qty`);
                    qtyInput.val(parseInt(qtyInput.val()) + 1).trigger('input');
                    return;
                }

                // if (window.APP_CONFIG.allow_negative == 0) {
                //     // Cek setiap item di keranjang
                //     // Pastikan qty dan stock di-convert ke angka agar perbandingan valid
                //     const stock = parseFloat(item.stock) || 0;
                //     const conversion = parseFloat(item.conversion) || 0;

                //     if (conversion > stock) {
                //         // Hitung selisihnya dulu
                //         const kurangnya = conversion - stock;

                //         swalError(
                //             "Stok Tidak Cukup",
                //             "Item berikut melebihi stok: " + (item.product || "Produk") +
                //             ". Stock tersisa " + stock +
                //             ". Kurang " + kurangnya // Menggunakan variabel yang sudah dihitung
                //         );
                //         return;
                //     }
                // };

                const row = `
        <tr id="${rowId}" data-product-id="${item.product_id}">
            <td>
                <div class="fw-bold">${item.text}</div>
                <small class="text-muted">${item.batch_number ?? '-'}</small>
            </td>

            <td>${item.measurement ?? '-'}</td>

            <td>
                ${item.stock}
            </td>

            <td>
                <input type="number"
                       class="form-control form-control-sm text-end racik-qty"
                       value="1"
                       min="1"
                       data-price="${item.price}" data-measurement="${item.measurement_id}">
            </td>

            <td class="text-end racik-subtotal">
                ${formatRupiah(item.price)}
            </td>

            <td class="text-center">
                <button class="btn btn-sm btn-danger racik-remove">
                    <i class="bi bi-x"></i>
                </button>
            </td>
        </tr>
    `;

                $('#racikTableBody').append(row);
                hitungTotalRacik();
            }

            $(document).on('input', '.racik-qty', function() {
                const qty = parseFloat($(this).val()) || 0;
                const price = parseFloat($(this).data('price')) || 0;

                const subtotal = qty * price;

                $(this)
                    .closest('tr')
                    .find('.racik-subtotal')
                    .text(formatRupiah(subtotal));

                hitungTotalRacik();
            });

            function hitungTotalRacik() {
                let total = 0;
                $('.racik-subtotal').each(function() {
                    // Ambil angka saja, hapus titik/simbol
                    const val = $(this).text().replace(/[^\d]/g, '');
                    total += parseInt(val) || 0;
                });

                $('#totalHargaBahan').text(formatRupiah(total));
                hitungHargaJual(); // Trigger hitung harga akhir
            }

            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID').format(angka);
            }

            function hitungHargaJual() {
                const totalBahan = parseInt($('#totalHargaBahan').text().replace(/[^\d]/g, '')) || 0;
                const jasa = parseInt($('#jasaRacik').val()) || 0;
                const markup = parseInt($('#markupTambahan').val()) || 0;
                const jumlah = parseInt($('#jumlahHasil').val()) || 1;

                const totalJual = totalBahan + jasa + markup;
                const perBungkus = Math.ceil(totalJual / (jumlah > 0 ? jumlah : 1));

                $('#hargaJualTotal').text('Rp ' + formatRupiah(totalJual));
                $('#hargaPerBungkus').text('≈ ' + formatRupiah(perBungkus) + ' / bungkus');
            }

            $(document).on('input', '#jasaRacik, #markupTambahan, #jumlahHasil', function() {
                hitungHargaJual();
            });

            function toggleBtnSimpanRacik() {
                const hasItem = $('#racikTableBody tr').length > 0;
                $('#btnSimpanRacik').prop('disabled', !hasItem);
            }

            function validasiRacik() {
                if ($('#racikTableBody tr').length === 0) {
                    alert('Bahan racikan belum ada');
                    return false;
                }
                if ($('#jumlahHasil').val() <= 0) {
                    alert('Jumlah hasil tidak valid');
                    return false;
                }
                return true;
            }
            $(document).on('click', '.racik-remove', function() {
                if (!confirm('Hapus bahan racikan ini?')) return;
                $(this).closest('tr').remove();

                // kalau sudah tidak ada item
                if ($('#racikTableBody tr').length === 0) {
                    $('#racikTableBody').html(`
            <tr class="text-center text-muted" id="racikEmpty">
                <td colspan="6" class="py-4">
                    Belum ada bahan baku dipilih.<br>
                    Gunakan pencarian di atas untuk menambahkan obat.
                </td>
            </tr>
        `);

                    // reset summary
                    $('#totalHargaBahan').text('0');
                    $('#hargaJualTotal').text('Rp 0');
                    $('#hargaPerBungkus').text('0');
                }

                hitungTotalRacik();
            });
        </script>
        <script>
            let racikanDetails = {};

            $('#btnMasukCartRacik').on('click', function() {

                if (!validasiRacik()) return;

                const itemRacik = convertRacikanToItem();

                addItem(itemRacik);

                // simpan detail racikan (nanti ke backend)
                simpanDetailRacikan(itemRacik.id);



                resetModalRacik();
                $('#modalRacik').modal('hide');
            });

            function resetModalRacik() {
                $('#namaRacikan').val('');
                $('#jumlahHasil').val(10);
                $('#racikTableBody').html(`
            <tr id="racikEmpty">
                <td colspan="6" class="text-center text-muted py-4">
                    Belum ada bahan baku dipilih.
                </td>
            </tr>
        `);
                $('#totalHargaBahan').text('0');
                hitungHargaJual();
            }

            function simpanDetailRacikan(racikId) {

                const details = [];

                $('#racikTableBody tr').each(function() {
                    const row = $(this);
                    const pId = row.data('product-id'); // LANGSUNG DAPET ANGKA ID DB

                    if (pId) {
                        details.push({
                            product_id: pId,
                            qty: parseFloat(row.find('.racik-qty').val()) || 0,
                            price: parseInt(row.find('.racik-qty').data('price')) || 0,
                            measurement_id: parseInt(row.find('.racik-qty').data('measurement')) || 0,
                        });
                    }
                });

                racikanDetails[racikId] = {
                    nama: $('#namaRacikan').val(),
                    jumlah_bungkus: parseInt($('#jumlahHasil').val()),
                    // measurement: parseInt($('#umRacik').val()),
                    jasa: parseInt($('#jasaRacik').val()),
                    markup: parseInt($('#markupTambahan').val()),
                    total: parseInt($('#hargaJualTotal').text().replace(/[^\d]/g, '')),
                    details: details
                };

                // console.log(racikanDetails);
            }

            const itemRacik = convertRacikanToItem();

            function convertRacikanToItem() {

                const nama = $('#namaRacikan').val() || 'Obat Racikan';
                // const um = $('#umracikan').val();
                // Ambil element select-nya
                let selectUm = $('#umRacik');

                // Ambil ID (Value)
                let umId = selectUm.val();

                // Ambil Nama (Teks yang muncul di layar)
                let umName = selectUm.find('option:selected').text();
                const qty = parseInt($('#jumlahHasil').val()) || 1;
                const hargaPerBungkus = parseInt(
                    $('#hargaPerBungkus').text().replace(/[^\d]/g, '')
                );

                return {
                    id: 'RACIK-' + Date.now(), // unik
                    type: 'racikan',

                    product: nama,
                    product_id: 0, // atau ID khusus racikan
                    measurement_id: umId,
                    measurement: umName,

                    price: hargaPerBungkus,
                    qty: qty,
                    disc: 0,
                    stock: 0,

                    batch_number: 'RACIK',
                    batch_exp: '-',
                    rak: 'Resep'
                };
            }
        </script>
        <script>
            const paymentInput = document.getElementById('paymentInput');
            const changeEl = document.getElementById('change');

            function updateChange(grandTotal) {
                // 1. Ambil nilai bayar
                const paid = parseFloat(paymentInput.value) || 0;

                // 2. Bulatkan grandTotal dan paid ke angka utuh terdekat
                // Ini penting agar selisih 0.1 atau 0.5 tidak menggagalkan perbandingan
                const roundedGrandTotal = Math.round(grandTotal);
                const roundedPaid = Math.round(paid);

                const change = roundedPaid - roundedGrandTotal;

                if (change >= 0) {
                    changeEl.classList.remove('negative');
                    changeEl.classList.add('positive');
                    // Gunakan nilai change yang sudah bulat untuk display
                    changeEl.value = rupiah(change);
                } else {
                    changeEl.classList.remove('positive');
                    changeEl.classList.add('negative');
                    changeEl.value = 'Rp 0';
                }
            }


            // Hook ke input bayar
            paymentInput.addEventListener('input', function() {
                const grand = window.currentGrandTotal || 0;
                updateChange(grand);
            });
        </script>
        <script>
            function addItem(item) {

                // 1. Definisikan rowId unik (Produk + Satuan + Harga)
                let rowId = `${item.product_id}-${item.measurement_id}-${item.price}`;

                // 2. Cari apakah sudah ada baris dengan data-rowid tersebut
                let existing = document.querySelector(`tr[data-rowid="${rowId}"]`);
                const qtyToAdd = parseFloat(item.qty) || 1;

                if (existing) {
                    // Jika ada, update qty saja
                    let qtyInput = existing.querySelector('.qty');
                    qtyInput.value = parseFloat(qtyInput.value) + qtyToAdd;
                    recalc();
                    return;
                }

                // console.log(item);


                const stock = parseFloat(item.stock) || 0;
                // Gunakan qty dikali konversi satuan (jika ada)
                const qtyBeli = (parseFloat(item.qty) || 0) * (parseFloat(item.conversion) || 1);

                if (item.type !== 'racikan') {
                    if (qtyBeli > stock) {
                        const kurangnya = qtyBeli - stock;
                        swalError(
                            "Stok Tidak Cukup",
                            "Item berikut melebihi stok: " + (item.product || "Produk") +
                            ". Stock tersisa " + stock +
                            ". Kurang " + kurangnya
                        );
                        return;
                    }
                }

                console.log('masuk additem atas sendiri');

                // 3. Jika tidak ada, buat baris baru dari template
                const tpl = document.getElementById('rowTemplate').content.cloneNode(true);
                const tr = tpl.querySelector('tr');
                const unitArea = tr.querySelector('.unit-area');

                // --- PENTING: Tambahkan data-rowid agar bisa dideteksi nantinya ---
                tr.setAttribute('data-rowid', rowId);
                // -----------------------------------------------------------------
                const idUnik = item.prescode || item.id_unik; // Ambil dari data resume
                tr.setAttribute('data-idunik', idUnik);

                tr.dataset.priceid = item.id || item.prescode;
                tr.dataset.type = item.type;

                tr.querySelector('.item-name').innerHTML = `
        <div class="fw-semibold text-dark">${item.product}</div>
        <div class="text-muted" style="font-size:10px">
            ${item.batch_number} (${item.batch_exp}) - ${item.rak}
        </div>
    `;

                if (item.type === 'racikan') {
                    // JIKA RACIKAN: Tampilkan Badge (Teks saja)
                    unitArea.innerHTML = `
            <input type="text" readonly name="items[${rowId}][measurement_id]" class="form-control form-control-sm " value="${item.measurement}">
            <input type="hidden" name="items[${rowId}][measurement_id]" class="measurement_id" value="${item.measurement_id}">
        `;
                } else {
                    console.log('masuk additem');

                    // JIKA REGULER: Tampilkan Select
                    unitArea.innerHTML = `
            <select name="items[${rowId}][measurement_id]" 
                    class="form-select form-select-sm select-measurement" required>
                <option value="">-- Pilih --</option>
            </select>
            <input type="hidden" name="items[${rowId}][umconv]" class="umconv" value="1">
        `;

                    // Panggil fungsi AJAX untuk isi option select-nya
                    const selectUM = unitArea.querySelector('.select-measurement');
                    fillMeasurementSelect(item.product_id, selectUM, item.measurement_id);
                }

                tr.querySelector('.qty').value = qtyToAdd;
                tr.querySelector('.price').value = item.price;
                tr.querySelector('.product_id').value = item.product_id;
                // tr.querySelector('.measurement_id').value = item.measurement_id;
                tr.querySelector('.disc-item').value = item.disc || 0;
                // tr.querySelector('.measurement').innerText = item.measurement;

                cartBody.appendChild(tr);
                recalc();
            }
        </script>
        <script>
            $(document).on('change', '.select-measurement', function() {
                let row = $(this).closest('tr'); // Ini adalah objek jQuery
                let selectedOption = $(this).find(':selected');

                let newPrice = parseFloat(selectedOption.data('price')) || 0;
                let conversion = parseFloat(selectedOption.data('conv')) || 1;

                // Gunakan .find() daripada .querySelector()
                row.find('.price').val(newPrice);
                row.find('.umconv').val(conversion);

                recalc();
            });

            // 1. Variabel Global untuk menyimpan data agar tidak load ulang terus menerus
            let measurementCache = {};

            function fillMeasurementSelect(productId, element, selectedId = null) {
                const $el = $(element);
                console.log('masuk umselect');
                // Jika data sudah ada di cache, langsung pakai (Tanpa delay AJAX)
                if (measurementCache[productId]) {
                    renderOptions($el, measurementCache[productId], selectedId);
                    return;
                }

                // Jika belum ada, baru panggil AJAX
                $.ajax({
                    url: `/product/${productId}/measurements`,
                    method: 'GET',
                    success: function(response) {
                        // Simpan ke cache
                        measurementCache[productId] = response;
                        renderOptions($el, response, selectedId);
                    }
                });
            }

            function renderOptions($el, data, selectedId) {
                let options = '';
                data.forEach(um => {
                    let selected = (selectedId == um.measurement_id) ? 'selected' : '';
                    // Simpan harga dasar atau harga per satuan di data attribute
                    options += `<option value="${um.measurement_id}" 
                            data-conv="${um.umconv}" 
                            data-price="${um.price}" 
                            ${selected}>
                        ${um.measurement_name}
                    </option>`;
                });
                $el.html(options);

                $el.select2({
                    placeholder: 'Cari Satuan',
                    width: '100%',
                    allowClear: true,
                    theme: "bootstrap-5",
                    minimumResultsForSearch: 0,
                    // Jika tabel ada di dalam modal, tambahkan: 
                    // dropdownParent: $el.parent() 
                });
                $el.on('select2:select', function(e) {
                    updatePriceByUnit($el);


                    // Pindah ke Qty di baris yang sama, Enter selanjutnya baru pindah baris
                    $(this).closest('tr').find('input.qty').focus().select();
                });
                // Langsung update harga pertama kali load
            }

            // 2. Fungsi Otomatis Update Harga saat Satuan Diganti
            $(document).on('change', '.select-measurement', function() {
                updatePriceByUnit($(this));
            });

            function updatePriceByUnit($selectEl) {
                let row = $selectEl.closest('tr');
                let selectedOption = $selectEl.find(':selected');

                // Ambil harga dari atribut data-price yang kita kirim dari server
                let price = parseFloat(selectedOption.data('price')) || 0;

                // Update input harga di baris tersebut
                row.find('.price').val(price);

                // Jalankan kalkulasi total
                recalc();
            }

            function recalc() {
                let subtotal = 0;

                cartBody.querySelectorAll('tr').forEach(tr => {
                    const qty = parseFloat(tr.querySelector('.qty').value) || 0;
                    const price = parseFloat(tr.querySelector('.price').value) || 0;
                    const disc = parseFloat(tr.querySelector('.disc-item').value) || 0;

                    const base = qty * price;
                    const line = Math.max(base - disc, 0);

                    tr.querySelector('.subtotal').innerText = rupiah(line);
                    subtotal += line;
                });

                // DISKON GLOBAL
                const dgType = document.getElementById('disc_global_type').value;
                const dgVal = parseFloat(document.getElementById('disc_global_value').value) || 0;

                let dgAmt = dgType === 'percent' ?
                    subtotal * (dgVal / 100) :
                    dgVal;

                dgAmt = Math.min(dgAmt, subtotal);
                const dpp = subtotal - dgAmt;

                // PPN EXCLUDE
                let ppn = 0;
                if (document.getElementById('ppn_type').value === 'include') {
                    ppn = dpp * 0.11;
                }

                const grand = dpp + ppn;

                document.getElementById('subtotal').innerText = rupiah(subtotal);
                document.getElementById('discGlobal').innerText = rupiah(dgAmt);
                document.getElementById('ppnVal').innerText = rupiah(ppn);
                document.getElementById('grandTotal').innerText = rupiah(grand);
                document.getElementById('grandTotal2').innerText = rupiah(grand);

                window.currentGrandTotal = grand;
                updateChange(grand);
            }

            // Shortcut F1 untuk mulai input di baris pertama
            $(document).on('keydown', function(e) {
                if (e.key === 'F4') {
                    e.preventDefault();
                    let firstRow = $('#cartTable tbody tr:first');
                    focusRow(firstRow);
                }
            });

            function focusRow(row) {
                if (row && row.length > 0) {
                    // 1. Highlight visual
                    $('#cartTable tbody tr').removeClass('table-primary'); // Pastikan ID tabel benar
                    row.addClass('table-primary');

                    // 2. Tembak input Qty
                    // Gunakan setTimeout 50ms jika baris baru saja dibuat (render delay)
                    setTimeout(() => {
                        let qtyInput = row.find('input.qty');
                        if (qtyInput.length > 0) {
                            qtyInput.focus().select();
                        }
                    }, 50);

                    // 3. Scroll ke pandangan user
                    row[0].scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });
                }
            }

            $(document).on('keydown', '#cartTable tbody tr input, #cartTable tbody tr select', function(e) {
                // Deteksi Enter dan pastikan Select2 sedang tertutup
                if (e.key === 'Enter' && !$('.select2-container--open').length) {
                    e.preventDefault();

                    let currentRow = $(this).closest('tr');
                    let nextRow = currentRow.next('tr');

                    if (nextRow.length > 0) {
                        focusRow(nextRow);
                    } else {
                        // JIKA DI BARIS TERAKHIR: Tambah baris baru otomatis
                        if (typeof addRow === "function") {
                            addRow(); // Panggil fungsi tambah baris Anda
                            // Fokuskan ke baris yang baru saja dibuat
                            let newlyAddedRow = $('#cartTable tbody tr:last');
                            focusRow(newlyAddedRow);
                        }
                    }
                }
            });
            $(document).on('keydown', '#cartTable tbody tr input, #cartTable tbody tr select', function(e) {
                // Jika menekan Enter (dan bukan di dalam dropdown yang sedang terbuka)
                if (e.key === 'Enter' && !$('.select2-container--open').length) {
                    e.preventDefault();

                    let nextRow = $(this).closest('tr').next('tr');

                    if (nextRow.length > 0) {
                        focusRow(nextRow);
                    } else {
                        // Opsional: Jika di baris terakhir, otomatis tambah baris baru
                        // addRow();
                        focusRow($('#cartTable tbody tr:last'));
                    }
                }
            });
            $(document).on('keydown', function(e) {
                // Cari baris yang sedang aktif (berdasarkan kursor)
                let activeRow = $(document.activeElement).closest('tr');
                if (activeRow.length === 0) return;

                // Pastikan user TIDAK sedang mengetik di dalam input (agar huruf u/s tidak terketik)
                let isTyping = $(document.activeElement).is('input[type="number"], input[type="text"]');

                // Shortcut 'u' atau 's' untuk Satuan
                if (!isTyping && (e.key.toLowerCase() === 'u' || e.key.toLowerCase() === 's')) {
                    e.preventDefault();
                    let selectUm = activeRow.find('.select-measurement');

                    // Gunakan logika yang sudah work sebelumnya
                    selectUm.val(null).trigger('change');
                    selectUm.select2('open');
                }
            });
        </script>
        <style>
            #cartTable tbody tr.table-primary {
                background-color: #e7f1ff !important;
                border-left: 5px solid #0d6efd;
                transition: all 0.2s;
            }
        </style>
        <script>
            cartBody.addEventListener('input', recalc);

            document.getElementById('disc_global_type').addEventListener('change', recalc);
            document.getElementById('disc_global_value').addEventListener('input', recalc);
            document.getElementById('ppn_type').addEventListener('change', recalc);

            $(document).on('click', '.removerow', function() {
                const tr = $(this).closest('tr');

                // Ambil ID dan Tipe dari atribut data- (sesuaikan dengan cara addItem)
                // Jika di HTML tertulis data-priceid, gunakan .data('priceid')
                const idUnik = tr.data('priceid') || tr.attr('data-priceid');
                const tipeItem = tr.data('type') || tr.attr('data-type');

                console.log('Menghapus ID:', idUnik, 'Tipe:', tipeItem);

                // 3. Jika tipenya racikan, hapus dari variabel memori
                if (tipeItem === 'racikan') {
                    // Pastikan variabel racikanDetails bisa diakses di scope ini
                    if (racikanDetails && racikanDetails[idUnik]) {
                        delete racikanDetails[idUnik];
                        console.log('Detail racikan ' + idUnik + ' telah dihapus dari memori.');
                    }
                }
                // 4. Hapus baris dari tampilan tabel
                tr.remove();

                // 5. Hitung ulang total belanjaan
                recalc();
            });
        </script>
        <script>
            function handlePay() {
                // pastikan ada item
                const rows = cartBody.querySelectorAll('tr');
                if (rows.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Keranjang Kosong',
                        text: 'Silakan pilih obat atau item terlebih dahulu sebelum melanjutkan.',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Siap, Mengerti!'
                    });
                    return;
                }

                let subtotal = 0;

                rows.forEach(tr => {
                    const qty = parseFloat(tr.querySelector('.qty').value) || 0;
                    const price = parseFloat(tr.querySelector('.price').value) || 0;
                    const disc = parseFloat(tr.querySelector('.disc-item').value) || 0;

                    const line = Math.max(qty * price - disc, 0);
                    tr.querySelector('.subtotal').innerText = rupiah(line);

                    subtotal += line;
                });

                // Diskon global
                const dgTypeEl = document.getElementById('disc_global_type');
                const dgValEl = document.getElementById('disc_global_value');
                const dgType = dgTypeEl.value;
                const dgVal = parseFloat(dgValEl.value) || 0;

                let dgAmt = dgType === 'percent' ? subtotal * (dgVal / 100) : dgVal;
                dgAmt = Math.min(dgAmt, subtotal);

                const dpp = subtotal - dgAmt;

                // PPN
                const ppnEl = document.getElementById('ppn_type');
                let ppn = 0;
                if (ppnEl.value === 'include') {
                    ppn = dpp * 0.11;
                }

                const grand = dpp + ppn;

                // Payment input
                const paymentInput = document.getElementById('paymentInput');
                const paid = parseFloat(paymentInput.value) || 0;

                // console.log(paymentType);

                const paymentType = document.getElementById('payment_type').value;

                if (paymentType == 'cash') {
                    if (paid < 0) {
                        alert('Masukan nominal pembayaran');
                        paymentInput.focus();
                        return;
                    }

                    if (paid < grand) {
                        alert('Pembayaran kurang');
                        paymentInput.focus();
                        return;
                    }
                }

                const holdid = document.getElementById('hold_id').value;
                console.log(holdid);
                // Submit
                // Contoh pemanggilan
                submitSale('paid', subtotal, dgAmt, ppn, grand, holdid)
                // Notifikasi
                // showSuccessToast('Pembayaran berhasil');

                // Reset
                // clearCart();

            }
        </script>
        <script>
            // ============================
            // BUTTON CLICK
            // ============================
            document.getElementById('button_pay').addEventListener('click', () => handlePay());
            document.getElementById('button_hold').addEventListener('click', () => handleHold());
            document.getElementById('button_hold_list').addEventListener('click', () => {
                const modal = new bootstrap.Modal(document.getElementById('holdListModal'));
                modal.show();
            });

            // ============================
            // HOLD FUNCTION
            // ============================
            function handleHold() {
                const rows = cartBody.querySelectorAll('tr');
                if (rows.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Keranjang Kosong',
                        text: 'Silakan pilih obat atau item terlebih dahulu sebelum melanjutkan.',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Siap, Mengerti!'
                    });
                    return;
                }

                let subtotal = 0;
                rows.forEach(tr => {
                    const qty = parseFloat(tr.querySelector('.qty').value) || 0;
                    const price = parseFloat(tr.querySelector('.price').value) || 0;
                    const disc = parseFloat(tr.querySelector('.disc-item').value) || 0;

                    const line = Math.max(qty * price - disc, 0);
                    tr.querySelector('.subtotal').innerText = rupiah(line);

                    subtotal += line;
                });

                // Diskon global
                const dgTypeEl = document.getElementById('disc_global_type');
                const dgValEl = document.getElementById('disc_global_value');
                const dgType = dgTypeEl.value;
                const dgVal = parseFloat(dgValEl.value) || 0;

                let dgAmt = dgType === 'percent' ? subtotal * (dgVal / 100) : dgVal;
                dgAmt = Math.min(dgAmt, subtotal);

                const dpp = subtotal - dgAmt;

                // PPN
                const ppnEl = document.getElementById('ppn_type');
                let ppn = 0;
                if (ppnEl.value === 'include') {
                    ppn = dpp * 0.11;
                }

                const grand = dpp + ppn;

                let items = [];
                cartBody.querySelectorAll('tr').forEach(tr => {
                    const measurementEl = tr.querySelector('.measurement_id') || tr.querySelector(
                        '.select-measurement');
                    items.push({
                        price_id: tr.dataset.priceid,
                        product_id: tr.querySelector('.product_id').value,
                        measurement_id: measurementEl ? measurementEl.value : null,
                        qty: tr.querySelector('.qty').value,
                        price: tr.querySelector('.price').value,
                        disc: tr.querySelector('.disc-item').value
                    });
                });
                // console.log(items);
                const holdid = document.getElementById('hold_id').value;

                submitSale('draft', subtotal, dgAmt, ppn, grand, holdid, items);
                // alert('Transaksi ditunda (HOLD)');
            }

            // ============================
            // RESUME HOLD FUNCTION
            // ============================
            function resumeHold(id) {
                // 1. Tampilkan Loading agar user tidak klik berkali-kali
                Swal.fire({
                    title: 'Memuat Data...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/sales/hold/${id}/resume`)
                    .then(res => {
                        if (!res.ok) throw new Error('Gagal mengambil data dari server');
                        return res.json();
                    })
                    .then(data => {
                        // 2. Kosongkan keranjang lama
                        clearCart();

                        // 3. Masukkan item baru
                        if (data.racikanDetails) {
                            // Jangan pakai 'let' atau 'const' agar mengisi variabel global yang sudah ada
                            racikanDetails = data.racikanDetails;
                            console.log("Memori racikan dipulihkan:", racikanDetails);
                        }

                        if (data.items && data.items.length > 0) {
                            data.items.forEach(item => {
                                addItem(item); // addItem akan membaca item.id_unik
                            });
                        }

                        // 4. Set Header Data
                        document.getElementById('disc_global_value').value = data.sales_mstr_discamt || 0;
                        document.getElementById('ppn_type').value = data.ppn_type;
                        document.getElementById('hold_id').value = data.sales_mstr_id;

                        // 5. Hitung ulang totalan
                        recalc();

                        // 6. Tutup Modal Hold List
                        const modalEl = document.getElementById('holdListModal');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();

                        // 7. Tutup Loading dan beri notifikasi sukses (Toast)
                        Swal.fire({
                            icon: 'success',
                            title: 'Data Berhasil Dimuat',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan: ' + err.message
                        });
                    });
            }

            // ============================
            // CANCEL HOLD FUNCTION
            // ============================
            function cancelHold(id) {
                // 1. Konfirmasi dengan tema Peringatan (Warning)
                Swal.fire({
                    title: 'Batalkan Transaksi?',
                    text: "Data transaksi yang ditunda ini akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33', // Warna merah untuk aksi hapus
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // 2. Tampilkan Loading saat proses hapus di server
                        Swal.fire({
                            title: 'Sedang Menghapus...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        fetch(`/sales/hold/${id}/cancel`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(res => {
                                if (!res.ok) throw new Error('Gagal membatalkan transaksi');
                                return res.json();
                            })
                            .then(res => {
                                // 3. Notifikasi sukses (Toast)
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Dibatalkan',
                                    text: 'Transaksi HOLD telah berhasil dihapus.',
                                    timer: 2000,
                                    showConfirmButton: false
                                });

                                // 4. Refresh daftar hold
                                if (typeof updateHoldList === 'function') {
                                    updateHoldList();
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: err.message
                                });
                            });
                    }
                });
            }
            // ============================
            // UPDATE HOLD LIST RELOAD
            // ============================
            function updateHoldList() {
                fetch(`/sales/hold/list`)
                    .then(res => res.json())
                    .then(data => {
                        const tbody = document.getElementById('holdListBody');
                        tbody.innerHTML = '';
                        data.forEach(hold => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                    <td>${hold.sales_mstr_nbr}</td>
                    <td>${hold.sales_mstr_grandtotal.toLocaleString('id-ID')}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="resumeHold(${hold.sales_mstr_id})">Lanjutkan</button>
                        <button class="btn btn-sm btn-danger" onclick="cancelHold(${hold.sales_mstr_id})">Batal</button>
                    </td>
                `;
                            tbody.appendChild(tr);
                        });
                    });
            }
        </script>
        <script>
            document.addEventListener('keydown', function(e) {
                // ENTER → FOCUS PAYMENT

                // =========================
                // GUARD KEY YANG DIPAKAI SAJA
                // =========================
                const key = e.key.toLowerCase();
                const isAltP = e.altKey && key === 'p';
                const isHold = key === 'h';
                const isEsc = e.key === 'Escape';
                const isF2 = e.key === 'F2';
                const isF8 = e.key === 'F8';

                if (!isAltP && !isHold && !isEsc && !isF2 && !isF8) return;
                let subtotal = 0;

                cartBody.querySelectorAll('tr').forEach(tr => {
                    const qty = parseFloat(tr.querySelector('.qty').value) || 0;
                    const price = parseFloat(tr.querySelector('.price').value) || 0;
                    const disc = parseFloat(tr.querySelector('.disc-item').value) || 0;

                    const base = qty * price;
                    const line = Math.max(base - disc, 0);

                    tr.querySelector('.subtotal').innerText = rupiah(line);
                    subtotal += line;
                });

                // =========================
                // ELEMENT SAFETY
                // =========================
                const dgTypeEl = document.getElementById('disc_global_type');
                const dgValEl = document.getElementById('disc_global_value');
                const ppnEl = document.getElementById('ppn_type');

                // console.log(dgTypeEl, dgValEl, ppnEl);

                if (!dgTypeEl || !dgValEl || !ppnEl) {
                    console.warn('Element diskon / PPN tidak ditemukan');
                    return;
                }

                // =========================
                // HITUNG NILAI FINAL
                // =========================
                const dgType = dgTypeEl.value;
                const dgVal = parseFloat(dgValEl.value) || 0;

                let dgAmt = dgType === 'percent' ?
                    subtotal * (dgVal / 100) :
                    dgVal;

                // dgAmt = Math.min(dgAmt, subtotal);
                // console.log(dgAmt);

                const dpp = subtotal - dgAmt;
                // console.log(dpp);

                // PPN (aktif jika include)
                let ppn = 0;
                if (ppnEl.value === 'include') {
                    ppn = dpp * 0.11;
                }

                const grand = dpp + ppn;

                // =========================
                // AKSI
                // =========================
                if (isF8) {
                    e.preventDefault();
                    document.getElementById('paymentInput').focus();
                }

                if (isAltP) {
                    e.preventDefault();
                    handlePay();
                    return;
                }

                if (isHold) {
                    e.preventDefault();
                    handleHold();
                    return;
                }

                if (isEsc) {
                    e.preventDefault();
                    if (confirm('Batalkan transaksi?')) {
                        clearCart();
                    }
                    return;
                }

                if (isF2) {
                    e.preventDefault();

                    // 1. Hapus item yang sedang terpilih (Reset ke null/kosong)
                    $('#itemInput').val(null).trigger('change');

                    // 2. Baru buka dropdown-nya
                    $('#itemInput').select2('open');

                    // 3. Tambahan: Hapus highlight biru di baris pertama hasil pencarian
                    setTimeout(function() {
                        $('.select2-results__option--highlighted').removeClass(
                            'select2-results__option--highlighted');
                    }, 1);
                }

            });
        </script>
        <script>
            document.addEventListener('keydown', function(e) {
                if (e.key === 'F4') {
                    // console.log('f4 cliked');
                    e.preventDefault();
                    let firstRow = $('#cartTable tbody tr:first');
                    focusRow(firstRow);
                }
            });

            function focusRow(row) {
                if (row && row.length > 0) {
                    // 1. Highlight visual
                    $('#cartTable tbody tr').removeClass('table-primary'); // Pastikan ID tabel benar
                    row.addClass('table-primary');

                    // 2. Tembak input Qty
                    // Gunakan setTimeout 50ms jika baris baru saja dibuat (render delay)
                    setTimeout(() => {
                        let qtyInput = row.find('input.qty');
                        if (qtyInput.length > 0) {
                            qtyInput.focus().select();
                        }
                    }, 50);

                    // 3. Scroll ke pandangan user
                    row[0].scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });
                }
            }

            $(document).on('keydown', '#cartTable tbody tr input, #cartTable tbody tr select', function(e) {
                // Deteksi Enter dan pastikan Select2 sedang tertutup
                if (e.key === 'Enter' && !$('.select2-container--open').length) {
                    e.preventDefault();

                    let currentRow = $(this).closest('tr');
                    let nextRow = currentRow.next('tr');

                    if (nextRow.length > 0) {
                        focusRow(nextRow);
                    } else {
                        // JIKA DI BARIS TERAKHIR: Tambah baris baru otomatis
                        if (typeof addRow === "function") {
                            addRow(); // Panggil fungsi tambah baris Anda
                            // Fokuskan ke baris yang baru saja dibuat
                            let newlyAddedRow = $('#cartTable tbody tr:last');
                            focusRow(newlyAddedRow);
                        }
                    }
                }
            });

            $(document).on('keydown', function(e) {
                // Cari baris yang sedang aktif (berdasarkan kursor)
                let activeRow = $(document.activeElement).closest('tr');
                if (activeRow.length === 0) return;

                // Pastikan user TIDAK sedang mengetik di dalam input (agar huruf u/s tidak terketik)
                let isTyping = $(document.activeElement).is('input[type="number"], input[type="text"]');

                // Shortcut 'u' atau 's' untuk Satuan
                if (!isTyping && (e.key.toLowerCase() === 'u' || e.key.toLowerCase() === 's')) {
                    e.preventDefault();
                    let selectUm = activeRow.find('.select-measurement');

                    // Gunakan logika yang sudah work sebelumnya
                    selectUm.val(null).trigger('change');
                    selectUm.select2('open');
                }
            });
        </script>
        <style>
            #cartTable tbody tr.table-primary {
                background-color: #e7f1ff !important;
                border-left: 5px solid #0d6efd;
                transition: all 0.2s;
            }
        </style>
        <script>
            function submitSale(type, subtotal, disc_global, ppn, grandtotal, holdid) {
                // 1. Validasi Cart (Menggunakan SweetAlert)
                if (cartBody.children.length === 0) {
                    swalError(
                        "Keranjang Kosong",
                        "Silahkan pilih obat terlebih dahulu!"
                    );
                    return;
                }

                const modalTitle = type === 'draft' ? 'Simpan sebagai Draft?' : 'Konfirmasi Pembayaran';
                const confirmText = type === 'draft' ? 'Ya, Simpan' : 'Ya, Proses Sekarang';

                // Tampilkan konfirmasi dan loading
                Swal.fire({
                    title: modalTitle,
                    text: type === 'draft' ? 'Transaksi akan disimpan di daftar tunggu.' :
                        `Total Bayar: Rp ${parseFloat(grandtotal).toLocaleString('id-ID')}`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Proses Sekarang',
                    cancelButtonText: 'Batal',
                    width: window.innerWidth < 768 ? '90%' : '32em',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tampilkan Loading
                        Swal.fire({
                            title: 'Sedang Memproses...',
                            width: window.innerWidth < 768 ? '90%' : '32em',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Ambil data pendukung
                        const payment_method = document.getElementById('payment_method').value;
                        const payment_type = document.getElementById('payment_type').value;
                        const customer_id = document.getElementById('customer_id').value;
                        const paymentInput = document.getElementById('paymentInput').value;
                        const change = document.getElementById('change').value;
                        // console.log("payment: ", paymentInput);

                        const items = [];
                        cartBody.querySelectorAll('tr').forEach(tr => {
                            // Cari elemen measurement, coba ambil class .measurement_id dulu (untuk racikan)
                            // jika tidak ada, ambil dari .select-measurement (untuk reguler)
                            const measurementEl = tr.querySelector('.measurement_id') || tr.querySelector(
                                '.select-measurement');

                            items.push({
                                price_id: tr.dataset.priceid,
                                type: tr.dataset.type,
                                product_id: tr.querySelector('.product_id')
                                    ?.value, // Gunakan ?. untuk keamanan
                                measurement_id: measurementEl ? measurementEl.value :
                                null, // Ambil value dengan aman
                                qty: tr.querySelector('.qty')?.value || 0,
                                price: tr.querySelector('.price')?.value || 0,
                                disc: tr.querySelector('.disc-item')?.value || 0
                            });
                        });

                        const details = (typeof racikanDetails !== 'undefined') ? racikanDetails : null;
                        console.log(details);
                        // Di dalam event listener submit
                        if (window.APP_CONFIG.allow_negative == 0) {
                            let itemsBermasalah = [];

                            // Cek setiap item di keranjang
                            items.forEach(item => {
                                // Pastikan qty dan stock di-convert ke angka agar perbandingan valid
                                if (parseFloat(item.qty) > parseFloat(item.stock)) {
                                    itemsBermasalah.push(item.product); // Simpan nama itemnya buat info di Swal
                                }
                            });

                            if (itemsBermasalah.length > 0) {
                                // Tampilkan Swal dengan detail barang apa yang kurang
                                swalError(
                                    "Stok Tidak Cukup",
                                    "Item berikut melebihi stok: " + itemsBermasalah.join(", ")
                                );
                                return; // BERHENTI, fetch tidak akan dijalankan
                            }
                        }
                        // Jika lolos cek stok, baru jalankan fetch
                        // console.log(items);

                        // Proses Fetch
                        fetch("{{ route('SalesMstr.store') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    subtotal,
                                    grandtotal,
                                    ppn,
                                    paymentInput,
                                    change,
                                    payment_method,
                                    payment_type,
                                    customer_id,
                                    disc_global,
                                    type,
                                    holdid,
                                    loc_id: document.getElementById('location').value,
                                    ppn_type: document.getElementById('ppn_type').value,
                                    items,
                                    details
                                })
                            })
                            .then(async response => {
                                // Ambil teks mentah dulu untuk memastikan ada isinya
                                const text = await response.text();
                                // console.log(response);

                                if (!response.ok) {
                                    let errorMsg = response.statusText;
                                    try {
                                        // Coba parse JSON, kalau gagal (karena isinya HTML error), dia akan ke catch di bawahnya
                                        const errorObj = JSON.parse(text);
                                        errorMsg = errorObj.message || errorObj.error || errorMsg;
                                    } catch (e) {
                                        // Jika text bukan JSON (misal error 500 HTML), gunakan teks mentah tapi batasi panjangnya
                                        errorMsg = text.substring(0, 100) || response.statusText;
                                    }
                                    throw new Error(errorMsg);
                                }

                                // Jika kosong, kembalikan objek kosong agar tidak error di .json()
                                return text ? JSON.parse(text) : {};
                            })
                            .then(res => {
                                // Notifikasi Sukses
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: res.message || 'Transaksi berhasil disimpan',
                                    timer: 2000,
                                    width: window.innerWidth < 768 ? '90%' : '32em',
                                    showConfirmButton: false
                                }).then(() => {
                                    if (res.print_id) {
                                        const printUrl = `/sales/print/${res.print_id}`;
                                        const frame = document.getElementById('printFrame');

                                        // Isi source iframe dengan URL print
                                        frame.src = printUrl;

                                        // Tunggu hingga konten invoice termuat, lalu panggil perintah print
                                        frame.onload = function() {
                                            frame.contentWindow.focus();
                                            frame.contentWindow.print();
                                        };
                                        clearCart()
                                    } else {
                                        clearCart()
                                        if (typeof updateHoldList === 'function') {
                                            updateHoldList();
                                        }
                                    }

                                    // Opsi: window.location.reload(); jika ingin reset total
                                });
                            })
                            .catch((err) => {
                                // Console log untuk memastikan pesan yang masuk
                                console.log("Caught Error:", err.message);

                                // Langsung gunakan helper swalError kamu
                                // err.message akan berisi "Tidak ada session kasir aktif"
                                swalError("Pembayaran Gagal", err.message || "Terjadi kesalahan pada server");
                            });
                    }
                });
                // clearCart();

            }

            function clearCart() {
                cartBody.innerHTML = '';
                document.getElementById('disc_global_value').value = 0;
                document.getElementById('paymentInput').value = 0;
                document.getElementById('change').value = 0;
                recalc();
            }

            function rupiah(n) {
                return Number(n || 0).toLocaleString('id-ID');
            }
        </script>
        <script>
            $(document).ready(function() {
                $('#fabMain').on('click', function() {
                    const menu = $('#fabMenu');

                    if (menu.hasClass('d-none')) {
                        menu.removeClass('d-none').addClass('d-flex');
                        $(this).find('i').addClass('rotate-45'); // Opsional: rotasi icon
                    } else {
                        menu.removeClass('d-flex').addClass('d-none');
                        $(this).find('i').removeClass('rotate-45');
                    }
                });

                // Klik di luar untuk menutup
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('#fabMain').length && !$(e.target).closest('#fabMenu').length) {
                        $('#fabMenu').removeClass('d-flex').addClass('d-none');
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $('#closeCashierBtn').on('click', function(e) {
                    e.preventDefault();
                    const loc = document.getElementById('location').value;

                    Swal.fire({
                        title: 'Konfirmasi Tutup Kasir',
                        text: "Apakah Anda yakin ingin menutup kasir sekarang? Laporan rekap akan dicetak otomatis.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Tutup & Cetak!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Tampilkan loading saat proses
                            Swal.fire({
                                title: 'Memproses...',
                                text: 'Sedang mengunci transaksi dan membuat PDF',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            $.ajax({
                                url: "{{ route('CashierSession.close') }}", // Sesuaikan nama route-mu
                                method: "POST",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    loc: loc
                                },
                                success: function(response) {
                                    if (response.success) {
                                        Swal.close();

                                        // Buka tab baru untuk print
                                        let printWindow = window.open(response.print_url,
                                            '_blank');

                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Kasir Ditutup',
                                            text: 'Mencetak rekap...',
                                            timer: 2000
                                        }).then(() => {
                                            window.location
                                                .reload(); // Refresh halaman kasir
                                        });
                                    }
                                },
                                error: function(xhr) {
                                    Swal.fire('Error!', 'Terjadi kesalahan pada server.',
                                        'error');
                                }
                            });
                        }
                    });
                });
            });
        </script>
        <iframe id="printFrame" style="display:none;"></iframe>
    @endpush

    </x-app-layout>
