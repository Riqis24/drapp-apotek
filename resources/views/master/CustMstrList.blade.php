<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Cust Master</h3>
        </div>
        <div class="page-content">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-outline-primary btn-sm rounded" type="button" data-bs-toggle="modal"
                        data-bs-target="#modalAddCustomer">
                        Add Cust
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="custTable" class="table table-striped table-bordered table-sm nowrap"
                            style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width:5%; text-align: center">No</th>
                                    <th style="width:20%; text-align: center">Nama</th>
                                    <th style="text-align: center">Alamat</th>
                                    <th style="width:20%; text-align: center">No HP</th>
                                    <th style="width:20%; text-align: center">Type</th>
                                    <th style="width:20%; text-align: center">Is Visible</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $cust)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $cust->name }}</td>
                                        <td>{{ $cust->address }}</td>
                                        <td>{{ $cust->phone }}</td>
                                        <td>{{ $cust->type }}</td>
                                        <td>{{ $cust->isvisible == 1 ? 'True' : 'False' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    ini footer
                </div>
            </div>
        </div>
    </div>

    {{-- modal add user --}}
    <form action="{{ route('CustMstr.store') }}" method="POST">
        @csrf
        <div class="modal fade" id="modalAddCustomer" tabindex="-1" aria-labelledby="modalAddCustomerLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalAddCustomerLabel">Cust Master</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="dynamicCustsInputs">
                            <div class="cust-row">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="name" class="form-label">Nama</label>
                                        <input type="text" class="form-control form-control-sm" name="name"
                                            required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="address" class="form-label">Alamat</label>
                                        <input type="text" class="form-control form-control-sm" name="address"
                                            required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="phone" class="form-label">No HP</label>
                                        <input type="text" class="form-control form-control-sm" name="phone"
                                            required>
                                    </div>
                                    {{-- <div class="col-md-2">
                                        <label for="outstanding" class="form-label">Piutang</label>
                                        <input type="text" class="form-control form-control-sm" value="0"
                                            name="outstandings[]" required>
                                    </div> --}}
                                    <div class="col-md-3">
                                        <label for="type" class="form-label">Type</label>
                                        <select name="type" id="type" class="form-select form-select-sm"
                                            required>
                                            <option value=""></option>
                                            <option value="reguler">Reguler</option>
                                            <option value="member">member</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="isvisible" class="form-label">Visible?</label>
                                        <select name="isvisible" id="isvisible" class="form-select form-select-sm"
                                            required>
                                            <option value="1">Iya</option>
                                            <option value="0">Tidak</option>
                                        </select>
                                    </div>
                                    {{-- <div class="col-md-1">
                                        <label for="" class="form-label">Remove</label>
                                        <button type="button"
                                            class="btn btn-danger btn-sm rounded removeRow">❌</button>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        {{-- <button type="button" class="btn btn-info" id="addRow">Add Row</button> --}}
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
