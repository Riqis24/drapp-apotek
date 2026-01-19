<x-app-layout>
    <div id="main" class="min-vh-100">
        <header class="mb-3 p-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>

        <div class="page-heading">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold mb-0 text-dark">
                            {{ $product->name ?? 'Produk Tidak Ditemukan' }}
                        </h3>
                        <p class="text-muted small mb-0">
                            {{ $product->description ?? '-' }} ({{ $product->code ?? '-' }})
                        </p>
                    </div>

                    <div>
                        <a href="{{ route('StockTransaction.StockCard') }}"
                            class="btn btn-sm btn-outline-danger fw-bold">
                            Ganti Produk
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-content">
            <div class="row mb-4 g-3">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm text-center p-4">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <h6 class="text-primary text-uppercase small fw-bold mb-3">Total Stok Fisik</h6>
                            <h1 class="display-4 fw-bold text-dark mb-0">
                                {{ number_format($stockAct->sum('quantity') + $stockNonAct->sum('quantity'), 0) }}
                            </h1>
                            <span class="text-muted fs-5">{{ $product->measurement->name }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card h-100 border-0 shadow-sm p-3">
                        <div class="card-header bg-white border-0 pb-0">
                            <h6 class="text-muted text-uppercase small fw-bold border-bottom">Rincian Status Stok</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted"><i class="bi bi-check-circle-fill text-success me-2"></i>Bisa
                                    Dijual (Aktif)</small>
                                <span class="fw-bold text-success">{{ $stockAct->sum('quantity') }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted"><i
                                        class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Expired /
                                    Rusak</small>
                                <span class="fw-bold text-danger">{{ $stockNonAct->sum('quantity') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="fw-bold mb-0">Riwayat Pergerakan Stok (Mutasi)</h5>
                </div>
                <div class="card-body">
                    <table id="ProductTable" class="table align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="text-muted small">
                                <th class="ps-4">TANGGAL</th>
                                <th>LOKASI</th>
                                <th>KETERANGAN</th>
                                <th class="text-center">MASUK/KELUAR</th>
                                <th class="pe-4 text-end">SISA BATCH</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $st)
                                @php
                                    $itemInfo = $detailsMap[$st->id] ?? ['price' => 0, 'total' => 0];
                                    $referenceNbr = match ($st->source_type) {
                                        \App\Models\SalesMstr::class => $st->source?->sales_mstr_nbr,
                                        \App\Models\SrMstr::class => $st->source?->sr_mstr_nbr,
                                        \App\Models\BpbMstr::class => $st->source?->bpb_mstr_nbr,
                                        \App\Models\PrMstr::class => $st->source?->pr_mstr_nbr,
                                        \App\Models\SaMstr::class => $st->source?->sa_mstr_nbr,
                                        \App\Models\SoMstr::class => $st->source?->so_mstr_nbr,
                                        default => '-',
                                    };
                                    $route =
                                        match ($st->source_type) {
                                            \App\Models\SalesMstr::class => 'SalesMstr.show',
                                            \App\Models\BpbMstr::class => 'BpbMstr.show',
                                            \App\Models\SaMstr::class => 'SaMstr.show',
                                            \App\Models\PrMstr::class => 'PrMstr.show',
                                            \App\Models\SrMstr::class => 'SrMstr.show',
                                            \App\Models\TsMstr::class => 'TsMstr.show',
                                            default => '-',
                                        } ?? '-';
                                    $stockBatch = DB::table('stocks')
                                        ->where('loc_id', $st->loc_id)
                                        ->where('batch_id', $st->batch_id)
                                        ->where('product_id', $st->product_id)
                                        ->value('quantity');
                                @endphp
                                <tr class="border-bottom">
                                    <td class="ps-4 py-3">
                                        <div class="fw-bold text-dark">{{ $st->created_at->format('d M Y H:i') }}
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="text-primary fw-bold small text-uppercase">{{ $st->location->loc_mstr_name ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark mb-1">
                                            {{ str_replace('App\Models\\', '', $st->source_type) }}
                                            <a href="{{ route($route, $st->source_id) }}">({{ $referenceNbr }})</a>
                                        </div>
                                        <div class="text-muted x-small">
                                            Batch: {{ $st->batch->batch_mstr_no ?? '-' }} | ED:
                                            {{ $st->batch->batch_mstr_expireddate ?? '-' }}
                                        </div>
                                        <div class="text-muted x-small">Oleh:
                                            {{ $st->user->user_mstr_name ?? 'system' }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge rounded-pill {{ $st->type == 'in' ? 'bg-success-light text-success' : 'bg-danger-light text-danger' }} px-3 py-2">
                                            {{ $st->type == 'in' ? '+' : '' }}{{ (float) $st->quantity }}
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end fw-bold text-dark">
                                        {{ numfmt($stockBatch) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .x-small {
            font-size: 8pt;
        }

        .bg-success-light {
            background-color: #e8fadf;
            color: #28a745;
        }

        .bg-danger-light {
            background-color: #fbe9e9;
            color: #dc3545;
        }

        .card {
            border-radius: 12px;
        }
    </style>
    @push('scripts')
        <script>
            $("#ProductTable").DataTable({
                scrollX: true, // Wajib untuk tabel lebar seperti ini
                scrollY: "350px",
                scrollCollapse: true,
                autoWidth: false, // MATIKAN agar kita bisa kontrol via CSS
                paging: true,

            });
        </script>
    @endpush

</x-app-layout>
