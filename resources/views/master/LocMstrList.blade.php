<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Location Master</h3>
        </div>
        <div class="page-content">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-outline-primary btn-sm rounded" type="button" data-bs-toggle="modal"
                        data-bs-target="#addLocModal">
                        Add Location
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="custTable" class="table table-striped table-bordered table-sm nowrap"
                            style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th style="text-align: center">No</th>
                                    <th style="text-align: center">Kode</th>
                                    <th style="text-align: center">Nama</th>
                                    <th style="text-align: center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($locations as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->loc_mstr_code }}</td>
                                        <td>{{ $item->loc_mstr_name }}</td>
                                        <td><a href="{{ route('LocMstrList.destroy', $item->loc_mstr_id) }}"
                                                class="btn btn-sm btn-danger"><i class="bi bi-trash"
                                                    style="font-size: 12px"></i></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- modal add user --}}
    <div class="modal fade" id="addLocModal" tabindex="-1" aria-labelledby="addLocModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('LocMstr.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addLocModalLabel">Tambah Gudang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="loc_code" class="form-label">Kode Gudang</label>
                            <input type="text" class="form-control" id="loc_code" name="loc_mstr_code" required>
                        </div>

                        <div class="mb-3">
                            <label for="loc_name" class="form-label">Nama Gudang</label>
                            <input type="text" class="form-control" id="loc_name" name="loc_mstr_name" required>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="loc_active"
                                name="loc_mstr_active" checked>
                            <label class="form-check-label" for="loc_active">
                                Aktif
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="loc_isvisible"
                                name="loc_mstr_isvisible" checked>
                            <label class="form-check-label" for="loc_isvisible">
                                Aktif
                            </label>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="{{ 'assets/js/CustMstr/getData.js' }}"></script>
        <script src="{{ 'assets/js/alert.js' }}"></script>
    @endpush
</x-app-layout>
