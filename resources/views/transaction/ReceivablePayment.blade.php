<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Receivable Payment</h3>
        </div>
        <div class="page-content">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-outline-primary btn-sm rounded" type="button" data-bs-toggle="modal"
                        data-bs-target="#modalAddTransaksi">
                        Add Transaction
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="rcvPaymentTable" class="table table-striped table-bordered table-sm nowrap"
                            style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width:5%; text-align: center">No</th>
                                    <th style="width:12%; text-align: center">Tanggal</th>
                                    <th style="text-align: center">Invoice#</th>
                                    <th style="text-align: center">Name</th>
                                    <th style="width:15%; text-align: center">Bayar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $transaction->date }}</td>
                                        <td>{{ $transaction->custtr->invoice_number }}</td>
                                        <td>{{ $transaction->customer->name }}</td>
                                        <td>{{ rupiah($transaction->amount_paid) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- <div class="card-footer">
                  ini footer
              </div> --}}
            </div>
        </div>
    </div>

    {{-- modal add user --}}
    <form action="{{ route('ReceivablePayment.store') }}" method="POST">
        @csrf
        <div class="modal fade" id="modalAddTransaksi" tabindex="-1" aria-labelledby="modalAddTransaksiLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalAddTransaksiLabel">Transaksi</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="dynamicCustsInputs">
                            <div class="cust-row">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="date" class="form-label">Date</label>
                                        <input type="date" class="form-control form-control-sm" name="date"
                                            required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="customer" class="form-label">Customer</label>
                                        <select name="customer" class="form-control form-control-sm select2"
                                            style="width:100%" id="customer">
                                            <option value=""></option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="amount" class="form-label">Amount</label>
                                        <input type="text" class="form-control form-control-sm" name="amount"
                                            required>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <label for="note" class="form-label">Remark</label>
                                        <textarea name="note" id="note" class="form-control form-control-sm"></textarea>
                                    </div>
                                </div>
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
        <script src="{{ 'assets/js/RcvPayment/getData.js' }}"></script>
        <script src="{{ 'assets/js/alert.js' }}"></script>
    @endpush
</x-app-layout>
