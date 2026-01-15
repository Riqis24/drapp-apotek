<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>History</h3>
        </div>
        <div class="page-content">
            <div class="card">
                <div class="card-header">

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="historyTable" class="table table-striped table-bordered table-sm nowrap"
                            style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th style="text-align: center">No</th>
                                    <th style="text-align: center">Tanggal</th>
                                    <th style="text-align: center">Product</th>
                                    <th style="text-align: center">Kategori</th>
                                    <th style="text-align: center">Location</th>
                                    <th style="text-align: center">Batch No</th>
                                    <th style="text-align: center">Exp Date</th>
                                    <th style="text-align: center">Type</th>
                                    <th style="text-align: center">Qty</th>
                                    <th style="text-align: center">Price</th>
                                    <th style="text-align: center">Sub Total</th>
                                    <th style="text-align: center">Cust/Vend</th>
                                    <th style="text-align: center">Reference</th>
                                    <th style="text-align: center">Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $index => $st)
                                    @php
                                        $lookupKey = "{$st->source_type}_{$st->source_id}";

                                        // Ambil data dari Map yang dikirim dari Controller
                                        $itemInfo = $detailsMap[$lookupKey] ?? null;
                                        // dump($itemInfo);
                                        // AMAN: Cek dulu apakah $itemInfo ada, baru akses 'price'
                                        // Jika $itemInfo null ATAU 'price' tidak ada, gunakan harga beli master
                                        $unitPrice = $itemInfo && isset($itemInfo['price']) ? $itemInfo['price'] : 0;

                                        $subtotal = $itemInfo
                                            ? $itemInfo['total']
                                            : $itemInfo['price'] * $st->quantity ?? 0;

                                        $isHpp = !$itemInfo;

                                        // Logika Note
                                        $formNote =
                                            match ($st->source_type) {
                                                \App\Models\SalesMstr::class => $st->source?->sales_mstr_note,
                                                \App\Models\BpbMstr::class => $st->source?->bpb_mstr_note,
                                                \App\Models\SrMstr::class => $st->source?->sr_mstr_reason,
                                                \App\Models\PrMstr::class => $st->source?->pr_mstr_reason,
                                                \App\Models\SaMstr::class => $st->source?->sa_mstr_reason,
                                                \App\Models\TsMstr::class => $st->source?->ts_mstr_note,
                                                default => $st->note,
                                            } ?? '-';
                                        $referenceNbr =
                                            match ($st->source_type) {
                                                \App\Models\SalesMstr::class => $st->source?->sales_mstr_nbr,
                                                \App\Models\BpbMstr::class => $st->source?->bpb_mstr_nbr,
                                                \App\Models\SaMstr::class => $st->source?->sa_mstr_nbr,
                                                \App\Models\PrMstr::class => $st->source?->pr_mstr_nbr,
                                                \App\Models\SrMstr::class => $st->source?->sr_mstr_nbr,
                                                \App\Models\TsMstr::class => $st->source?->ts_mstr_nbr,
                                                default => '-',
                                            } ?? '-';
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $transactions->firstItem() + $index }}</td>
                                        <td class="text-center">{{ $st->created_at }}</td>
                                        <td>
                                            {{ $st->product->name }}
                                            @if ($isHpp)
                                                <small class="text-muted d-block" style="font-size: 7pt;">(HPP)</small>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">
                                                {{ str_replace('App\Models\\', '', $st->source_type) }}
                                            </span>
                                        </td>
                                        <td>{{ $st->location->loc_mstr_name ?? '-' }}</td>
                                        <td class="text-center">{{ $st->batch->batch_mstr_no ?? '-' }}</td>
                                        <td class="text-center">{{ $st->batch->batch_mstr_expireddate ?? '-' }}</td>
                                        <td class="text-center">
                                            <span class="badge {{ $st->type == 'in' ? 'bg-success' : 'bg-danger' }}">
                                                {{ strtoupper($st->type) }}
                                            </span>
                                        </td>
                                        <td class="text-end fw-bold">{{ (float) $st->quantity }}</td>
                                        <td class="text-end">{{ number_format($unitPrice, 0, ',', '.') }}</td>
                                        <td class="text-end">{{ number_format($subtotal, 0, ',', '.') }}</td>
                                        <td>
                                            @if ($st->source_type == \App\Models\SalesMstr::class)
                                                {{ $st->source->customer->name ?? 'Umum' }}
                                            @elseif ($st->source_type == \App\Models\SrMstr::class)
                                                {{ $st->source->customer->name ?? 'Umum' }}
                                            @elseif($st->source_type == \App\Models\BpbMstr::class)
                                                {{ $st->source->supplier->supp_mstr_name ?? '-' }}
                                            @elseif($st->source_type == \App\Models\PrMstr::class)
                                                {{ $st->source->supplier->supp_mstr_name ?? '-' }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="text-primary fw-bold">{{ $referenceNbr ?? '-' }}</span>
                                        </td>
                                        <td class="small">{{ $formNote ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                        <button type="button" class="btn btn-dark"
                            onclick="window.location.href='{{ route('Stock.index') }}'">
                            Back
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            $("#historyTable").DataTable({
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
