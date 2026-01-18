<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Purchase Return</h3>
        </div>
        <div class="page-content">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <table id="PrTable" class="table table-striped dt-responsive table-bordered table-sm nowrap">
                        <thead class="table-dark">
                            <tr>
                                <th style="text-align: center">No</th>
                                <th style="text-align: center">PR#</th>
                                <th style="text-align: center">Tanggal</th>
                                <th style="text-align: center">PO #</th>
                                <th style="text-align: center">BPB #</th>
                                <th style="text-align: center">Faktur #</th>
                                <th style="text-align: center">Alasan</th>
                                <th style="text-align: center">Created By</th>
                                <th style="text-align: center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($returns as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->pr_mstr_nbr }}</td>
                                    <td>{{ $item->pr_mstr_date }}</td>
                                    <td>{{ $item->po->po_mstr_nbr }}</td>
                                    <td>{{ $item->bpb->bpb_mstr_nbr }}</td>
                                    <td>{{ $item->bpb->bpb_mstr_nofaktur }}</td>
                                    <td>{{ $item->pr_mstr_reason }}</td>
                                    <td>{{ $item->creator->user_mstr_name }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info" type="button"
                                            onclick="window.open('{{ route('PrMstr.show', $item->pr_mstr_id) }}')">
                                            <i class="bi bi-folder"></i>
                                        </button>
                                        <a class="btn btn-sm btn-danger"
                                            href="{{ route('PrMstr.destroy', $item->pr_mstr_id) }}">
                                            <i class="bi bi-trash"></i>
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

    @push('scripts')
        <script>
            $("#PrTable").DataTable({
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
