<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Details Retur Penjualan</h3>
        </div>

        {{-- Alert Area --}}
        <div class="px-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body px-5 py-4">
                <div class="text-center mb-3">
                    <small class="text-muted">No. Retur Penjualan</small>
                    <h5 class="fw-bold text-dark mb-0">{{ $sr->sr_mstr_nbr }}</h5>
                </div>
                <div class="row text-center">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <small class="text-muted">Nama Pelanggan/Pasien</small>
                        <h5 class="fw-bold text-primary mt-1">
                            {{-- Sesuaikan relasi customer di model SR --}}
                            {{ $sr->sales->customer->cust_mstr_name ?? 'Umum' }}
                        </h5>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <small class="text-muted">Referensi Invoice</small>
                        <h5 class="fw-bold text-info mt-1">
                            {{ $sr->sales->sales_mstr_nbr }}
                        </h5>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <small class="text-muted">Tanggal Retur</small>
                        <h5 class="fw-bold text-warning mt-1">
                            {{ date('d/m/Y', strtotime($sr->sr_mstr_date)) }}
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
                                    <th style="text-align: center">Nama Produk</th>
                                    <th style="width:12%; text-align: center">Qty Retur</th>
                                    <th style="width:12%; text-align: center">Satuan</th>
                                    <th style="width:15%; text-align: center">@ Harga Jual</th>
                                    <th style="text-align: center">Batch</th>
                                    <th style="width:15%; text-align: center">Sub Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sr->details as $detail)
                                    <tr>
                                        <td style="text-align:center;">{{ $loop->iteration }}</td>
                                        <td>{{ $detail->product->name }}</td>
                                        <td style="text-align:right;">{{ numfmt($detail->sr_det_qty) }}</td>
                                        <td style="text-align:center;">{{ $detail->measurement->name ?? '-' }}</td>
                                        <td style="text-align:right;">{{ rupiah($detail->sr_det_price) }}</td>
                                        <td style="text-align:center;">
                                            {{-- Menampilkan No Batch dan Expired --}}
                                            {{ $detail->batch->batch_mstr_no ?? '-' }}
                                            <small
                                                class="text-muted">({{ $detail->batch->batch_mstr_expireddate ?? '-' }})</small>
                                        </td>
                                        <td style="text-align:right;">{{ rupiah($detail->sr_det_subtotal) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="6" style="text-align: right">Grand Total</th>
                                    <th style="text-align: right">{{ rupiah($sr->details->sum('sr_det_subtotal')) }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <button class="btn btn-dark btn-sm" type="button"
                        onclick="window.location.href='{{ route('SrMstr.index') }}'">
                        <i class="bi bi-arrow-left"></i> Back
                    </button>
                    {{-- Tombol Cetak jika diperlukan --}}
                    <button class="btn btn-primary btn-sm" onclick="window.print()">
                        <i class="bi bi-printer"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- Pastikan script alert.js menangani session Laravel --}}
        <script src="{{ asset('assets/js/alert.js') }}"></script>
    @endpush
</x-app-layout>
