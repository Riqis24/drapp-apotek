<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Sales Transaction</h3>
        </div>
        <div class="page-content">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <div class="container mt-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Pengaturan Sistem Inventory</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-3">
                                    <div>
                                        <h6 class="mb-0 font-weight-bold mt-2">Izinkan Stok Minus</h6>
                                        <small class="text-muted">Jika aktif, kasir tetap bisa menjual barang meskipun
                                            stok di sistem
                                            0.</small>
                                    </div>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="allow_negative_stock"
                                            {{ ($settings['allow_negative_stock'] ?? '0') == '1' ? 'checked' : '' }}
                                            onchange="updateSetting('allow_negative_stock', this.checked ? '1' : '0')">
                                        <label class="custom-control-label" for="allow_negative_stock"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- <script src="{{ 'assets/js/CustMstr/getData.js' }}"></script> --}}
        <script src="{{ 'assets/js/alert.js' }}"></script>
        <script>
            function updateSetting(key, value) {
                // Gunakan helper swalLoading kamu
                swalLoading("Menyimpan perubahan...");

                fetch("{{ route('settings.update') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            [key]: value
                        })
                    })
                    .then(res => res.json())
                    .then(res => {
                        swalSuccess("Berhasil", res.message);
                    })
                    .catch(err => {
                        swalError("Gagal", "Tidak dapat menyimpan pengaturan.");
                    });
            }
        </script>
    @endpush
</x-app-layout>
