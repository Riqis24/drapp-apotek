<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>

        <div class="page-heading d-flex justify-content-between align-items-center">
            <h3>Detail Payment </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('ArMstr.index') }}">AR List</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </nav>
        </div>

        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body px-5 py-4">
                @php
                    // Logika Status Badge
                    $status = strtolower($transaction->ar_mstr_status);
                    $color =
                        [
                            'paid' => 'success',
                            'partial' => 'warning',
                            'unpaid' => 'danger',
                        ][$status] ?? 'secondary';
                @endphp

                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <small class="text-muted fw-bold uppercase">AR Number</small>
                        <h4 class="fw-bold text-primary mb-1">{{ $transaction->ar_mstr_nbr }}</h4>
                        <span class="badge rounded-pill bg-{{ $color }} px-3 py-2">
                            {{ strtoupper($status) }}
                        </span>
                    </div>
                    <div class="col-md-6 mt-3 mt-md-0">
                        <div class="row text-center text-md-end">
                            <div class="col-4">
                                <small class="text-muted d-block">Total Tagihan</small>
                                <span class="fw-bold text-dark">{{ rupiah($transaction->ar_mstr_amount) }}</span>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">Terbayar</small>
                                <span class="fw-bold text-success">{{ rupiah($transaction->ar_mstr_paid) }}</span>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">Sisa Piutang</small>
                                <span class="fw-bold text-danger">{{ rupiah($transaction->ar_mstr_balance) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-content">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">History Pelunasan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="custTable" class="table table-hover table-sm nowrap w-100">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th class="text-center">Payment #</th>
                                    <th class="text-center">Tanggal Bayar</th>
                                    <th class="text-center">Customer</th>
                                    <th class="text-center">Nilai Bayar</th>
                                    <th class="text-center">Method</th>
                                    <th class="text-center">Ref/Note</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($aps as $detail)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="fw-bold text-primary">{{ $detail->master->arpay_mstr_nbr }}</td>
                                        <td class="text-center">
                                            {{ date('d/m/Y', strtotime($detail->master->arpay_mstr_date)) }}</td>
                                        <td>{{ $detail->master->customer->cust_mstr_name ?? '-' }}</td>
                                        <td class="text-end fw-bold">{{ rupiah($detail->arpay_det_amount) }}</td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-info text-dark">{{ strtoupper($detail->master->arpay_mstr_method) }}</span>
                                        </td>
                                        <td>
                                            <small
                                                class="d-block text-muted">{{ $detail->master->arpay_mstr_ref }}</small>
                                            <small class="fst-italic">{{ $detail->master->arpay_mstr_note }}</small>
                                        </td>
                                        <td>
                                            <form id="delete-arpay-{{ $detail->master->arpay_mstr_id }}"
                                                action="{{ route('ArpayMstr.destroy', $detail->master->arpay_mstr_id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="confirmDeletePay('{{ $detail->master->arpay_mstr_id }}', '{{ $detail->master->arpay_mstr_nbr }}')">
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
                <div class="card-footer bg-white border-0">
                    <button class="btn btn-secondary btn-sm px-4" type="button"
                        onclick="window.location.href='{{ route('ArMstr.index') }}'">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#custTable').DataTable({
                    responsive: true,
                    autoWidth: false,
                });
            });
        </script>
        <script>
            function confirmDeletePay(id, nbr) {
                Swal.fire({
                    title: 'Batalkan Pembayaran?',
                    text: `Hapus pembayaran ${nbr}? Semua invoice piutang di dalamnya akan kembali tertagih (Unpaid).`,
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
                            text: 'Mengembalikan saldo piutang...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        document.getElementById('delete-arpay-' + id).submit();
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
