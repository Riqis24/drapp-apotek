<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Transfer Sheet</h3>
        </div>
        <div class="page-content">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-outline-primary btn-sm rounded" type="button"
                        onclick="window.location.href='{{ route('TsMstr.create') }}'">
                        Create Transfer Sheet
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="ExpenseTable" class="table table-striped table-bordered table-sm nowrap"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>TS#</th>
                                    <th>Tanggal</th>
                                    <th>Dari</th>
                                    <th>Ke</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $ts)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $ts->ts_mstr_nbr }}</td>
                                        <td>{{ $ts->ts_mstr_date }}</td>
                                        <td>{{ $ts->fromLocation->loc_mstr_name }}</td>
                                        <td>{{ $ts->toLocation->loc_mstr_name }}</td>
                                        <td>{{ strtoupper($ts->ts_mstr_status) }}</td>
                                        <td>
                                            <a href="{{ route('TsMstr.show', $ts->ts_mstr_id) }}"
                                                class="btn btn-sm btn-info">
                                                <i class="bi bi-folder"></i>
                                            </a>
                                            <form action="{{ route('TsMstrList.destroy', $ts->ts_mstr_id) }}"
                                                method="POST" class="d-inline" id="delete-form-{{ $ts->ts_mstr_id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="handleDelete('{{ $ts->ts_mstr_id }}')">
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
        <script src="{{ 'assets/js/ExpenseTr/getData.js' }}"></script>
        <script src="{{ 'assets/js/alert.js' }}"></script>
        <script>
            function handleDelete(id) {
                // Sesuaikan dengan nama fungsi di swal-helper kamu, biasanya confirmDelete atau sejenisnya
                Swal.fire({
                    title: 'Hapus Draft?',
                    text: "Data Transfer Sheet yang dihapus tidak dapat dikembalikan!",
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
