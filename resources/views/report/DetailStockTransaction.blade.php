<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Stock Transaction</h3>
        </div>
        <div class="page-content">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-outline-primary btn-sm rounded" type="button" data-bs-toggle="modal"
                        data-bs-target="#modalAddTransaction">
                        Add Transaction
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="custTable" class="table table-striped table-bordered table-sm nowrap"
                            style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width:5%; text-align: center">No</th>
                                    <th style="width:12%; text-align: center">Tanggal</th>
                                    <th style="width:20%; text-align: center">Product</th>
                                    <th style="width:12%; text-align: center">Type</th>
                                    <th style="width:15%; text-align: center">Qty</th>
                                    <th style="text-align: center">Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trStocks as $trStock)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $trStock->date }}</td>
                                        <td>{{ $trStock->product->name }}</td>
                                        <td>{{ $trStock->type }}</td>
                                        <td>{{ $trStock->quantity }}</td>
                                        <td>{{ $trStock->note }}</td>
                                        {{-- <td style="text-align: center">
                                            <button type="button" class="btn btn-sm btn-info rounded"
                                                onclick="window.location.href='{{ route('getDetailTransaction', $transaction->id) }}'">
                                                <i class="bi bi-folder"></i>
                                            </button>
                                        </td> --}}
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
    <form action="{{ route('StockTransaction.store') }}" method="POST">
        @csrf
        <div class="modal fade" id="modalAddTransaction" tabindex="-1" aria-labelledby="modalAddTransactionLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalAddTransactionLabel">Transaction</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="effdate" class="form-label">Tanggal</label>
                                <input type="date" class="form-control form-control-sm" name="effdate" required>
                            </div>
                            <div class="col-md-3">
                                <label for="address" class="form-label">Product</label>
                                <select name="product" class="form-control form-control-sm select2" style="width:100%"
                                    required>
                                    <option value=""></option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="type" class="form-label">Type</label>
                                <select name="type" class="select2" style="width:100%" id="type">
                                    <option value=""></option>
                                    <option value="in">In</option>
                                    <option value="out">out</option>
                                    <option value="adjustment">Adjustment</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="qty" class="form-label">Qty</label>
                                <input type="number" name="qty" class="form-control form-control-sm"
                                    step="0.00001">
                            </div>
                            <div class="col-md-12">
                                <label for="note" class="form-label">Note</label>
                                <textarea name="note" id="" class="form-control form-control-sm"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
        <script src="{{ 'assets/js/StockTr/getData.js' }}"></script>
        <script src="{{ 'assets/js/alert.js' }}"></script>
    @endpush
</x-app-layout>
