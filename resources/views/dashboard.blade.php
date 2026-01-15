<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>

        <div class="page-heading d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold">Financial Overview</h3>
                <p class="text-subtitle text-muted">Ringkasan performa apotek berdasarkan catatan keuangan terpusat.</p>
            </div>
            <div class="d-none d-md-block">
                <div class="badge bg-light-primary p-2 px-3 rounded-pill text-primary">
                    <i class="bi bi-calendar3 me-2"></i> {{ now()->format('F Y') }}
                </div>
            </div>
        </div>

        <div class="page-content">
            <section class="row">
                <div class="col-12 col-lg-9">

                    <div class="row">
                        <div class="col-6 col-lg-3">
                            <div class="card shadow-sm border-0 rounded-4 text-white"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <p class="small text-uppercase mb-1 opacity-75">Gross Income</p>
                                            <h5 class="fw-extrabold text-white mb-0">Rp
                                                {{ number_format($totalIncome, 0, ',', '.') }}</h5>
                                        </div>
                                        <div class="stats-icon-mini"><i class="bi bi-graph-up"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-lg-3">
                            <div class="card shadow-sm border-0 rounded-4 text-white"
                                style="background: linear-gradient(135deg, #ff6a00 0%, #ee0979 100%);">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <p class="small text-uppercase mb-1 opacity-75">Expense</p>
                                            <h5 class="fw-extrabold text-white mb-0">Rp
                                                {{ number_format($totalExpense, 0, ',', '.') }}</h5>
                                        </div>
                                        <div class="stats-icon-mini"><i class="bi bi-cart-dash"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-lg-3">
                            <div class="card shadow-sm border-0 rounded-4 text-white"
                                style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <p class="small text-uppercase mb-1 opacity-75">PPN Liability</p>
                                            <h5 class="fw-extrabold mb-0 text-white">Rp
                                                {{ number_format($totalPPN, 0, ',', '.') }}</h5>
                                        </div>
                                        <div class="stats-icon-mini"><i class="bi bi-shield-check"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-lg-3">
                            <div class="card shadow-sm border-0 rounded-4 text-white"
                                style="background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%);">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <p class="small text-uppercase mb-1 opacity-75">Available Balance</p>
                                            <h5 class="fw-extrabold text-white mb-0">Rp
                                                {{ number_format($saldo, 0, ',', '.') }}
                                            </h5>
                                        </div>
                                        <div class="stats-icon-mini"><i class="bi bi-wallet2"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-xl-8">
                            <div class="card border-0 shadow-sm rounded-4">
                                <div class="card-header bg-transparent py-4 border-0">
                                    <h5 class="card-title mb-0">Financial Performance (7 Days)</h5>
                                </div>
                                <div class="card-body">
                                    <div id="chart-performance"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-xl-4">
                            <div class="card border-0 shadow-sm rounded-4">
                                <div
                                    class="card-header bg-transparent py-4 border-0 d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Last Transactions</h5>
                                    <a href="{{ Route('FinancialRecord.index') }}"
                                        class="small text-decoration-none">View All</a>
                                </div>
                                <div class="card-body pt-0">
                                    @foreach ($recentRecords as $record)
                                        <div class="recent-message d-flex px-0 mb-4 align-items-center">
                                            <div
                                                class="avatar avatar-lg rounded-3 d-flex align-items-center justify-content-center p-2">
                                                <i
                                                    class="bi {{ $record->type == 'income' ? 'bi-arrow-down-left text-success' : 'bi-arrow-up-right text-danger' }}"></i>
                                            </div>
                                            <div class="name ms-3 flex-grow-1 overflow-hidden">
                                                <h6 class="mb-0 fw-bold small text-truncate">{{ $record->ref_number }}
                                                </h6>
                                                <p class="text-muted small mb-0">
                                                    {{ \Carbon\Carbon::parse($record->created_at)->format('d M, H:i') }}
                                                </p>
                                            </div>
                                            <div class="text-end">
                                                <h6
                                                    class="mb-0 fw-bold small {{ $record->type == 'income' ? 'text-success' : 'text-danger' }}">
                                                    {{ $record->type == 'income' ? '+' : '-' }}
                                                    {{ number_format($record->amount, 0, ',', '.') }}
                                                </h6>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-12 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-body p-0">
                            <div class="p-4 text-center bg-info bg-gradient">
                                <div
                                    class="avatar avatar-xl mb-3 shadow-lg border border-3 border-white border-opacity-25 mx-auto">
                                    <img src="{{ asset('assets/compiled/jpg/1.jpg') }}" alt="Profile" />
                                </div>
                                <h5 class="mb-0 fw-bold text-white">{{ auth()->user()->user_mstr_name }}</h5>
                                <span
                                    class="badge bg-white bg-opacity-25 rounded-pill small">{{ auth()->user()->getRoleNames()->first() }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-transparent border-0 pt-4 pb-0">
                            <h5 class="card-title mb-0">Quick Stock Info</h5>
                        </div>
                        <div class="card-body">
                            <div id="chart-stock-summary"></div>
                            <div class="mt-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="small text-muted">Stok Menipis</span>
                                    <span
                                        class="badge bg-warning text-dark rounded-pill">{{ $stockStatus['low_stock'] }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="small text-muted">Habis</span>
                                    <span
                                        class="badge bg-danger rounded-pill">{{ $stockStatus['out_of_stock'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    @push('styles')
        <style>
            .stats-icon-mini {
                width: 40px;
                height: 40px;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.2rem;
            }

            .fw-extrabold {
                font-weight: 800;
                letter-spacing: -0.5px;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            // Data From Controller
            const incomeData = @json($incomeData);
            const expenseData = @json($expenseData);
            const chartLabels = @json($labels);

            // Main Performance Chart
            var perfOptions = {
                series: [{
                    name: 'Pemasukan',
                    data: incomeData
                }, {
                    name: 'Pengeluaran',
                    data: expenseData
                }],
                chart: {
                    height: 350,
                    type: 'area',
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    }
                },
                colors: ['#667eea', '#ee0979'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 4
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        opacityFrom: 0.4,
                        opacityTo: 0
                    }
                },
                xaxis: {
                    categories: chartLabels
                },
                yaxis: {
                    labels: {
                        formatter: (v) => "Rp " + v.toLocaleString()
                    }
                }
            };
            new ApexCharts(document.querySelector("#chart-performance"), perfOptions).render();

            // Stock Donut
            var stockOptions = {
                series: [@json($stockStatus['in_stock']), @json($stockStatus['low_stock']), @json($stockStatus['out_of_stock'])],
                chart: {
                    type: 'donut',
                    height: 250
                },
                labels: ['Safe', 'Low', 'Empty'],
                colors: ['#00b09b', '#fccb90', '#f5576c'],
                legend: {
                    show: false
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '75%'
                        }
                    }
                }
            };
            new ApexCharts(document.querySelector("#chart-stock-summary"), stockOptions).render();
        </script>
    @endpush
</x-app-layout>
