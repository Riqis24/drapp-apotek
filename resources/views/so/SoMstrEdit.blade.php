<x-app-layout>
    <div id="main">
        <div class="page-heading mb-3">
            <h3>Stock Opname</h3>
            <p class="text-muted mb-0">
                Lokasi: <strong>{{ $so->location->loc_mstr_name }}</strong>
            </p>
            @if ($so->so_mstr_status == 'draft')
                <span class="badge bg-warning mt-2">
                    Status: {{ Str::upper($so->so_mstr_status) }}
                </span>
            @elseif($so->so_mstr_status == 'approved')
                <span class="badge bg-success mt-2">
                    Status: {{ Str::upper($so->so_mstr_status) }}
                </span>
            @else
                <span class="badge bg-info mt-2">
                    Status: {{ Str::upper('submitted') }}
                </span>
            @endif

        </div>

        <div class="page-content">
            <div class="card shadow-sm">
                <div class="card-body">

                    {{-- INFO --}}
                    <div class="alert alert-info">
                        ✏️ Silakan tambah item, edit qty fisik, atau hapus item sebelum approve.
                    </div>

                    {{-- ADD ITEM --}}
                    <div class="card mb-3 border">
                        <div class="card-header bg-light">
                            ➕ Tambah Item ke Stock Opname
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('so.items.store', $so->so_mstr_id) }}">
                                @csrf
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-6">
                                        <label class="form-label">Produk</label>
                                        <select name="stock_id" id="stock_id" class="form-select" required>
                                            <option value="">Pilih Produk</option>
                                            @foreach ($products as $p)
                                                <option value="{{ $p->id }}">
                                                    {{ $p->product->name }} ({{ numfmt($p->quantity) }}) |
                                                    {{ $p->batch->batch_mstr_no }}
                                                    ({{ $p->batch->batch_mstr_expireddate }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Qty Fisik</label>
                                        <input type="number" step="0.01" name="qty_physical"
                                            class="form-control form-control-sm" required>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-primary w-100">
                                            ➕ Tambah
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- TABLE --}}
                    <form method="POST" action="{{ route('SoMstr.update', $so->so_mstr_id) }}">
                        @csrf
                        @method('PUT')

                        <div class="table-responsive card card-body border">
                            <table id="ExpenseTable" class="table table-sm table-bordered align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Produk</th>
                                        <th class="text-center">Qty System</th>
                                        <th class="text-center" width="150">Qty Fisik</th>
                                        <th class="text-center">Um</th>
                                        <th class="text-center">Selisih</th>
                                        <th class="text-center" width="200">Catatan</th>
                                        <th class="text-center" width="25"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($so->details as $det)
                                        @php
                                            $diff = $det->so_det_qtyphysical - $det->so_det_qtysystem;
                                        @endphp
                                        <tr class="{{ $diff != 0 ? 'table-warning' : '' }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $det->product->name ?? '-' }} | {{ $det->batch->batch_mstr_no }}
                                                ({{ $det->batch->batch_mstr_expireddate }})
                                            </td>

                                            <td class="text-end">
                                                {{ numfmt($det->so_det_qtysystem) }}
                                            </td>

                                            <td>
                                                <input type="number" step="0.01"
                                                    name="details[{{ $det->so_det_id }}][qty_physical]"
                                                    value="{{ $det->so_det_qtyphysical }}"
                                                    class="form-control form-control-sm">
                                            </td>

                                            <td>
                                                {{ $det->product->measurement->name }}
                                            </td>

                                            <td class="text-end fw-bold {{ $diff != 0 ? 'text-danger' : '' }}">
                                                {{ numfmt($diff) }}
                                            </td>

                                            <td>
                                                <input type="text" name="details[{{ $det->so_det_id }}][note]"
                                                    value="{{ $det->so_det_note }}"
                                                    class="form-control form-control-sm">
                                            </td>

                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete"
                                                    data-id="{{ $det->so_det_id }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach

                                    @if ($so->details->isEmpty())
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">
                                                Belum ada item
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <button class="btn btn-success mt-2">
                            💾 Simpan Perubahan
                        </button>
                        <button type="button" onclick="window.location.href='{{ route('SoMstr.index') }}'"
                            class="btn btn-dark mt-2">Back</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- <script src="{{ url('assets/js/ExpenseTr/getData.js') }}"></script> --}}
        <script>
            $(document).ready(function() {
                $("#ExpenseTable").DataTable({
                    responsive: true,
                    autoWidth: true,
                    // pageLength: 100,
                    scrollY: "350px",
                    lengthMenu: [
                        [-1, 100, 50, 25],
                        ["All", 100, 50, 25]
                    ]
                });


                $('#stock_id').select2({
                    placeholder: 'ketik nama barang',
                    width: '100%',
                    minimumInputLength: 3,
                    allowClear: true,
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                document.querySelectorAll('.btn-delete').forEach(btn => {
                    btn.addEventListener('click', function() {

                        if (!confirm('Hapus item ini?')) return;

                        const id = this.dataset.id;
                        const row = this.closest('tr');

                        fetch(`/so/items/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => {
                                if (!res.ok) throw res;
                                return res.json();
                            })
                            .then(() => {
                                row.remove();
                            })
                            .catch(() => {
                                alert('Gagal menghapus item');
                            });
                    });
                });

            });
        </script>
    @endpush

</x-app-layout>
