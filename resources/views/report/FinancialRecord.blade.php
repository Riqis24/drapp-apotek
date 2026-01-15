<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Financial Record</h3>
            <p class="lead text-muted">Laporan arus kas pemasukan dan pengeluaran</p>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title">RESULT</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="card bg-success text-white shadow rounded">
                            <div class="card-body d-flex align-items-center">
                                <div class="me-3">
                                    <i class="bi bi-cash-stack fs-1"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">Total Pemasukan</h5>
                                    <h4 class="card-text" style="color:white">Rp
                                        {{ number_format($totalIncome, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card bg-danger text-white shadow rounded">
                            <div class="card-body d-flex align-items-center">
                                <div class="me-3">
                                    <i class="bi bi-cart-x fs-1"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">Total Pengeluaran</h5>
                                    <h4 class="card-text" style="color:white">Rp
                                        {{ number_format($totalExpense, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card bg-primary text-white shadow rounded">
                            <div class="card-body d-flex align-items-center">
                                <div class="me-3">
                                    <i class="bi bi-wallet2 fs-1"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">Saldo</h5>
                                    <h4 class="card-text" style="color:white">Rp
                                        {{ number_format($saldo, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <form action="{{ route('FinancialRecord.index') }}" method="GET">
            <div class="page-content">
                <!-- Filter Bar -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label for="start-date" class="form-label">Tanggal Awal</label>
                        <input type="date" name="date1" id="start-date" class="form-control"
                            value="{{ request('date1') }}" />
                    </div>
                    <div class="col-md-3">
                        <label for="end-date" class="form-label">Tanggal Akhir</label>
                        <input type="date" name="date2" id="end-date" class="form-control"
                            value="{{ request('date2') }}" />
                    </div>
                    <div class="col-md-2">
                        <label for="transaction-type" class="form-label">Jenis</label>
                        <select id="transaction-type" name="type" class="form-select">
                            <option value="" {{ request('type') == '' ? 'selected' : '' }}>Semua</option>
                            <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Pemasukan
                            </option>
                            <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Pengeluaran
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        {{-- <button class="btn btn-outline-primary btn-sm rounded" type="button" data-bs-toggle="modal"
                        data-bs-target="#modalAddTransaction">
                        Add Transaction
                    </button> --}}
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="custTable" class="table table-striped table-bordered table-sm nowrap"
                                style="width:100%">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width:5%; text-align: center">No</th>
                                        <th style="width:12%; text-align: center">Date</th>
                                        <th style="width:10%; text-align: center">Type</th>
                                        <th style="width:10%; text-align: center">Description</th>
                                        <th style="width:15%; text-align: center">Amount</th>
                                        <th style="text-align: center">Source</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($records as $record)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $record->date }}</td>
                                            <td>{{ $record->type }}</td>
                                            <td>{{ $record->data_source }}</td>
                                            <td>{{ rupiah($record->amount) }}</td>
                                            <td>
                                                @if ($record->source_type === \App\Models\SalesMstr::class && is_object($record->source))
                                                    Penjualan ({{ $record->source->sales_mstr_nbr ?? '-' }})
                                                @else
                                                    {{ class_basename($record->source_type) }}
                                                    #{{ $record->source_id }}
                                                @endif
                                            </td>


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

                </div>
            </div>
        </form>
    </div>

    {{-- modal add user --}}
    <form action="{{ route('Stock.store') }}" method="POST">
        @csrf
        <div class="modal fade" id="modalAddTransaction" tabindex="-1" aria-labelledby="modalAddTransactionLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalAddTransactionLabel">Cust Master</h1>
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
