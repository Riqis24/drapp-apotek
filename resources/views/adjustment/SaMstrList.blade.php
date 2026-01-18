<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Stock Adjustment</h3>
        </div>
        <div class="page-content">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-outline-primary btn-sm rounded" type="button"
                        onclick="window.location.href='{{ route('SaMstr.create') }}'">
                        Create Stok Adjustment
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="SaTable" class="table table-striped table-bordered table-sm nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Adjustment#</th>
                                    <th>Date</th>
                                    <th>Location</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th width="180">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $sa)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $sa->sa_mstr_nbr }}</td>
                                        <td>{{ $sa->sa_mstr_date }}</td>
                                        <td>{{ $sa->location->location_mstr_name }}</td>
                                        <td>{{ $sa->sa_mstr_reason }}</td>
                                        <td>
                                            <span
                                                class="badge 
                        {{ $sa->sa_mstr_status == 'posted'
                            ? 'bg-success'
                            : ($sa->sa_mstr_status == 'reversed'
                                ? 'bg-danger'
                                : 'bg-secondary') }}">
                                                {{ $sa->sa_mstr_status }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('SaMstr.show', $sa->sa_mstr_id) }}"
                                                class="btn btn-sm btn-info"><i class="bi bi-folder"></i></a>

                                            @if ($sa->sa_mstr_status === 'draft')
                                                <form action="{{ route('SaMstr.destroy', $sa->sa_mstr_id) }}"
                                                    method="POST" class="d-inline"
                                                    id="delete-form-{{ $sa->sa_mstr_id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="handleDelete('{{ $sa->sa_mstr_id }}')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
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
            $("#SaTable").DataTable({
                scrollX: true, // Wajib untuk tabel lebar seperti ini
                scrollY: "350px",
                scrollCollapse: true,
                autoWidth: false, // MATIKAN agar kita bisa kontrol via CSS
                paging: true,

            });
        </script>
        <script>
            function handleDelete(id) {
                // Sesuaikan dengan nama fungsi di swal-helper kamu, biasanya confirmDelete atau sejenisnya
                Swal.fire({
                    title: 'Hapus Draft?',
                    text: "Data adjustment yang dihapus tidak dapat dikembalikan!",
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
