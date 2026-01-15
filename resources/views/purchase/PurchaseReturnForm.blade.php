<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Purchase Return</h3>
            <p class="text-muted">BPB No: {{ $bpb->bpb_mstr_nbr }}</p>
        </div>
        <div class="page-content">
            <div class="card  card-body">
                <form action="{{ route('PrMstr.store') }}" method="POST">
                    @csrf


                    <input type="hidden" name="bpb_id" value="{{ $bpb->bpb_mstr_id }}">


                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>Tanggal Return</label>
                            <input type="date" name="pr_mstr_date" class="form-control" required>
                        </div>
                        <div class="col-md-8">
                            <label>Alasan Return</label>
                            <input type="text" name="pr_mstr_reason" class="form-control">
                        </div>
                    </div>


                    <div class="table-responsive">
                        <table class="table table-bordered" id="returnTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Batch</th>
                                    <th>Expired</th>
                                    <th class="text-end">Qty BPB</th>
                                    <th class="text-end">Sudah Return</th>
                                    <th class="text-end">Sisa</th>
                                    <th class="text-end">Qty Return</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bpb->details as $i => $row)
                                    <tr>
                                        <td>
                                            {{ $row->product->name }}
                                            <input type="hidden" name="items[{{ $i }}][bpb_det_id]"
                                                value="{{ $row->bpb_det_id }}">
                                        </td>
                                        <td>{{ $row->bpb_det_batch }}</td>
                                        <td>{{ $row->bpb_det_expired }}</td>
                                        <td class="text-end">{{ $row->bpb_det_qty }}</td>
                                        <td class="text-end">{{ $row->qty_returned }}</td>
                                        <td class="text-end">{{ $row->qty_remaining }}</td>
                                        <td>
                                            <input type="number" name="items[{{ $i }}][qty]"
                                                class="form-control text-end" min="0"
                                                max="{{ $row->qty_remaining }}" step="0.01" value="0">
                                        </td>
                                    </tr>
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

    @push('scripts')
        <script src="{{ url('assets/js/alert.js') }}"></script>
    @endpush

</x-app-layout>
