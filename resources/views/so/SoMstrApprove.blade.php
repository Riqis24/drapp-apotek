<x-app-layout>
    <div id="main">
        <div class="page-heading">
            <h3>Approve Stock Opname</h3>
        </div>

        <div class="page-content">
            <div class="card shadow-sm">

                <div class="card-header bg-light">
                    <h4 class="mb-0">
                        📋 Stock Opname
                        <small class="text-muted">
                            – {{ $so->location->loc_mstr_name }}
                        </small>
                    </h4>


                    @if ($so->so_mstr_status === 'draft')
                        <span class="badge bg-warning mt-2">
                            Status: {{ $so->so_mstr_status }}
                        </span>
                    @else
                        <span class="badge bg-success mt-2">
                            Status: {{ $so->so_mstr_status }}
                        </span>
                    @endif
                </div>

                <div class="card-body">
                    @if ($so->so_mstr_status === 'draft')
                        {{-- INFO --}}
                        <div class="alert alert-primary mt-2">
                            🔒 Data sudah final. Pastikan qty fisik benar sebelum approve.
                        </div>
                    @endif
                    {{-- TABLE --}}
                    <div class="table-responsive mt-2">
                        <table id="itemTable" class="table table-sm table-bordered align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th>No</th>
                                    <th>Produk</th>
                                    <th class="text-end">Qty System</th>
                                    <th class="text-end">Qty Fisik</th>
                                    <th>Um</th>
                                    <th class="text-end">Selisih</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($so->details as $det)
                                    @php
                                        $diff = $det->so_det_qtyphysical - $det->so_det_qtysystem;
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $det->product->name }}</td>

                                        <td class="text-end">
                                            {{ numfmt($det->so_det_qtysystem) }}
                                        </td>

                                        <td class="text-end">
                                            {{ numfmt($det->so_det_qtyphysical) }}
                                        </td>

                                        <td>{{ $det->product->measurement->name }}</td>

                                        <td class="text-end {{ $diff != 0 ? 'text-danger fw-bold' : '' }}">
                                            {{ numfmt($diff) }}
                                        </td>

                                        <td>{{ $det->so_det_note }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($so->so_mstr_status === 'draft')
                        {{-- ACTION --}}
                        <div class="mt-4">
                            <form method="POST" action="{{ route('SoMstr.approve', $so->so_mstr_id) }}"
                                id="approve-form-{{ $so->so_mstr_id }}">
                                @csrf
                                <button type="button" class="btn btn-success w-100"
                                    onclick="handleApprove('{{ $so->so_mstr_id }}')">
                                    ⚠️ Approve & Generate Adjustment
                                </button>
                            </form>
                        </div>
                    @endif


                </div>
                <div class="card-footer">
                    <button type="button" onclick="window.location.href='{{ route('SoMstr.index') }}'"
                        class="btn btn-dark mt-2">Back</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $("#itemTable").DataTable({
                    responsive: true,
                    autoWidth: true,
                    // pageLength: 100,
                    scrollY: "350px",
                    lengthMenu: [
                        [-1, 100, 50, 25],
                        ["All", 100, 50, 25]
                    ]
                });
            });
        </script>
        <script>
            function handleApprove(id) {
                Swal.fire({
                    title: 'Approve Stock Opname?',
                    text: "Proses ini akan otomatis menyesuaikan stok (Stock Adjustment) dan tidak dapat dibatalkan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745', // Warna sukses (hijau)
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Approve & Update Stok!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tampilkan loading saat proses berlangsung karena ini logic-nya berat
                        Swal.fire({
                            title: 'Sedang Memproses...',
                            text: 'Harap tunggu, sistem sedang menghitung selisih stok.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Submit form secara manual
                        document.getElementById('approve-form-' + id).submit();
                    }
                });
            }
        </script>
    @endpush

</x-app-layout>
