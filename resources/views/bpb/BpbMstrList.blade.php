<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Penerimaan Barang</h3>
        </div>
        <div class="page-content">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-outline-primary btn-sm rounded" type="button"
                        onclick="window.location.href='{{ route('BpbMstr.create') }}'">
                        Create Penerimaan Barang
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="BpbTable" class="table table-striped table-bordered table-sm nowrap">
                            <thead class="table-dark">
                                <tr>
                                    <th style="text-align: center">No</th>
                                    <th style="text-align: center">Tanggal BPB</th>
                                    <th style="text-align: center">BPB #</th>
                                    <th style="text-align: center">Faktur #</th>
                                    <th style="text-align: center">SJ #</th>
                                    <th style="text-align: center">PO #</th>
                                    <th style="text-align: center">Supplier</th>
                                    <th style="text-align: center">Remark</th>
                                    <th style="text-align: center">Created At</th>
                                    <th style="text-align: center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bpb as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->bpb_mstr_date }}</td>
                                        <td>{{ $item->bpb_mstr_nbr }}</td>
                                        <td>{{ $item->bpb_mstr_nofaktur }}</td>
                                        <td>{{ $item->bpb_mstr_nosj }}</td>
                                        <td>{{ $item->po->po_mstr_nbr ?? '-' }}</td>
                                        <td>{{ $item->supplier->supp_mstr_name ?? '-' }}</td>
                                        <td>{{ $item->bpb_mstr_note }}</td>
                                        <td>{{ $item->bpb_mstr_createdat }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" type="button"
                                                onclick="window.open('{{ route('BpbMstr.show', $item->bpb_mstr_id) }}')">
                                                <i class="bi bi-folder"></i>
                                            </button>
                                            <a href="{{ route('PrMstr.create', $item->bpb_mstr_id) }}"
                                                class="btn btn-sm btn-secondary">
                                                <i class="bi bi-cart-dash"></i>
                                            </a>
                                            <button class="btn btn-sm btn-warning" type="button"
                                                onclick="window.open('{{ route('BpbMstr.edit', $item->bpb_mstr_id) }}')">
                                                <i class="bi bi-pen"></i>
                                            </button>
                                            <form action="{{ route('BpbMstr.destroy', $item->bpb_mstr_id) }}"
                                                method="POST" id="form-delete-{{ $item->bpb_mstr_id }}"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete('{{ $item->bpb_mstr_id }}')">
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
        <script>
            $("#BpbTable").DataTable({
                scrollX: true, // Wajib untuk tabel lebar seperti ini
                scrollY: "350px",
                scrollCollapse: true,
                autoWidth: false, // MATIKAN agar kita bisa kontrol via CSS
                paging: true,

            });
        </script>
        <script>
            function confirmDelete(id) {
                Swal.fire({
                    title: 'Hapus BPB?',
                    text: "Hutang terkait (jika belum dibayar) akan ikut terhapus dan stok akan ditarik!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-delete-' + id).submit();
                    }
                })
            }
        </script>
        <script src="{{ 'assets/js/alert.js' }}"></script>
    @endpush
</x-app-layout>
