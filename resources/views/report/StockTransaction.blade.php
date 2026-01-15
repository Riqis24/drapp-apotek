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
                                    <th style="width:20%; text-align: center">Batch</th>
                                    <th style="width:20%; text-align: center">Location</th>
                                    <th style="width:12%; text-align: center">Type</th>
                                    <th style="width:15%; text-align: center">Qty</th>
                                    <th style="text-align: center">Note</th>
                                    {{-- <th style="width:5%; text-align: center">Aksi</th> --}}
                                    {{-- <th>Cust</th> --}}
                                    {{-- <th>Status</th> --}}
                                    {{-- <th>Aksi</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trStocks as $trStock)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $trStock->created_at }}</td>
                                        <td>{{ $trStock->product->name }}</td>
                                        <td>{{ $trStock->batch->batch_mstr_no }}</td>
                                        <td>{{ $trStock->location->loc_mstr_name }}</td>
                                        <td>{{ $trStock->type }}</td>
                                        <td>{{ $trStock->quantity }}</td>
                                        <td>{{ $trStock->source_type }}</td>
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
    </div>


    @push('scripts')
        <script src="{{ 'assets/js/StockTr/getData.js' }}"></script>
        <script src="{{ 'assets/js/alert.js' }}"></script>
    @endpush
</x-app-layout>
