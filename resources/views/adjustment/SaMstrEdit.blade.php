<x-app-layout>
    <div id="main">

        {{-- PAGE HEADER --}}
        <div class="page-heading mb-3 d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Adjustment Detail</h3>

            <span
                class="badge fs-6 px-3 py-2
                {{ $sa->sa_mstr_status === 'posted'
                    ? 'bg-success'
                    : ($sa->sa_mstr_status === 'reversed'
                        ? 'bg-danger'
                        : 'bg-secondary') }}">
                {{ strtoupper($sa->sa_mstr_status) }}
            </span>
        </div>

        <div class="page-content">

            {{-- MASTER INFO --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="text-muted small">Adjustment No</div>
                            <div class="fw-bold">{{ $sa->sa_mstr_nbr }}</div>
                        </div>

                        <div class="col-md-3">
                            <div class="text-muted small">Date</div>
                            <div class="fw-bold">{{ $sa->sa_mstr_date }}</div>
                        </div>

                        <div class="col-md-3">
                            <div class="text-muted small">Location</div>
                            <div class="fw-bold">{{ $sa->location->loc_mstr_name }}</div>
                        </div>

                        <div class="col-md-3">
                            <div class="text-muted small">Total Items</div>
                            <div class="fw-bold">{{ $sa->details->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DETAIL TABLE --}}
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-bordered table-hover table-sm mb-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Product</th>
                                <th>Batch</th>
                                <th>Qty System</th>
                                <th>Qty Physical</th>
                                <th>Diff</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sa->details as $d)
                                @php
                                    $diff = $d->sa_det_qtydiff;
                                @endphp
                                <tr>
                                    <td>{{ $d->product->name }}</td>
                                    <td>{{ $d->batch->batch_mstr_no }} | {{ $d->batch->batch_mstr_expireddate }}</td>

                                    <td class="text-end">
                                        {{ number_format($d->sa_det_qtysystem) }}
                                    </td>

                                    <td class="text-end">
                                        {{ number_format($d->sa_det_qtyphysical) }}
                                    </td>

                                    <td class="text-center">
                                        <span
                                            class="badge
                                            {{ $diff > 0 ? 'bg-success' : ($diff < 0 ? 'bg-danger' : 'bg-secondary') }}">
                                            {{ $diff > 0 ? '+' : '' }}{{ $diff }}
                                        </span>
                                    </td>

                                    <td>{{ $d->sa_det_note ?: '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ACTION BUTTON --}}
            <div class="mt-4 d-flex justify-content-between align-items-center">
                <a href="{{ route('SaMstr.index') }}" class="btn btn-secondary">
                    ← Back
                </a>

                <div>
                    @if ($sa->sa_mstr_status === 'draft')
                        <form method="POST" action="{{ route('SaMstr.post', $sa->sa_mstr_id) }}" class="d-inline"
                            onsubmit="return confirm('Post this adjustment?')">
                            @csrf
                            <button class="btn btn-success">
                                <i class="bi bi-check-circle me-1"></i> POST
                            </button>
                        </form>
                    @endif

                    @if ($sa->sa_mstr_status === 'posted')
                        <form method="POST" action="{{ route('SaMstr.reverse', $sa->sa_mstr_id) }}" class="d-inline"
                            onsubmit="return confirm('Reverse this adjustment?')">
                            @csrf
                            <button class="btn btn-danger">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> REVERSE
                            </button>
                        </form>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
