<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Purchase Order</h3>
        </div>
        <div class="page-content">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-outline-primary btn-sm rounded" type="button"
                        onclick="window.location.href='{{ route('PurchaseOrder.create') }}'">
                        Create PO
                    </button>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="PoTable" class="table table-striped table-bordered table-sm nowrap">
                            <thead class="table-dark">
                                <tr>
                                    <th style="text-align: center">No</th>
                                    <th style="text-align: center">Tanggal</th>
                                    <th style="text-align: center">PO #</th>
                                    <th style="text-align: center">Supplier</th>
                                    <th style="text-align: center">ETA</th>
                                    <th style="text-align: center">Sub Total</th>
                                    <th style="text-align: center">Discount</th>
                                    <th style="text-align: center">PPN</th>
                                    <th style="text-align: center">Grand Total</th>
                                    <th style="text-align: center">Remark</th>
                                    <th style="text-align: center">Payment</th>
                                    <th style="text-align: center">Jatuh Tempo</th>
                                    {{-- <th style="text-align: center">Created By</th> --}}
                                    <th style="text-align: center">Created At</th>
                                    <th style="text-align: center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        {{-- <td>{{ $item->po_mstr_date }}</td> --}}
                                        <td>{{ $item->po_mstr_createdat }}</td>
                                        <td>{{ $item->po_mstr_nbr }}</td>
                                        <td>{{ $item->supplier->supp_mstr_name }}</td>
                                        <td>{{ $item->po_mstr_eta }}</td>
                                        <td>{{ rupiah($item->po_mstr_subtotal) }}</td>
                                        <td>{{ rupiah($item->po_mstr_discamt) }}</td>
                                        <td>{{ rupiah($item->po_mstr_ppnamt) }}</td>
                                        <td>{{ rupiah($item->po_mstr_grandtotal) }}</td>
                                        <td>{{ $item->po_mstr_payment }}</td>
                                        <td>{{ $item->po_mstr_duedate }}</td>
                                        <td>{{ $item->po_mstr_note }}</td>
                                        <td>{{ $item->user->user_mstr_name }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" type="button"
                                                onclick="window.open('{{ route('PurchaseOrder.show', $item->po_mstr_id) }}')">
                                                <i class="bi bi-folder"></i>
                                            </button>
                                            <form id="delete-po-{{ $item->po_mstr_id }}"
                                                action="{{ route('PurchaseOrderList.destroy', $item->po_mstr_id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="confirmDeletePo('{{ $item->po_mstr_id }}', '{{ $item->po_mstr_nbr }}')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-warning rounded"
                                                onclick="window.location.href='{{ route('PrMstr.create', $item->po_mstr_id) }}'">
                                                <i class="bi bi-cart-dash"></i>
                                            </button>
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
            $("#PoTable").DataTable({
                scrollX: true, // Wajib untuk tabel lebar seperti ini
                scrollY: "350px",
                scrollCollapse: true,
                autoWidth: false, // MATIKAN agar kita bisa kontrol via CSS
                paging: true,

            });
        </script>
        <script src="{{ 'assets/js/alert.js' }}"></script>
        <script>
            function confirmDeletePo(id, poNumber) {
                Swal.fire({
                    title: 'Hapus Purchase Order?',
                    text: `Apakah Anda yakin ingin menghapus PO: ${poNumber}? Data tidak dapat dikembalikan.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit form secara manual
                        document.getElementById('delete-po-' + id).submit();
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
