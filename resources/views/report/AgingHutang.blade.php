<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Aging Hutang Supplier</h3>
        </div>
        <div class="page-content">
            <div class="card mb-3">

                <div class="card-body">
                    <form method="GET" action="{{ route('ApMstr.AgingHutang') }}" class="row g-3">
                        <div class="col-md-4">
                            <label>Supplier</label>
                            <select name="suppid" class="form-control">
                                <option value="">-- semua supplier --</option>
                                @foreach ($suppliers as $s)
                                    <option value="{{ $s->supp_mstr_id }}"
                                        {{ request('suppid') == $s->supp_mstr_id ? 'selected' : '' }}>
                                        {{ $s->supp_mstr_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-primary w-100">Tampilkan</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body table-responsive">

                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Supplier</th>
                                <th>No AP</th>
                                <th>Due Date</th>
                                <th class="text-end">Saldo</th>
                                <th class="text-end">Current</th>
                                <th class="text-end">0–30</th>
                                <th class="text-end">31–60</th>
                                <th class="text-end">&gt;60</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $r)
                                <tr>
                                    <td>{{ $r['supplier'] }}</td>
                                    <td>{{ $r['ap_no'] }}</td>
                                    <td>{{ $r['duedate'] }}</td>
                                    <td class="text-end">{{ rupiah($r['balance']) }}</td>
                                    <td class="text-end">{{ rupiah($r['bucket']['current']) }}</td>
                                    <td class="text-end text-warning">{{ rupiah($r['bucket']['0_30']) }}</td>
                                    <td class="text-end text-danger">{{ rupiah($r['bucket']['31_60']) }}</td>
                                    <td class="text-end text-danger fw-bold">
                                        {{ rupiah($r['bucket']['gt_60']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>



    @push('scripts')
        <script src="{{ 'assets/js/StockTr/getData.js' }}"></script>
        <script src="{{ 'assets/js/alert.js' }}"></script>
    @endpush
</x-app-layout>
