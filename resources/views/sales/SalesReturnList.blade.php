<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Retur Penjualan</h3>
        </div>
        <div class="page-content">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="SrTable" class="table table-striped table-bordered table-sm nowrap">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width:5%; text-align: center">No</th>
                                    <th style="width:12%; text-align: center">SR #</th>
                                    <th style="width:12%; text-align: center">Tanggal</th>
                                    <th style="text-align: center">INV#</th>
                                    <th style="width:12%; text-align: center">Alasan</th>
                                    <th style="width:12%; text-align: center">Created At</th>
                                    <th style="width:5%; text-align: center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($srs as $sr)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $sr->sr_mstr_nbr }}</td>
                                        <td>{{ $sr->sr_mstr_date }}</td>
                                        <td>{{ $sr->sales->sales_mstr_nbr }}</td>
                                        <td>{{ $sr->sr_mstr_reason }}</td>
                                        <td>{{ $sr->created_at }}</td>
                                        <td style="text-align: center">
                                            <button type="button" class="btn btn-sm btn-info rounded"
                                                onclick="window.location.href='{{ route('SrMstr.show', $sr->sr_mstr_id) }}'">
                                                <i class="bi bi-folder"></i>
                                            </button>
                                            <form id="delete-sr-{{ $sr->sr_mstr_id }}"
                                                action="{{ route('SrMstr.destroy', $sr->sr_mstr_id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="confirmDeleteSr('{{ $sr->sr_mstr_id }}', '{{ $sr->sr_mstr_nbr }}')">
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
            $("#SrTable").DataTable({
                scrollX: true, // Wajib untuk tabel lebar seperti ini
                scrollY: "350px",
                scrollCollapse: true,
                autoWidth: false, // MATIKAN agar kita bisa kontrol via CSS
                paging: true,

            });
        </script>
        <script src="{{ 'assets/js/alert.js' }}"></script>
        <script>
            function confirmDeleteSr(id, srNumber) {
                Swal.fire({
                    title: 'Hapus Sales Return?',
                    text: `Apakah Anda yakin ingin menghapus SR: ${srNumber}? Data tidak dapat dikembalikan.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit form secara manual
                        document.getElementById('delete-sr-' + id).submit();
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
