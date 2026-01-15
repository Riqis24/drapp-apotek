<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Customer Transaction</h3>
        </div>
        <div class="page-content">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="custTable" class="table table-striped table-bordered table-sm nowrap"
                            style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width:5%; text-align: center">No</th>
                                    <th style="width:14%; text-align: center">Invoice #</th>
                                    <th style="text-align: center">Nama</th>
                                    <th style="width:12%; text-align: center">Tanggal</th>
                                    <th style="width:12%; text-align: center">Status</th>
                                    <th style="width:13%; text-align: center">Total</th>
                                    <th style="width:13%; text-align: center">Bayar</th>
                                    <th style="width:13%; text-align: center">Kembali</th>
                                    <th style="width:13%; text-align: center">Hutang</th>
                                    <th style="width:8%; text-align: center">Status</th>
                                    <th style="width:5%; text-align: center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    @php
                                        if ($transaction->status == '0') {
                                            $status = 'open';
                                            $color = 'text-bg-warning';
                                        } elseif ($transaction->status == '1') {
                                            $status = 'completed';
                                            $color = 'text-bg-success';
                                        } else {
                                            $status = 'unknown';
                                            $color = 'text-bg-danger';
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $transaction->invoice_number }}</td>
                                        <td>{{ $transaction->customer->name }}</td>
                                        <td>{{ $transaction->date }}</td>
                                        <td>{{ $transaction->method_payment }}</td>
                                        <td style="text-align: right">{{ rupiah($transaction->total) }}</td>
                                        <td style="text-align: right">{{ rupiah($transaction->paid) }}</td>
                                        <td style="text-align: right">{{ rupiah($transaction->change) }}</td>
                                        <td style="text-align: right">{{ rupiah($transaction->hutang) }}</td>
                                        <td style="text-align: center">
                                            <span class="badge {{ $color }}">{{ $status }}</span>
                                        </td>
                                        <td style="text-align: center">
                                            <button type="button" class="btn btn-sm btn-info rounded"
                                                onclick="window.location.href='{{ route('getDetailTransaction', $transaction->id) }}'">
                                                <i class="bi bi-folder"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-success rounded"
                                                onclick="window.location.href='{{ route('CustTransaction.show', $transaction->id) }}'">
                                                <i class="bi bi-cash-coin"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ 'assets/js/CustMstr/getData.js' }}"></script>
        <script src="{{ 'assets/js/alert.js' }}"></script>
    @endpush
</x-app-layout>
