<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Sales Return</h3>
            <p class="text-muted">Sales No: {{ $sm->sales_mstr_nbr }}</p>
        </div>
        <div class="page-content">
            <div class="page-content">
                <div class="card  card-body">
                    <form action="{{ route('SrMstr.store') }}" method="POST">
                        @csrf


                        <input type="hidden" name="sales_id" value="{{ $sm->sales_mstr_id }}">


                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label>Tanggal Return</label>
                                <input type="date" name="sr_mstr_date" class="form-control" required>
                            </div>
                            <div class="col-md-8">
                                <label>Alasan Return</label>
                                <input type="text" name="sr_mstr_reason" class="form-control">
                            </div>
                        </div>


                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="returnTable">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th>Produk / Deskripsi</th>
                                        <th>Batch / Exp</th>
                                        <th width="10%">Qty Jual</th>
                                        <th width="10%">Sdh Return</th>
                                        <th width="10%">Sisa</th>
                                        <th width="15%">Qty Return</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sm->details as $i => $row)
                                        @php
                                            $isChildBundle = !is_null($row->sales_det_parentid);
                                            $isRacikan = !is_null($row->sales_det_pmid);
                                            // Jika racikan, stok tidak bisa kembali ke rak (Disable input)
                                            $canReturnStock = !$isRacikan;
                                        @endphp

                                        <tr
                                            class="{{ $isChildBundle ? 'table-light' : '' }} {{ $isRacikan ? 'bg-aliceblue' : '' }}">
                                            <td style="{{ $isChildBundle ? 'padding-left: 35px;' : '' }}">
                                                @if ($isChildBundle)
                                                    <i class="bi bi-arrow-return-right text-muted me-2"></i>
                                                @endif

                                                @if ($isRacikan)
                                                    <span class="fw-bold">
                                                        {{ $row->prescription->pres_mstr_name }}
                                                    </span>
                                                    <span class="badge bg-purple text-white ms-1">Racikan</span>
                                                    <small class="d-block text-danger fst-italic">*Return tidak menambah
                                                        stok fisik</small>
                                                @else
                                                    <span class="{{ !$isChildBundle ? 'fw-bold' : 'text-muted' }}">
                                                        {{ $row->product->name }}
                                                    </span>
                                                @endif


                                                @if ($isRacikan)
                                                    <span class="badge bg-primary text-white ms-1"
                                                        style="font-size: 0.7rem;">Racikan</span>
                                                @elseif($row->sales_det_type == 'bundle' && !$isChildBundle)
                                                    <span class="badge bg-info text-dark ms-1"
                                                        style="font-size: 0.7rem;">Bundle</span>
                                                @endif

                                                <input type="hidden" name="items[{{ $i }}][sales_det_id]"
                                                    value="{{ $row->sales_det_id }}">
                                            </td>

                                            <td class="small text-center">
                                                @if ($row->batch)
                                                    <span
                                                        class="d-block fw-bold">{{ $row->batch->batch_mstr_no }}</span>
                                                    <span class="text-muted" style="font-size: 0.75rem;">Exp:
                                                        {{ $row->batch->batch_mstr_expireddate }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="text-end fw-bold">{{ numfmt($row->sales_det_qty) }}</td>
                                            <td class="text-end text-danger">
                                                {{ numfmt($row->sales_det_qtyreturn) ?? 0 }}</td>
                                            <td class="text-end text-success">{{ numfmt($row->qty_remaining) }}</td>

                                            <td>
                                                {{-- Input Qty tetap ada untuk semua tipe --}}
                                                <input type="number" name="items[{{ $i }}][qty]"
                                                    class="form-control form-control-sm text-end {{ $isRacikan ? 'border-danger' : 'border-primary' }}"
                                                    min="0" max="{{ $row->qty_remaining }}" step="0.01"
                                                    value="0">
                                            </td>
                                        </tr>

                                        {{-- Detail Bahan Racikan (Read Only - Just for Info) --}}
                                        @if ($isRacikan && $row->prescription)
                                            @foreach ($row->prescription->details as $bahan)
                                                <tr style="font-size: 0.8rem;" class="bg-white">
                                                    <td colspan="6" class="ps-5 text-muted fst-italic">
                                                        ↳ {{ $bahan->product->name }}
                                                        <small>({{ numfmt($bahan->pres_det_qty) }}
                                                            {{ $bahan->measurement->name }})</small>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>


                        <div class="mt-3 text-end">
                            <button class="btn btn-danger">Process Return</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ 'assets/js/CustMstr/getData.js' }}"></script>
        <script src="{{ 'assets/js/alert.js' }}"></script>
    @endpush
</x-app-layout>
