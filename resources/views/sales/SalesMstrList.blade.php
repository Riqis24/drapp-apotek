<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Sales Transaction</h3>
        </div>
        <div class="page-content">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="custTable" class="table table-striped table-bordered table-sm nowrap"
                            style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width:5%; text-align: center">No</th>
                                    <th style="width:12%; text-align: center">Tanggal</th>
                                    <th style="text-align: center">SO#</th>
                                    <th style="width:12%; text-align: center">Gudang</th>
                                    <th style="width:13%; text-align: center">Sub Total</th>
                                    <th style="width:13%; text-align: center">Discount</th>
                                    <th style="width:13%; text-align: center">PPN</th>
                                    <th style="width:13%; text-align: center">Grand Total</th>
                                    <th style="width:8%; text-align: center">Status</th>
                                    <th style="width:12%; text-align: center">Note</th>
                                    <th style="width:5%; text-align: center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $transaction->sales_mstr_createdat }}</td>
                                        <td>{{ $transaction->sales_mstr_nbr }}</td>
                                        <td>{{ $transaction->loc->loc_mstr_name }}</td>
                                        <td style="text-align: right">{{ rupiah($transaction->sales_mstr_subtotal) }}
                                        </td>
                                        <td style="text-align: right">{{ rupiah($transaction->sales_mstr_discamt) }}
                                        </td>
                                        <td style="text-align: right">{{ rupiah($transaction->sales_mstr_ppnamt) }}</td>
                                        <td style="text-align: right">{{ rupiah($transaction->sales_mstr_grandtotal) }}
                                        </td>
                                        <td>{{ $transaction->sales_mstr_status }}</td>
                                        <td>{{ $transaction->sales_mstr_note }}</td>
                                        <td style="text-align: center">
                                            <button type="button" class="btn btn-sm btn-info rounded"
                                                onclick="window.location.href='{{ route('SalesMstr.show', $transaction->sales_mstr_id) }}'">
                                                <i class="bi bi-folder"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-warning rounded"
                                                onclick="window.location.href='{{ route('SrMstr.create', $transaction->sales_mstr_id) }}'">
                                                <i class="bi bi-cart-dash"></i>
                                            </button>
                                            <form id="delete-sales-{{ $transaction->sales_mstr_id }}"
                                                action="{{ route('SalesMstr.destroy', $transaction->sales_mstr_id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="confirmDeletePay('{{ $transaction->sales_mstr_id }}', '{{ $transaction->sales_mstr_nbr }}')">
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
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ 'assets/js/CustMstr/getData.js' }}"></script>
        <script src="{{ 'assets/js/alert.js' }}"></script>
        <script>
            function confirmDeletePay(id, nbr) {
                Swal.fire({
                    title: 'Hapus Invoice?',
                    text: `Hapus Invoice ${nbr}? Seluruh transaksi akan direverse.`,
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
                        document.getElementById('delete-sales-' + id).submit();
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
