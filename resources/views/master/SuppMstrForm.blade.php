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
                <div class="card-body">
                    <form method="POST"
                        action="{{ isset($supplier) ? route('SupplierMstr.update', $supplier->supp_mstr_id) : route('SupplierMstr.store') }}">
                        @csrf
                        @isset($supplier)
                            @method('PUT')
                        @endisset
                        <div class="row">
                            <div class="col-md-12">
                                <label for="supp_mstr_name" class="form-label">Nama</label>
                                <input type="text" name="supp_mstr_name" class="form-control form-control-sm mb-2"
                                    placeholder="Nama Supplier" value="{{ $supplier->supp_mstr_name ?? '' }}" required>
                            </div>
                            <div class="col-md-12">
                                <label for="supp_mstr_addr" class="form-label">Alamat</label>
                                <textarea name="supp_mstr_addr" class="form-control mb-2" placeholder="Alamat">{{ $supplier->supp_mstr_addr ?? '' }}</textarea>
                            </div>
                            <div class="col-md-12">
                                <label for="supp_mstr_phone" class="form-label">No Telephone</label>
                                <input name="supp_mstr_phone" class="form-control mb-2" placeholder="Telepon" value="{{ $supplier->supp_mstr_phone ?? '' }}">
                            </div>
                            <div class="col-md-12">
                                <label for="supp_mstr_npwp" class="form-label">NPWP</label>
                                <input name="supp_mstr_npwp" class="form-control mb-2" placeholder="NPWP" value="{{ $supplier->supp_mstr_npwp ?? '' }}">
                            </div>
                            <div class="col-md-12">
                                <label>
                                    <input type="checkbox" name="supp_mstr_ppn" value="1"
                                        {{ isset($supplier) && $supplier->supp_mstr_ppn ? 'checked' : '' }}>
                                    PPN
                                </label>
                            </div>
                        </div>

                        <button class="btn btn-success mt-2">Save</button>
                    </form>

                </div>
            </div>

        </div>
    </div>



    @push('scripts')
        <script src="{{ 'assets/js/CustMstr/getData.js' }}"></script>
        <script src="{{ 'assets/js/alert.js' }}"></script>
    @endpush
</x-app-layout>
