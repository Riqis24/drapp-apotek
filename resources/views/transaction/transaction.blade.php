<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Transaction</h3>
        </div>
        <form action="{{ route('Transaction.store') }}" method="POST">
            @csrf
            <div class="page-content">
                <div class="container">
                    <!-- Card Header Info -->
                    <div class="card rounded-4 shadow-sm p-4 mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Tanggal</label>
                                <input name="effdate" type="date" class="form-control form-control-sm"
                                    value="{{ now()->toDateString() }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Jam</label>
                                <input type="time" class="form-control form-control-sm"
                                    value="{{ now()->format('H:i') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Pelanggan</label>
                                <select name="customer" class="form-select select2">
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Metode Pembayaran</label>
                                <select name="method_payment" class="form-select select2" required>
                                    <option value=""></option>
                                    <option value="cash">Cash</option>
                                    <option value="credit">Credit</option>
                                    <!-- Tambahkan opsi lain jika perlu -->
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Item yang Dibeli -->
                    <div class="card shadow-sm rounded-4 p-3 mb-4">
                        <h5 class="mb-3">Daftar Item</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Produk</th>
                                        <th style="width:10%">Satuan</th>
                                        <th style="width:12%">Qty</th>
                                        <th style="width:12%">Harga</th>
                                        <th style="width:12%">Diskon</th>
                                        <th style="width:18%">Subtotal</th>
                                        <th style="width:5%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="items-table">
                                    <tr>
                                        <td><select name="item[]"
                                                class="form-control form-control-sm select2 product-select"
                                                style="width: 100%">
                                                <option value="">Pilih Produk</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->product_id }}">
                                                        {{ $product->product->code }} -
                                                        {{ $product->product->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="measurement[]" class="form-select select2 satuan-select"
                                                style="width: 100%">
                                                <option value="">Pilih Satuan</option>
                                            </select>
                                        </td>
                                        <td><input name="qty[]" type="text"
                                                class="form-control form-control-sm qty" placeholder="Qty"></td>
                                        <td><input name="harga[]" type="text"
                                                class="form-control form-control-sm price" placeholder="Harga"></td>
                                        <td><input name="diskon[]" type="text"
                                                class="form-control form-control-sm discount" placeholder="Diskon"></td>
                                        <td><input name="subtotal[]" type="text"
                                                class="form-control form-control-sm subtotal" placeholder="Subtotal">
                                        </td>
                                        <td><button type="button" class="btn btn-danger btn-sm btn-delete">❌</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-outline-primary btn-sm add-item" id="add-item">+
                                Tambah
                                Item</button>
                        </div>
                    </div>

                    <!-- Bagian Bawah: Total, Bayar, Kembalian -->
                    <div class="card shadow rounded-4 p-4 border-0 bg-light">
                        <div class="card-body">
                            <h5 class="mb-4 fw-semibold text-primary"><i class="bi bi-cash-stack me-2"></i> Pembayaran
                            </h5>
                            <div class="row g-4 align-items-center">
                                <div class="col-md-4">
                                    <label for="total" class="form-label fw-semibold">Total Belanja</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-primary text-white">Rp</span>
                                        <input name="total" type="text"
                                            class="form-control bg-white fw-bold text-end" id="total" value="0,00"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="bayar" class="form-label fw-semibold">Bayar</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-success text-white">Rp</span>
                                        <input name="bayar" type="text" class="form-control fw-bold text-end"
                                            id="bayar" placeholder="Jumlah Bayar">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="kembalian" class="form-label fw-semibold">Kembalian</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-warning text-dark">Rp</span>
                                        <input name="kembalian" type="text"
                                            class="form-control bg-white fw-bold text-end" id="kembalian"
                                            value="0,00" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5 text-end">
                                <button type="submit" class="btn btn-lg btn-success rounded-3 px-4 shadow-sm">
                                    <i class="bi bi-cart-check me-2"></i> Simpan & Checkout
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @push('scripts')
        <script>
            function formatNumber(n) {
                const number = parseFloat(n);

                if (isNaN(number)) return '0,00';

                // Buat fixed 2 digit dan ubah titik ke koma
                let str = number.toFixed(2).replace('.', ',');

                // Pisah ribuan dan desimal
                let parts = str.split(',');
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // tambahkan titik ribuan

                return parts.join(',');
            }


            function unformatNumber(formatted) {
                // Hapus titik ribuan, ubah koma desimal ke titik
                const cleaned = formatted.replace(/\./g, '').replace(',', '.');
                return parseFloat(cleaned);
            }
            $(document).on('click', '.add-item', function() {
                const row = $(this).closest('tr');

            });


            $(document).on('change', '.select2.product-select', function() {
                const productId = $(this).val();
                const row = $(this).closest('tr');
                const satuanSelect = row.find('.satuan-select');

                satuanSelect.html('<option>⌛Loading..</option>');

                $.ajax({
                    url: '/get-satuans/' + productId, // Buat route ini di Laravel
                    method: 'GET',
                    success: function(data) {
                        satuanSelect.empty().append('<option value="">Pilih Satuan</option>');
                        data.forEach(function(satuan) {
                            satuanSelect.append(
                                `<option value="${satuan.id}">${satuan.name}</option>`);
                        });
                    }
                });
            });

            $(document).on('change', '.satuan-select', function() {
                const row = $(this).closest('tr');
                const productId = row.find('.product-select').val();
                const satuanId = $(this).val();
                const priceInput = row.find('.price');

                if (!productId || !satuanId) return;

                $.ajax({
                    url: `/get-harga/${productId}/${satuanId}`,
                    method: 'GET',
                    success: function(res) {
                        priceInput.val(formatNumber(res.harga));
                        priceInput.trigger('input'); // Hitung ulang
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                console.log('select ready');
                $('.select2').select2();
            });
            document.addEventListener("DOMContentLoaded", function() {
                const tableBody = document.getElementById("items-table");
                const addItemBtn = document.getElementById("add-item");
                const bayarInput = document.getElementById("bayar");
                const totalInput = document.getElementById("total");
                const kembalianInput = document.getElementById("kembalian");

                function formatNumber(n) {
                    const number = parseFloat(n);

                    if (isNaN(number)) return '0,00';

                    // Buat fixed 2 digit dan ubah titik ke koma
                    let str = number.toFixed(2).replace('.', ',');

                    // Pisah ribuan dan desimal
                    let parts = str.split(',');
                    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // tambahkan titik ribuan

                    return parts.join(',');
                }

                function unformatNumber(formatted) {
                    // Hapus titik ribuan, ubah koma desimal ke titik
                    const cleaned = formatted.replace(/\./g, '').replace(',', '.');
                    return parseFloat(cleaned);
                }

                function calculateRowSubtotal(row, manual = false) {
                    const qty = parseFloat(row.querySelector(".qty")?.value) || 0;
                    const price = parseFloat(unformatNumber(row.querySelector(".price")?.value)) || 0;
                    const discount = parseFloat(unformatNumber(row.querySelector(".discount")?.value)) || 0;
                    const subtotalField = row.querySelector(".subtotal");

                    if (manual) {
                        return parseFloat(unformatNumber(subtotalField?.value)) || 0;
                    }

                    const subtotal = (qty * price) - discount;
                    if (subtotalField) subtotalField.value = formatNumber(subtotal);
                    return subtotal;
                }

                function calculateTotal() {
                    let total = 0;
                    const rows = tableBody.querySelectorAll("tr");
                    rows.forEach(row => {
                        total += calculateRowSubtotal(row, true);
                    });
                    totalInput.value = `Rp. ${formatNumber(total)}`;
                    return total;
                }

                function calculateKembalian() {
                    const bayar = parseFloat(unformatNumber(bayarInput.value)) || 0;
                    const total = calculateTotal();
                    const kembali = bayar - total;
                    kembalianInput.value = `Rp. ${kembali >= 0 ? formatNumber(kembali) : "0"}`;
                }

                function applyFormatListener(input) {
                    input.addEventListener("blur", function() {
                        const rawValue = unformatNumber(input.value);
                        input.value = formatNumber(rawValue);
                    });
                }

                function addListenersToRow(row) {
                    // Format angka
                    ["price", "discount", "subtotal"].forEach(cls => {
                        const input = row.querySelector(`.${cls}`);
                        if (input) {
                            input.classList.add("number-format");
                            applyFormatListener(input);
                        }
                    });

                    // Hitung ulang subtotal & total saat input berubah
                    row.querySelectorAll(".qty, .price, .discount").forEach(input => {
                        input.addEventListener("input", () => {
                            calculateRowSubtotal(row);
                            calculateTotal();
                            calculateKembalian();
                        });
                    });

                    // Manual input subtotal
                    const subtotalInput = row.querySelector(".subtotal");
                    if (subtotalInput) {
                        subtotalInput.removeAttribute("disabled");
                        subtotalInput.addEventListener("input", () => {
                            calculateTotal();
                            calculateKembalian();
                        });
                    }

                    // Select2 init
                    $(row).find('.select2').select2();

                    // Dropdown satuan dinamis
                    const productSelect = row.querySelector('.product-select');
                    const satuanSelect = row.querySelector('.satuan-select');

                    if (productSelect && satuanSelect) {
                        productSelect.addEventListener('change', function() {
                            const productId = this.value;
                            fetch(`/get-satuans/${productId}`)
                                .then(res => res.json())
                                .then(data => {
                                    satuanSelect.innerHTML = '';
                                    data.forEach(item => {
                                        const option = document.createElement('option');
                                        option.value = item.id;
                                        option.text = item.name;
                                        satuanSelect.appendChild(option);
                                    });
                                });
                        });
                    }
                }
                addItemBtn.addEventListener("click", function() {
                    const newRow = document.createElement("tr");
                    newRow.innerHTML = `
                <tr>
                    <td><select name="item[]"
                                            class="form-control form-control-sm select2 product-select"
                                            style="width: 100%">
                                        </select>
                    <td>
                        <select name="measurement[]" class="form-select satuan select2 satuan-select">
                            <option value="">Pilih Satuan</option>
                            
                        </select>
                    </td>
                    <td><input name="qty[]" type="text" class="form-control form-control-sm qty" min="1" placeholder="Qty"></td>
                    <td><input name="harga[]" type="text" class="form-control form-control-sm price number-format" placeholder="Harga"></td>
                    <td><input name="diskon[]" type="text" class="form-control form-control-sm discount number-format" placeholder="Diskon"></td>
                    <td><input name="subtotal[]" type="text" class="form-control form-control-sm subtotal number-format" placeholder="Subtotal"></td>
                    <td><button class="btn btn-danger btn-sm btn-delete">❌</button></td>
                </tr>
            `;

                    // Tambah row ke tabel
                    document.querySelector("#items-table").appendChild(newRow);

                    const productSelect = newRow.querySelector(".product-select");

                    fetch(`/get-product`)
                        .then(res => res.json())
                        .then(data => {
                            productSelect.innerHTML = `<option value="">Pilih Produk</option>`;
                            data.forEach(product => {
                                const option = document.createElement("option");
                                option.value = product.id;
                                option.textContent = product.name;
                                productSelect.appendChild(option);
                            });

                            // Setelah produk ready, aktifkan select2
                            $(productSelect).select2();
                            addListenersToRow(newRow);
                        });


                });


                tableBody.addEventListener("click", function(e) {
                    if (e.target.classList.contains("btn-delete")) {
                        e.target.closest("tr").remove();
                        calculateTotal();
                        calculateKembalian();
                    }
                });

                // Listener awal untuk baris pertama
                document.querySelectorAll("#items-table tr").forEach(row => {
                    addListenersToRow(row);
                });

                bayarInput.classList.add("number-format");
                applyFormatListener(bayarInput);
                bayarInput.addEventListener("input", calculateKembalian);
            });
        </script>
        <script src="{{ 'assets/js/alert.js' }}"></script>
    @endpush

</x-app-layout>
