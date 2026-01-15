<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Product Transaction</h3>
        </div>
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body px-5 py-4">
                @php
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
                @endphp

                <div class="text-center">
                    <small class="text-muted">No. Invoice</small>
                    <h5 class="fw-bold text-dark mb-0">{{ $transaction->invoice_number }}

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
                        <ul class="dropdown-menu">
                            <li class="dropdown-item">
                                <a href="{{ route('StoreProfile.printInvoice', $id) }}">Invoice</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ route('StoreProfile.printNota', $id) }}">Nota</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-md-3 mb-3 mb-md-0">
                        <small class="text-muted">Nama Customer</small>
                        <h5 class="fw-semibold text-primary mt-1">
                            {{ $transaction->customer->name ?? 'Umum' }}
                        </h5>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <small class="text-muted">Total Transaksi</small>
                        <h5 class="fw-semibold text-success mt-1">
                            {{ rupiah($transaction->total) }}
                        </h5>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <small class="text-muted">Jumlah Dibayar</small>
                        <h5 class="fw-semibold text-warning mt-1">
                            {{ rupiah($transaction->paid) }}
                        </h5>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <small class="text-muted">Metode Pembayaran</small>
                        <h5 class="fw-semibold text-secondary mt-1">
                            {{ $transaction->method_payment }}
                        </h5>
                    </div>

                </div>
            </div>
        </div>

        <div class="page-content">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-outline-primary btn-sm rounded" type="button" data-bs-toggle="modal"
                        data-bs-target="#modalAddCustomer">
                        Add Cust
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="custTable" class="table table-striped table-bordered table-sm nowrap"
                            style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width:5%; text-align: center">No</th>
                                    <th style="text-align: center">Nama</th>
                                    <th style="width:12%; text-align: center">Satuan</th>
                                    <th style="width:12%; text-align: center">Qty</th>
                                    <th style="width:15%; text-align: center">@ Harga</th>
                                    <th style="width:15%; text-align: center">Sub Total</th>
                                    <th style="width:5%; text-align: center">Aksi</th>
                                    {{-- <th>Cust</th> --}}
                                    {{-- <th>Status</th> --}}
                                    {{-- <th>Aksi</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($details as $detail)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $detail->product->name }}</td>
                                        <td>{{ $detail->measurement->name }}</td>
                                        <td>{{ $detail->quantity }}</td>
                                        <td>{{ rupiah($detail->unit_price) }}</td>
                                        <td>{{ rupiah($detail->subtotal) }}</td>
                                        <td style="text-align: center">
                                            <button type="button" class="btn btn-sm btn-info rounded"
                                                onclick="window.location.href=''">
                                                <i class="bi bi-folder"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-dark btn-sm" type="button"
                        onclick="window.location.href='{{ route('CustTransaction.index') }}'">Back</button>
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
