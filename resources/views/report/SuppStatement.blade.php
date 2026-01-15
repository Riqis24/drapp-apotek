<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Supplier Statement</h3>
        </div>
        <div class="page-content">
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" action="{{ route('ApMstr.SuppStatement') }}" class="row g-3">

                        <div class="col-md-4">
                            <label>Supplier</label>
                            <select name="suppid" class="form-control" required>
                                <option value="">-- pilih supplier --</option>
                                @foreach ($suppliers as $s)
                                    <option value="{{ $s->supp_mstr_id }}"
                                        {{ request('suppid') == $s->supp_mstr_id ? 'selected' : '' }}>
                                        {{ $s->supp_mstr_code }} | {{ $s->supp_mstr_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Dari Tanggal</label>
                            <input type="date" name="from" class="form-control" value="{{ request('from') }}"
                                required>
                        </div>

                        <div class="col-md-3">
                            <label>Sampai</label>
                            <input type="date" name="to" class="form-control" value="{{ request('to') }}"
                                required>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-primary w-100">Tampilkan</button>
                        </div>
                    </form>
                </div>
            </div>

            @if (count($rows))
                <div class="card">
                    <div class="card-body">

                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Dokumen</th>
                                    <th>Tipe</th>
                                    <th class="text-end">Debit</th>
                                    <th class="text-end">Kredit</th>
                                    <th class="text-end">Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="fw-bold">
                                    <td colspan="5">Opening Balance</td>
                                    <td class="text-end">
                                        {{ rupiah($openingBalance) }}
                                    </td>
                                </tr>

                                @foreach ($rows as $r)
                                    <tr>
                                        <td>{{ $r->trx_date }}</td>
                                        <td>{{ $r->doc_no }}</td>
                                        <td>{{ $r->trx_type }}</td>
                                        <td class="text-end">{{ rupiah($r->debit) }}</td>
                                        <td class="text-end">{{ rupiah($r->credit) }}</td>
                                        <td class="text-end">{{ rupiah($r->balance) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            @endif
        </div>
    </div>



    @push('scripts')
        <script src="{{ 'assets/js/StockTr/getData.js' }}"></script>
        <script src="{{ 'assets/js/alert.js' }}"></script>
    @endpush
</x-app-layout>
