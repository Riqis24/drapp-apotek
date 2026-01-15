<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Details PO</h3>
        </div>
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body px-5 py-4">
                {{-- @php
                    if ($transaction->status == '0') {
                        $status = 'open';
                        $color = 'warning';
                    } elseif ($transaction->status == '1') {
                        $status = 'completed';
                        $color = 'success';
                    } else {
                        $status = 'unknown';
                        $color = 'danger';
                    }
                @endphp --}}

                <div class="text-center">
                    <small class="text-muted">No. Purchase Order</small>
                    <h5 class="fw-bold text-dark mb-0">{{ $po->po_mstr_nbr }}

                    </h5>
                </div>
                <div class="row text-center">
                    <div class="col-md-3 mb-3 mb-md-0">
                        <small class="text-muted">Nama Supplier</small>
                        <h5 class="fw-bold text-primary mt-1">
                            {{ $po->supplier->supp_mstr_name }}
                        </h5>
                    </div>
                    <div class="col-md-2 mb-3 mb-md-0">
                        <small class="text-muted">Sub Total</small>
                        <h5 class="fw-bold text-info mt-1">
                            {{ rupiah($po->po_mstr_subtotal) }}
                        </h5>
                    </div>
                    <div class="col-md-2 mb-3 mb-md-0">
                        <small class="text-muted">Discount</small>
                        <h5 class="fw-bold text-warning mt-1">
                            {{ rupiah($po->po_mstr_discamt) }}
                        </h5>
                    </div>
                    <div class="col-md-2 mb-3 mb-md-0">
                        <small class="text-muted">PPN</small>
                        <h5 class="fw-bold text-secondary mt-1">
                            {{ rupiah($po->po_mstr_ppnamt) }}
                        </h5>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <small class="text-muted">Grand Total</small>
                        <h5 class="fw-bold text-success mt-1">
                            {{ rupiah($po->po_mstr_grandtotal) }}
                        </h5>
                    </div>

                </div>
            </div>
        </div>

        <div class="page-content">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="custTable" class="table table-striped table-bordered table-sm nowrap"
                            style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width:5%; text-align: center">No</th>
                                    <th style="text-align: center">Nama</th>
                                    <th style="width:12%; text-align: center">Qty</th>
                                    <th style="width:12%; text-align: center">Qty Terima</th>
                                    <th style="width:12%; text-align: center">Kurangan</th>
                                    <th style="width:12%; text-align: center">Satuan</th>
                                    <th style="width:15%; text-align: center">@ Harga</th>
                                    <th style="width:15%; text-align: center">Diskon</th>
                                    <th style="width:15%; text-align: center">Sub Total</th>
                                    {{-- <th style="width:5%; text-align: center">Aksi</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($details as $detail)
                                    <tr>
                                        <td style="text-align:right;">{{ $loop->iteration }}</td>
                                        <td>{{ $detail->product->name }}</td>
                                        <td style="text-align:right;">{{ numfmt($detail->po_det_qty) }}</td>
                                        <td style="text-align:right;">{{ numfmt($detail->po_det_qtyrcvd) }}</td>
                                        <td style="text-align:right;">{{ numfmt($detail->po_det_qtyremain) }}</td>
                                        <td style="text-align:center;">{{ $detail->product->measurement->name }}</td>
                                        <td style="text-align:right;">{{ rupiah($detail->po_det_price) }}</td>
                                        <td style="text-align:right;">{{ rupiah($detail->po_det_discamt) }}</td>
                                        <td style="text-align:right;">{{ rupiah($detail->po_det_total) }}</td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-dark btn-sm" type="button"
                        onclick="window.location.href='{{ route('PurchaseOrder.index') }}'">Back</button>
                </div>
            </div>
        </div>
    </div>



    @push('scripts')
        <script src="{{ 'assets/js/CustMstr/getData.js' }}"></script>
        <script src="{{ 'assets/js/alert.js' }}"></script>
    @endpush
</x-app-layout>
