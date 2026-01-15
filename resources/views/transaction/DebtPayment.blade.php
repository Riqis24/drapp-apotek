<x-app-layout>
    <style>
        .cashier-box {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e5e5e5;
        }

        .cashier-label {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .cashier-input {
            height: 45px;
            font-size: 16px;
            border-radius: 8px;
        }

        .cashier-input:focus {
            box-shadow: 0 0 0 2px #19875450;
            border-color: #198754;
        }

        .summary-box {
            background: white;
            padding: 15px;
            border: 1px solid #eaeaea;
            border-radius: 10px;
            text-align: center;
        }

        .summary-value {
            font-size: 22px;
            font-weight: bold;
            color: #198754;
        }
    </style>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Pelunasan</h3>
        </div>
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body px-5 py-4">
                @php
                    if ($transaction->status == '0') {
                        $status = 'open';
                        $color = 'warning';
                    } elseif ($transaction->status == '1') {
                        $status = 'completed';
                        $color = 'success';
                    } else {
                        $status = 'unknown';
                        $color = 'danger';
                    }
                @endphp
                <div class="mb-4 text-center">
                    <small class="text-muted">No. Invoice</small>
                    <span class="badge rounded-pill bg-{{ $color }} mt-2 px-3 py-2">
                        {{ ucfirst($status) }}
                    </span>
                    <h5 class="fw-bold text-dark mb-0">{{ $transaction->invoice_number }}</h5>
                </div>
                <div class="row text-center">
                    <div class="col-md-3 mb-3 mb-md-0">
                        <small class="text-muted">Nama Customer</small>
                        <h5 class="fw-semibold text-primary mt-1">
                            {{ $transaction->customer->name ?? 'Umum' }}
                        </h5>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <small class="text-muted">Total Transaksi</small>
                        <h5 class="fw-semibold text-success mt-1">
                            {{ rupiah($transaction->total) }}
                        </h5>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <small class="text-muted">Jumlah Dibayar</small>
                        <h5 class="fw-semibold text-warning mt-1">
                            {{ rupiah($transaction->paid) }}
                        </h5>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <small class="text-muted">Metode Pembayaran</small>
                        <h5 class="fw-semibold text-secondary mt-1">
                            {{ $transaction->method_payment }}
                        </h5>
                    </div>

                </div>
            </div>
        </div>

        <div class="page-content">
            <div class="card">
                <div class="card-header">

                </div>
                <form action="{{ route('CustTransaction.update', $transaction->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="cashier-box">

                        @if ($transaction->debt > 0)
                            <div class="row g-3">

                                <!-- Tanggal -->
                                <div class="col-md-3">
                                    <label class="cashier-label">Tanggal</label>
                                    <input type="date" class="form-control cashier-input" name="effdate">
                                </div>

                                <!-- Bayar -->
                                <div class="col-md-3">
                                    <label class="cashier-label">Bayar</label>
                                    <input type="text" class="form-control cashier-input numeric-only money"
                                        id="payment" name="payment">
                                </div>

                                <!-- Kembali -->
                                <div class="col-md-3">
                                    <label class="cashier-label">Kembali</label>
                                    <input type="text" class="form-control cashier-input  money bg-light"
                                        id="change" name="change" readonly>
                                </div>

                                <!-- Sisa -->
                                <div class="col-md-3">
                                    <label class="cashier-label">Sisa</label>
                                    <input type="text" class="form-control cashier-input money bg-light"
                                        id="rest" name="rest" readonly>
                                </div>
                            </div>

                            <!-- Summary box tampilkan sisa utang -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="summary-box">
                                        <div>Sisa Utang Saat Ini</div>
                                        <div class="summary-value">
                                            Rp {{ number_format($transaction->debt, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="summary-box">
                                        <div>Sisa Utang Saat Ini</div>
                                        <div class="summary-value">
                                            Rp {{ number_format($transaction->debt, 0, ',', '.') }}
                                        </div>
                                        <h5>THIS INVOICE HAS <span style="color: #198754">COMPLETED</span></h5>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>

                    <div class="card-footer mt-3">
                        <button class="btn btn-success btn-lg" type="submit" style="width:180px;">
                            Submit
                        </button>

                        <button class="btn btn-dark btn-lg" type="button"
                            onclick="window.location.href='{{ route('CustTransaction.index') }}'">
                            Back
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ url('assets/js/CustMstr/getData.js') }}"></script>
        <script src="{{ url('assets/js/alert.js') }}"></script>
        <script>
            // tidak bisa input string utk form type text
            document.querySelectorAll('.numeric-only').forEach(function(el) {
                el.addEventListener('input', function() {
                    this.value = this.value.replace(/[^\d\.]/g, '');
                });
            });
            // Format ribuan (auto)
            function formatRibuan(angka) {
                return new Intl.NumberFormat('id-ID').format(angka);
            }

            // Hilangkan format ribuan → angka asli
            function cleanNumber(str) {
                return str.replace(/\./g, '');
            }

            document.querySelectorAll('.money').forEach(function(el) {
                el.addEventListener('input', function() {
                    let raw = this.value.replace(/\D/g, "");
                    if (!raw) {
                        this.value = "";
                        return;
                    }
                    this.value = formatRibuan(raw);
                    hitung(); // panggil tiap user ketik
                });
            });

            function hitung() {
                let debt = {{ $transaction->debt ?? 0 }};
                let paymentInput = document.getElementById('payment').value;
                // console.log(debt, paymentInput)


                if (!paymentInput) {
                    document.getElementById('change').value = "";
                    document.getElementById('rest').value = "";
                    return;
                }

                let payment = cleanNumber(paymentInput) || 0;
                // console.log(payment);
                let change = 0;
                let rest = 0;

                if (payment > debt) {
                    change = payment - debt;
                    rest = 0;
                } else {
                    rest = debt - payment;
                    change = 0;
                }

                document.getElementById('change').value = change > 0 ? formatRibuan(change) : "";
                document.getElementById('rest').value = rest > 0 ? formatRibuan(rest) : "";
            };


            document.querySelector('form').addEventListener('submit', function() {
                document.querySelectorAll('.money').forEach(function(el) {
                    el.value = cleanNumber(el.value);
                });
            });
        </script>
    @endpush
</x-app-layout>
