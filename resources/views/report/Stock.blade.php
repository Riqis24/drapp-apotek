<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Stock</h3>
        </div>
        <div class="page-content">
            <div class="card">
                {{-- <div class="card-header">
                    <button class="btn btn-outline-primary btn-sm rounded" type="button" data-bs-toggle="modal"
                        data-bs-target="#modalAddCustomer">
                        Add Cust
                    </button>
                </div> --}}
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="custTable" class="table table-striped table-bordered table-sm nowrap"
                            style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width:5%; text-align: center">No</th>
                                    <th style="text-align: center">Product</th>
                                    <th style="width:15%; text-align: center">Qty</th>
                                    <th style="width:15%; text-align: center">Location</th>
                                    <th style="width:15%; text-align: center">Batch Number</th>
                                    <th style="width:15%; text-align: center">Exp Date</th>
                                    <th style="width:5%; text-align: center">Aksi</th>
                                    {{-- <th>Cust</th> --}}
                                    {{-- <th>Status</th> --}}
                                    {{-- <th>Aksi</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stocks as $stock)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $stock->product->name }}</td>
                                        <td style="text-align: right">{{ numfmt($stock->quantity) }}</td>
                                        <td>{{ $stock->loc->loc_mstr_name }}</td>
                                        <td>{{ $stock->batch->batch_mstr_no }}</td>
                                        <td>{{ $stock->batch->batch_mstr_expireddate }}</td>
                                        <td style="text-align: center">
                                            <button type="button" class="btn btn-sm btn-info rounded"
                                                onclick="window.location.href='{{ route('stockHistory', $stock->id) }}'">
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
                    ini footer
                </div>
            </div>
        </div>
    </div>

    {{-- modal add user --}}
    <form action="{{ route('Stock.store') }}" method="POST">
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
