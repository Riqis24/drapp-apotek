<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>TS Form</h3>
        </div>
        <div class="page-content">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">{{ $ts->ts_mstr_nbr }}</h5>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Dari</strong><br>
                            {{ $ts->fromLocation->loc_mstr_name }}
                        </div>
                        <div class="col-md-4">
                            <strong>Ke</strong><br>
                            {{ $ts->toLocation->loc_mstr_name }}
                        </div>
                        <div class="col-md-4">
                            <strong>Status</strong><br>
                            <span
                                class="badge bg-{{ $ts->ts_mstr_status === 'draft' ? 'secondary' : ($ts->ts_mstr_status === 'posted' ? 'success' : 'danger') }}">
                                {{ strtoupper($ts->ts_mstr_status) }}
                            </span>
                        </div>
                    </div>

                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th>Batch</th>
                                <th width="120">Qty</th>
                                @if ($ts->ts_mstr_status === 'draft')
                                    <th width="80">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ts->details as $det)
                                <tr data-id="{{ $det->ts_det_id }}">
                                    <td>{{ $det->product->name }}</td>
                                    <td>{{ optional($det->batch)->batch_mstr_no ?? '-' }}</td>

                                    <td>
                                        @if ($ts->ts_mstr_status === 'draft')
                                            <input type="number" class="form-control form-control-sm qty-input"
                                                value="{{ $det->ts_det_qty }}" min="0.01" step="0.01">
                                        @else
                                            {{ $det->ts_det_qty }}
                                        @endif
                                    </td>

                                    @if ($ts->ts_mstr_status === 'draft')
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-danger btn-delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- ACTION BUTTON --}}
                    <div class="mt-3">
                        @if ($ts->ts_mstr_status === 'draft')
                            <form method="POST" action="{{ route('TsMstr.post', $ts->ts_mstr_id) }}">
                                @csrf
                                <button class="btn btn-success" id="btn-post">
                                    POST TRANSFER
                                </button>
                            </form>
                        @elseif ($ts->ts_mstr_status === 'posted')
                            <form method="POST" action="{{ route('TsMstr.cancelpost', $ts->ts_mstr_id) }}">
                                @csrf
                                <button class="btn btn-danger">
                                    CANCEL TRANSFER
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
    @push('scripts')
        <script>
            $('.qty-input').change(function() {
                const tr = $(this).closest('tr');
                const detId = tr.data('id');
                const qty = $(this).val();

                $.ajax({
                    url: `/ts/detail/${detId}`,
                    method: 'PUT',
                    data: {
                        qty: qty,
                        _token: '{{ csrf_token() }}'
                    },
                    success() {
                        toastr.success('Qty diperbarui');
                    }
                });
            });
            $('.btn-delete').click(function() {
                if (!confirm('Hapus item ini?')) return;

                const tr = $(this).closest('tr');
                const detId = tr.data('id');

                $.ajax({
                    url: `/ts/detail/${detId}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success() {
                        tr.remove();
                        toastr.success('Item dihapus');
                    }
                });
            });
        </script>
    @endpush


</x-app-layout>
