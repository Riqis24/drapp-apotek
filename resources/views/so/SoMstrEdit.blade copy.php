<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>SO Edit</h3>
        </div>
        <div class="page-content">
            <div class="card card-body">
                <h4>Stock Opname – {{ $so->location->loc_mstr_name }}</h4>

                <form method="POST" action="{{ route('SoMstr.update', $so->so_mstr_id) }}">
                    @csrf
                    @method('PUT')

                    <table class="table table-sm table-bordered table-responsive">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Qty System</th>
                                <th>Qty Fisik</th>
                                <th>Selisih</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($so->details as $det)
                                @php
                                    $diff = $det->so_det_qtyphysical - $det->so_det_qty_system;
                                @endphp
                                <tr>
                                    <td>{{ $det->product->name ?? '-' }}</td>
                                    <td>{{ numfmt($det->so_det_qtysystem) }}</td>
                                    <td>
                                        <input type="number" step="0.01"
                                            name="details[{{ $det->so_det_id }}][qty_physical]"
                                            value="{{ numfmt($det->so_det_qtyphysical) }}" class="form-control"
                                            {{ $so->so_mstr_status != 'draft' ? 'readonly' : '' }}>
                                    </td>
                                    <td class="{{ $diff != 0 ? 'text-danger' : '' }}">
                                        {{ $diff }}
                                    </td>
                                    <td>
                                        <input type="text" name="details[{{ $det->so_det_id }}][note]"
                                            value="{{ $det->so_det_note }}" class="form-control"
                                            {{ $so->so_mstr_status != 'draft' ? 'readonly' : '' }}>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if ($so->so_mstr_status == 'draft')
                        <button class="btn btn-success">Simpan</button>
                    @endif
                </form>

                {{-- APPROVE --}}
                @if ($so->so_mstr_status == 'draft')
                    <form method="POST" action="{{ route('SoMstr.approve', $so->so_mstr_id) }}"
                        onsubmit="return confirm('Approve stock opname?')">
                        @csrf
                        <button class="btn btn-danger mt-3">
                            Approve & Generate Adjustment
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>


</x-app-layout>
