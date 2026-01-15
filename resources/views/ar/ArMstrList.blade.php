<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Piutang Usaha</h3>
        </div>
        <div class="page-content">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-outline-primary btn-sm rounded" type="button"
                        onclick="window.open('{{ route('ArpayMstr.create') }}', '_blank')">
                        <i class="bi bi-cash me-2"></i>Pay
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="ExpenseTable" class="table table-striped table-bordered table-sm nowrap"
                            style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>AR #</th>
                                    <th>Customer</th>
                                    <th>Tanggal</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Total</th>
                                    <th>Dibayar</th>
                                    <th>Sisa</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ars as $ar)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $ar->ar_mstr_nbr }}</td>
                                        <td>{{ $ar->customer->name }}</td>
                                        <td>{{ $ar->ar_mstr_date }}</td>
                                        <td>{{ $ar->ar_mstr_duedate }}</td>
                                        <td class="text-end">{{ rupiah($ar->ar_mstr_amount) }}</td>
                                        <td class="text-end">{{ rupiah($ar->ar_mstr_paid) }}</td>
                                        <td class="text-end">{{ rupiah($ar->ar_mstr_balance) }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $ar->ar_mstr_status == 'paid' ? 'success' : ($ar->ar_mstr_status == 'partial' ? 'warning' : 'danger') }}">
                                                {{ strtoupper($ar->ar_mstr_status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('ArpayMstr.show', $ar->ar_mstr_id) }}"
                                                class="btn btn-sm btn-primary"><i class="bi bi-folder"></i>

                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>




                </form>

            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ 'assets/js/ExpenseTr/getData.js' }}"></script>
        <script src="{{ 'assets/js/alert.js' }}"></script>
    @endpush
</x-app-layout>
