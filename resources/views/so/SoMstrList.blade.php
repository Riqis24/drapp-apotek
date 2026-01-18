<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Stock Opname</h3>
        </div>
        <div class="page-content">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-outline-primary btn-sm rounded" type="button"
                        onclick="window.location.href='{{ route('SoMstr.create') }}'">
                        Create Stok Opname
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="SoTable" class="table table-striped table-bordered table-sm nowrap"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 5%;">No</th>
                                    <th style="text-align: center">SO#</th>
                                    <th style="text-align: center; width: 15%;">Tanggal</th>
                                    <th style="text-align: center; width: 15%;">Lokasi</th>
                                    <th style="text-align: center; width: 10%;">Status</th>
                                    <th style="text-align: center; width: 10%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($opnames as $so)
                                    <tr>
                                        <td style="text-align: right">{{ $loop->iteration }}</td>
                                        <td style="text-align: left">{{ $so->so_mstr_nbr }}</td>
                                        <td style="text-align: center">{{ $so->so_mstr_date }}</td>
                                        <td style="text-align: center">{{ $so->location->loc_mstr_name ?? '-' }}
                                        </td>
                                        <td style="text-align: center">
                                            <span
                                                class="badge bg-{{ $so->so_mstr_status == 'approved' ? 'success' : 'warning' }}">
                                                {{ strtoupper($so->so_mstr_status) }}
                                            </span>
                                        </td>
                                        <td style="text-align: center">
                                            <a href="{{ route('SoMstr.edit', $so->so_mstr_id) }}"
                                                class="btn btn-sm btn-info">
                                                <i class="bi bi-folder"></i>
                                            </a>
                                            <a href="{{ route('SoMstr.viewApprove', $so->so_mstr_id) }}"
                                                class="btn btn-sm btn-success">
                                                <i class="bi bi-check-square"></i>
                                            </a>
                                            <form action="{{ route('SoMstr.destroy', $so->so_mstr_id) }}" method="POST"
                                                class="d-inline" id="delete-form-{{ $so->so_mstr_id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="handleDelete('{{ $so->so_mstr_id }}')">
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
            $("#SoTable").DataTable({
                scrollX: true, // Wajib untuk tabel lebar seperti ini
                scrollY: "350px",
                scrollCollapse: true,
                autoWidth: false, // MATIKAN agar kita bisa kontrol via CSS
                paging: true,

            });
        </script>
        <script src="{{ 'assets/js/alert.js' }}"></script>
        <script>
            function handleDelete(id) {
                // Sesuaikan dengan nama fungsi di swal-helper kamu, biasanya confirmDelete atau sejenisnya
                Swal.fire({
                    title: 'Hapus SO?',
                    text: "Data Stock Opname yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit form secara manual berdasarkan ID
                        document.getElementById('delete-form-' + id).submit();
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
