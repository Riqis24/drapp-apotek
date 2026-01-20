<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Supplier Master</h3>
        </div>
        <div class="page-content">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-outline-primary btn-sm rounded" type="button"
                        onclick="window.open('{{ route('SupplierMstr.create') }}', '_blank')">
                        Add Supplier
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
                                    <th style="text-align: center">Alamat</th>
                                    <th style="text-align: center">No Telp</th>
                                    <th style="text-align: center">PPN</th>
                                    <th style="text-align: center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($suppliers as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->supp_mstr_code }}</td>
                                        <td>{{ $item->supp_mstr_name }}</td>
                                        <td>{{ $item->supp_mstr_addr }}</td>
                                        <td>{{ $item->supp_mstr_phone }}</td>
                                        <td>{{ $item->supp_mstr_ppn }}</td>
                                        <td>
                                            <a href="{{ route('SupplierMstr.edit', $item->supp_mstr_id) }}"
                                                class="btn btn-sm btn-warning"><i class="bi bi-pen"
                                                    style="font-size: 12px"></i></a>
                                            <a href="{{ route('SuppMstrList.destroy', $item->supp_mstr_id) }}"
                                                class="btn btn-sm btn-danger"><i class="bi bi-trash"
                                                    style="font-size: 12px"></i></a>
                                        </td>
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

    @push('scripts')
        <script src="{{ 'assets/js/CustMstr/getData.js' }}"></script>
        <script src="{{ 'assets/js/alert.js' }}"></script>
    @endpush
</x-app-layout>
