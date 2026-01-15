<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Product Transaction</h3>
        </div>
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body px-5 py-4">
                <div class="row text-center">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <small class="text-muted">Nama Product</small>
                        <h5 class="fw-semibold text-primary mt-1">
                            {{ $default->name }}
                        </h5>
                    </div>
                    <div class="col-md-6 mb-3 mb-md-0">
                        <small class="text-muted">Default Satuan</small>
                        <h5 class="fw-semibold text-success mt-1">
                            {{ $default->measurement->name }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-content">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-outline-primary btn-sm rounded" type="button" data-bs-toggle="modal"
                        data-bs-target="#modalAddMeasurement">
                        Add Measurement
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="custTable" class="table table-striped table-bordered table-sm nowrap"
                            style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width:5%; text-align: center">No</th>
                                    <th style="text-align: center">Nama</th>
                                    <th style="width:12%; text-align: center">Satuan</th>
                                    <th style="width:12%; text-align: center">Conversi</th>
                                    <th style="width:5%; text-align: center">Aksi</th>
                                    {{-- <th>Cust</th> --}}
                                    {{-- <th>Status</th> --}}
                                    {{-- <th>Aksi</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $product->product->name }}</td>
                                        <td>{{ $product->measurement->name }}</td>
                                        <td>{{ $product->conversion }}</td>
                                        <td style="text-align: center">
                                            <button type="button" class="btn btn-sm btn-info rounded"
                                                onclick="window.location.href=''">
                                                <i class="bi bi-folder"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-dark btn-sm" type="button"
                        onclick="window.location.href='{{ route('ProductMstr.index') }}'">Back</button>
                </div>
            </div>
        </div>
    </div>

    {{-- modal add user --}}
    <form action="{{ route('updateMeasurement') }}" method="POST">
        @csrf
        <div class="modal fade" id="modalAddMeasurement" tabindex="-1" aria-labelledby="modalAddMeasurementLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalAddMeasurementLabel">Product Measurement</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" class="form-control form-control-sm" value="{{ $default->id }}"
                                name="product" required>
                            <div class="col-md-6">
                                <label for="address" class="form-label">Measurement</label>
                                <select name="satuan" class="form-control form-control-sm select2" id="fid_Satuans">
                                    @foreach ($satuans as $satuan)
                                        <option value="{{ $satuan->id }}">{{ $satuan->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="" class="form-label">Conversion</label>
                                <input type="number" name="conversi" step="0.00001" min="0.00001"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
        <script src="{{ 'assets/js/CustMstr/getData.js' }}"></script>
        <script src="{{ 'assets/js/alert.js' }}"></script>
    @endpush
</x-app-layout>
