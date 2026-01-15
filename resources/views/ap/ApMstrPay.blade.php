<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Detail Payment</h3>
        </div>
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body px-5 py-4">
                @php
                    if ($transaction->ap_mstr_status == 'partial') {
                        $status = 'partial';
                        $color = 'warning';
                    } elseif ($transaction->ap_mstr_status == 'paid') {
                        $status = 'paid';
                        $color = 'success';
                    } else {
                        $status = 'unpaid';
                        $color = 'danger';
                    }
                @endphp

                <div class="text-center">
                    <small class="text-muted">AP Number</small>
                    <h5 class="fw-bold text-dark mb-0">{{ $transaction->ap_mstr_nbr }}

                    </h5>
                </div>
                <div class="text-center">
                    <span class="badge rounded-pill bg-{{ $color }} px-3 py-2">
                        {{ ucfirst($status) }}
                    </span>

                </div>
                <div class="row text-center">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <small class="text-muted">Total Transaksi</small>
                        <h5 class="fw-semibold text-warning mt-1">
                            {{ rupiah($transaction->ap_mstr_amount) }}
                        </h5>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <small class="text-muted">Jumlah Dibayar</small>
                        <h5 class="fw-semibold text-success mt-1">
                            {{ rupiah($transaction->ap_mstr_paid) }}
                        </h5>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <small class="text-muted">Sisa</small>
                        <h5 class="fw-semibold text-danger mt-1">
                            {{ rupiah($transaction->ap_mstr_balance) }}
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
                                    <th style="text-align: center">AP Payment #</th>
                                    <th style="width:12%; text-align: center">Tanggal</th>
                                    <th style="width:12%; text-align: center">Supplier</th>
                                    <th style="width:15%; text-align: center">Nilai</th>
                                    <th style="width:15%; text-align: center">Method</th>
                                    <th style="width:15%; text-align: center">Ref</th>
                                    <th style="width:15%; text-align: center">Note</th>
                                    {{-- <th style="width:15%; text-align: center">Created By</th> --}}
                                    <th style="width:5%; text-align: center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($aps as $detail)
                                    <tr>
                                        <td style="text-align:right;">{{ $loop->iteration }}</td>
                                        <td>{{ $detail->master->appay_mstr_nbr }}</td>
                                        <td style="text-align:right;">{{ $detail->master->appay_mstr_date }}</td>
                                        <td style="text-align:right;">{{ $detail->master->supplier->supp_mstr_name }}
                                        </td>
                                        <td style="text-align:center;">{{ rupiah($detail->appay_det_payamount) }}
                                        </td>
                                        <td>{{ $detail->master->appay_mstr_method }}</td>
                                        <td>{{ $detail->master->appay_mstr_refno }}</td>
                                        <td>{{ $detail->master->appay_mstr_note }}</td>
                                        <td>
                                            <form id="delete-appay-{{ $detail->master->appay_mstr_id }}"
                                                action="{{ route('AppayMstr.destroy', $detail->master->appay_mstr_id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="confirmDeletePay('{{ $detail->master->appay_mstr_id }}', '{{ $detail->master->appay_mstr_nbr }}')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-dark btn-sm" type="button"
                        onclick="window.location.href='{{ route('ApMstr.index') }}'">Back</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $("#custTable").DataTable({
                    responsive: true,
                    autoWidth: true,
                    // pageLength: 100,
                    scrollY: "350px",
                    lengthMenu: [
                        [25, 20, 75, 100],
                        [25, 20, 75, 100]
                    ]
                });
            });
        </script>
        <script>
            function confirmDeletePay(id, nbr) {
                Swal.fire({
                    title: 'Batalkan Pembayaran?',
                    text: `Hapus pembayaran ${nbr}? Semua invoice hutang di dalamnya akan kembali tertagih (Unpaid).`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Batalkan!',
                    cancelButtonText: 'Tutup'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Mengembalikan saldo hutang...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        document.getElementById('delete-appay-' + id).submit();
                    }
                });
            }
        </script>
    @endpush

</x-app-layout>
