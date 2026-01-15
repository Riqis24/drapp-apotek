<x-app-layout>
    <div id="main">
        <div class="card shadow-sm">
            <div class="card-header bg-primary">
                <h4 class="mb-0 text-white">Input Data Perusahaan</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('StoreProfile.store') }}" method="POST">
                    @csrf
                    <div class="mb-3 mt-2">
                        <label for="name" class="form-label">Nama Perusahaan <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="owner" class="form-label">Nama Pemilik</label>
                        <input type="text" class="form-control" id="owner" name="owner">
                    </div>

                    <div class="mb-3">
                        <label for="npwp" class="form-label">NPWP</label>
                        <input type="text" class="form-control" id="npwp" name="npwp">
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo (Path File)</label>
                        <input type="text" class="form-control" id="logo" name="logo"
                            placeholder="Contoh: /images/logo.png">
                    </div>

                    <div class="mb-4">
                        <label for="footer_note" class="form-label">Catatan Kaki Invoice</label>
                        <textarea class="form-control" id="footer_note" name="footer_note" rows="3"></textarea>
                    </div>

                    <div class="text-end">
                        <button type="reset" class="btn btn-secondary me-2">Reset</button>
                        <button type="submit" class="btn btn-success">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
