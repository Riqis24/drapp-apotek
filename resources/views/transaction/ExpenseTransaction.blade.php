<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Expense Transaction</h3>
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
                        <table id="ExpenseTable" class="table table-striped table-bordered table-sm nowrap">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width:5%; text-align: center">No</th>
                                    <th style="width:12%; text-align: center">Date</th>
                                    <th style="width:10%; text-align: center">Type</th>
                                    {{-- <th style="text-align: center">Product</th> --}}
                                    <th style="width:10%; text-align: center">Description</th>
                                    <th style="width:15%; text-align: center">Amount</th>
                                    <th style="text-align: center">Source</th>
                                    {{-- <th style="width:15%;text-align: center">Customer</th> --}}
                                    {{-- <th style="width:5%; text-align: center">Aksi</th> --}}
                                    {{-- <th>Cust</th> --}}
                                    {{-- <th>Status</th> --}}
                                    {{-- <th>Aksi</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($records as $record)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $record->date }}</td>
                                        <td>{{ $record->type }}</td>
                                        <td>{{ $record->description }}</td>
                                        <td>{{ rupiah($record->amount) }}</td>
                                        <td>{{ $record->data_source }}</td>
                                        {{-- <td>
                                            @if ($record->source_type === \App\Models\CustTransactions::class && is_object($record->source))
                                                Penjualan ({{ $record->source->invoice_number ?? '-' }})
                                            @else
                                                {{ class_basename($record->source_type) }} #{{ $record->source_id }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($record->source_type === \App\Models\CustTransactions::class && is_object($record->source))
                                                {{ $record->source->customer->name ?? '-' }}
                                            @else
                                                -
                                            @endif
                                        </td> --}}

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
    <form action="{{ route('ExpenseTransaction.store') }}" method="POST">
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
                                <label for="name" class="form-label">Date</label>
                                <input type="date" class="form-control form-control-sm" name="date" required>
                            </div>
                            <div class="col-md-3">
                                <label for="address" class="form-label">Type</label>
                                <input type="text" class="form-control form-control-sm" name="type"
                                    value="expense" readonly required>
                            </div>
                            <div class="col-md-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-control form-control-sm select2" style="width:100%" name="category"
                                    required>
                                    <option value=""></option>
                                    <option value="Bayar Listrik">Bayar Listrik</option>
                                    <option value="Gaji Karyawan">Gaji Karyawan</option>
                                    <option value="Prive">Prive</option>
                                    <option value="Dll">Dll</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="text" class="form-control form-control-sm" name="amount" required>
                            </div>
                            <div class="col-md-12">
                                <label for="note" class="form-label">Remark</label>
                                <textarea name="note" id="note" class="form-control form-control-sm"></textarea>
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
        <script>
            $("#ExpenseTable").DataTable({
                scrollX: true, // Wajib untuk tabel lebar seperti ini
                scrollY: "350px",
                scrollCollapse: true,
                autoWidth: false, // MATIKAN agar kita bisa kontrol via CSS
                paging: true,

            });
        </script>
        <script src="{{ 'assets/js/alert.js' }}"></script>
    @endpush
</x-app-layout>
