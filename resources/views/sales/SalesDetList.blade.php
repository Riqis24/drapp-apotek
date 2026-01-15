<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Detail Penjualan</h3>
        </div>
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body px-5 py-4">
                @php
                    if ($transaction->sales_mstr_status == 'draft') {
                        $status = 'Hold';
                        $color = 'warning';
                    } elseif ($transaction->sales_mstr_status == 'posted') {
                        $status = 'completed';
                        $color = 'success';
                    } else {
                        $status = 'unknown';
                        $color = 'danger';
                    }
                @endphp

                <div class="text-center">
                    <small class="text-muted">Order Pesanan</small>
                    <h5 class="fw-bold text-dark mb-0">{{ $transaction->sales_mstr_nbr }}

                    </h5>
                </div>
                <div class="text-center">
                    <span class="badge rounded-pill bg-{{ $color }} px-3 py-2">
                        {{ ucfirst($status) }}
                    </span>
                    <div class="btn-group dropend">
                        <button type="button" class="btn btn-outline-success btn-sm dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-printer"></i>
                        </button>
                        {{-- <ul class="dropdown-menu">
                            <li class="dropdown-item">
                                <a href="{{ route('StoreProfile.printInvoice', $id) }}">Invoice</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ route('StoreProfile.printNota', $id) }}">Nota</a>
                            </li>
                        </ul> --}}
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-md-3 mb-3 mb-md-0">
                        <small class="text-muted">Sub Total</small>
                        <h5 class="fw-semibold text-success mt-1">
                            {{ rupiah($transaction->sales_mstr_subtotal) }}
                        </h5>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <small class="text-muted">Diskon</small>
                        <h5 class="fw-semibold text-warning mt-1">
                            {{ rupiah($transaction->sales_mstr_discamt) }}
                        </h5>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <small class="text-muted">PPN</small>
                        <h5 class="fw-semibold text-secondary mt-1">
                            {{ rupiah($transaction->sales_mstr_ppnamt) }}
                        </h5>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <small class="text-muted">Grand Total</small>
                        <h5 class="fw-semibold text-secondary mt-1">
                            {{ rupiah($transaction->sales_mstr_grandtotal) }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-content">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="custTable" class="table table-bordered table-sm nowrap" style="width:100%">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th style="width:5%">No</th>
                                    <th>Nama Produk / Deskripsi</th>
                                    <th style="width:10%">Satuan</th>
                                    <th style="width:10%">Qty</th>
                                    <th style="width:12%">@ Harga</th>
                                    <th style="width:12%">Diskon</th>
                                    <th style="width:12%">Sub Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($details as $item)
                                    @php
                                        // Logika identifikasi tipe baris
                                        $isChildBundle = !is_null($item->sales_det_parentid);
                                        $isRacikan = !is_null($item->sales_det_pmid);
                                    @endphp

                                    <tr
                                        class="{{ $isChildBundle ? 'table-light' : '' }} {{ $isRacikan ? 'bg-aliceblue' : '' }}">
                                        <td class="text-center text-muted">
                                            {{ $isChildBundle ? '' : $loop->iteration }}
                                        </td>

                                        <td style="{{ $isChildBundle ? 'padding-left: 30px;' : '' }}">
                                            @if ($isChildBundle)
                                                <i class="bi bi-arrow-return-right text-muted me-1"></i>
                                            @endif

                                            @if ($isRacikan)
                                                <span class="fw-bold">
                                                    {{ $item->prescription->pres_mstr_name }}
                                                </span>
                                            @else
                                                <span class="{{ !$isChildBundle ? 'fw-bold' : 'text-muted' }}">
                                                    {{ $item->product->name }}
                                                </span>
                                            @endif

                                            {{-- Badge Status --}}
                                            @if ($isRacikan)
                                                <span class="badge bg-primary text-white ms-1"
                                                    style="font-size: 0.7rem;">Racikan</span>
                                            @elseif($item->sales_det_type == 'bundle' && !$isChildBundle)
                                                <span class="badge bg-info text-dark ms-1"
                                                    style="font-size: 0.7rem;">Bundle</span>
                                            @endif
                                        </td>
                                        @if ($isRacikan)
                                            <td class="text-center small">Bungkus</td>
                                        @else
                                            <td class="text-center small">{{ $item->measurement->name }}</td>
                                        @endif
                                        <td class="text-center fw-bold">{{ $item->sales_det_qty }}</td>
                                        <td class="text-end">{{ rupiah($item->sales_det_price) }}</td>
                                        <td class="text-end text-danger">{{ rupiah($item->sales_det_discamt) }}
                                        </td>
                                        <td class="text-end fw-bolder">{{ rupiah($item->sales_det_subtotal) }}</td>
                                    </tr>

                                    {{-- LOOP BAHAN RACIKAN (Jika ada pmid) --}}
                                    @if ($isRacikan && $item->prescription)
                                        @foreach ($item->prescription->details as $bahan)
                                            <tr style="font-size: 0.85rem; background-color: #fdfdfd;">
                                                <td></td>
                                                <td class="ps-5 text-muted fst-italic">
                                                    ↳ {{ $bahan->product->name }}
                                                    <span
                                                        class="badge bg-secondary small">{{ numfmt($bahan->pres_det_qty) }}
                                                        {{ $bahan->measurement->name }}</span>
                                                </td>
                                                <td colspan="5" class="bg-white"></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-dark btn-sm" type="button"
                        onclick="window.location.href='{{ route('SalesMstr.index') }}'">Back</button>
                </div>
            </div>
        </div>
    </div>

    {{-- modal add user --}}
    <form action="{{ route('CustMstr.store') }}" method="POST">
        @csrf
        <div class="modal fade" id="modalAddCustomer" tabindex="-1" aria-labelledby="modalAddCustomerLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalAddCustomerLabel">Cust Master</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="height: 400px; overflow-y: scroll;">
                        <div id="dynamicCustsInputs">
                            <div class="cust-row">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="name" class="form-label">Nama</label>
                                        <input type="text" class="form-control form-control-sm" name="names[]"
                                            required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="address" class="form-label">Alamat</label>
                                        <input type="text" class="form-control form-control-sm" name="addresss[]"
                                            required>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="phone" class="form-label">No HP</label>
                                        <input type="text" class="form-control form-control-sm" name="phones[]"
                                            required>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="outstanding" class="form-label">Piutang</label>
                                        <input type="text" class="form-control form-control-sm" value="0"
                                            name="outstandings[]" required>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="" class="form-label">Remove</label>
                                        <button type="button"
                                            class="btn btn-danger btn-sm rounded removeRow">❌</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-info" id="addRow">Add Row</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
        <script src="{{ 'assets/js/CustMstr/getData.js' }}"></script>
        <script src="{{ 'assets/js/alert.js' }}"></script>
    @endpush
</x-app-layout>
