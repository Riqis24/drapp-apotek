<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Utang Usaha</h3>
        </div>
        <div class="page-content">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-outline-primary btn-sm rounded" type="button"
                        onclick="window.open('{{ route('AppayMstr.create') }}', '_blank')">
                        <i class="bi bi-cash me-2"></i>Pay
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="ApTable" class="table table-striped table-bordered table-sm nowrap">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>AP #</th>
                                    <th>Supplier</th>
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
                                @foreach ($aps as $ap)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $ap->ap_mstr_nbr }}</td>
                                        <td>{{ $ap->supplier->supp_mstr_name }}</td>
                                        <td>{{ $ap->ap_mstr_date }}</td>
                                        <td>{{ $ap->ap_mstr_duedate }}</td>
                                        <td class="text-end">{{ rupiah($ap->ap_mstr_amount) }}</td>
                                        <td class="text-end">{{ rupiah($ap->ap_mstr_paid) }}</td>
                                        <td class="text-end">{{ rupiah($ap->ap_mstr_balance) }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $ap->ap_mstr_status == 'paid' ? 'success' : ($ap->ap_mstr_status == 'partial' ? 'warning' : 'danger') }}">
                                                {{ strtoupper($ap->ap_mstr_status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('ApMstr.show', $ap->ap_mstr_id) }}"
                                                class="btn btn-sm btn-info"><i class="bi bi-folder"></i>
                                            </a>

                                        </td>
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
        <script>
            $("#ApTable").DataTable({
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
