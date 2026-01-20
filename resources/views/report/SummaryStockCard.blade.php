<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading px-4">
            <h3 class="fw-bold mb-3">Rekapitulasi Stock Card</h3>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Filter Periode & Lokasi</h5>
                    <form action="{{ route('SummaryStockCard') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="small text-muted fw-bold">Dari Tanggal</label>
                                <input type="datetime-local" name="from_date" class="form-control"
                                    value="{{ $fromDate }}">
                            </div>
                            <div class="col-md-3">
                                <label class="small text-muted fw-bold">Sampai Tanggal</label>
                                <input type="datetime-local" name="to_date" class="form-control"
                                    value="{{ $toDate }}">
                            </div>
                            <div class="col-md-3">
                                <label class="small text-muted fw-bold">Lokasi</label>
                                <select name="loc_id" class="form-select">
                                    <option value="">-- Semua Lokasi --</option>
                                    @foreach ($locations as $loc)
                                        <option value="{{ $loc->loc_mstr_id }}"
                                            {{ $locId == $loc->loc_mstr_id ? 'selected' : '' }}>
                                            {{ $loc->loc_mstr_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">Tampilkan Laporan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="text-muted small uppercase" style="background-color: #f8f9fa;">
                            <tr>
                                <th class="ps-4 py-3" style="width: 30%;">PRODUK</th>
                                <th class="text-center" style="background-color: #e9ecef; width: 12%;">SALDO AWAL</th>
                                <th class="text-center" style="background-color: #e8fadf; width: 12%; color: #198754;">
                                    MASUK</th>
                                <th class="text-center" style="background-color: #fbe9e9; width: 12%; color: #dc3545;">
                                    KELUAR</th>
                                <th class="text-center" style="background-color: #e7f1ff; width: 15%; color: #0d6efd;">
                                    SALDO AKHIR</th>
                                <th class="pe-4 text-center" style="width: 10%;">SATUAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $p)
                                @php
                                    // Logika perhitungan (sebaiknya dikirim dari controller)
                                    $masuk = $p->masuk ?? 0;
                                    $keluar = $p->keluar ?? 0;
                                    $saldo_awal = $p->saldo_awal; // Anda perlu query terpisah untuk ini
                                    $saldo_akhir = $saldo_awal + $masuk + $keluar;
                                @endphp
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="fw-bold text-dark">{{ $p->product_name }}</div>
                                        <small class="text-muted">{{ $p->description }}</small>
                                    </td>
                                    <td class="text-center fw-bold text-secondary bg-light">
                                        {{ number_format($saldo_awal, 0, ',', '.') }}</td>
                                    <td class="text-center fw-bold text-success" style="background-color: #f4fcf0;">
                                        {{ $masuk > 0 ? '+' . number_format($masuk, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="text-center fw-bold text-danger" style="background-color: #fff5f5;">
                                        {{ $keluar < 0 ? '' . number_format($keluar, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="text-center fw-bold text-primary"
                                        style="background-color: #f0f7ff; font-size: 1.1rem;">
                                        {{ number_format($saldo_akhir, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center text-muted pe-4">{{ $p->unit ?? 'Pcs' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4 ms-2">
                <p class="small text-muted mb-1">* <strong>Masuk:</strong> Pembelian, Retur Penjualan, Transfer Masuk,
                    Penyesuaian (Positif).</p>
                <p class="small text-muted">* <strong>Keluar:</strong> Penjualan, Retur Pembelian, Transfer Keluar,
                    Penyesuaian (Negatif).</p>
            </div>
        </div>


    </div>


    <style>
        .table thead th {
            font-weight: 700;
            font-size: 11px;
            letter-spacing: 0.5px;
            border: none;
        }

        .table tbody td {
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
        }

        .bg-light-blue {
            background-color: #f0f7ff;
        }
    </style>
    @push('scripts')
        <script src="{{ 'assets/js/ProductMstr/getData.js' }}"></script>
        <script src="{{ 'assets/js/alert.js' }}"></script>
    @endpush
</x-app-layout>
