<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Details BPB</h3>
        </div>
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body px-5 py-4">
                <div class="row text-center">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <small class="text-muted">Bukti Penerimaan Barang</small>
                        <h5 class="fw-bold text-primary mt-1">
                            {{ $bpb->bpb_mstr_nbr }}
                        </h5>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <small class="text-muted">PO</small>
                        <h5 class="fw-bold text-info mt-1">
                            {{ $bpb->po->po_mstr_nbr }}
                        </h5>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <small class="text-muted">Nama Supplier</small>
                        <h5 class="fw-bold text-warning mt-1">
                            {{ $bpb->supplier->supp_mstr_name }}
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
                                    <th style="width:12%; text-align: center">Satuan</th>
                                    <th style="width:15%; text-align: center">@ Harga</th>
                                    <th style="width:15%; text-align: center">Batch</th>
                                    <th style="width:15%; text-align: center">Tanggal Exp</th>
                                    {{-- <th style="width:5%; text-align: center">Aksi</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($details as $detail)
                                    <tr>
                                        <td style="text-align:right;">{{ $loop->iteration }}</td>
                                        <td>{{ $detail->product->name }}</td>
                                        <td style="text-align:right;">{{ numfmt($detail->bpb_det_qty) }}</td>
                                        <td style="text-align:center;">{{ $detail->podet->um->name }}</td>
                                        <td style="text-align:right;">{{ rupiah($detail->bpb_det_price) }}</td>
                                        <td>{{ $detail->batch->batch_mstr_no }}</td>
                                        <td>{{ $detail->bpb_det_expired }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-dark btn-sm" type="button"
                        onclick="window.location.href='{{ route('BpbMstr.index') }}'">Back</button>
                </div>
            </div>
        </div>
    </div>



    @push('scripts')
        <script src="{{ 'assets/js/CustMstr/getData.js' }}"></script>
        <script src="{{ 'assets/js/alert.js' }}"></script>
    @endpush
</x-app-layout>
