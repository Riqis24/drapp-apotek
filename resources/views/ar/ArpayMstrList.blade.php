<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>AR Payment</h3>
        </div>
        <div class="page-content">
            <form action="{{ route('ArpayMstr.store') }}" method="POST">
                @csrf
                <div class="card mb-3">
                    <div class="card-body row g-3">
                        <div class="col-md-3">
                            <label>Tanggal Bayar</label>
                            <input type="date" name="pay_date" class="form-control" required>
                        </div>

                        <div class="col-md-3">
                            <label>Customers</label>
                            <select name="customer_id" id="customer_id" class="form-control select2" required>
                                <option value="">-- pilih customer --</option>
                                @foreach ($customers as $cust)
                                    <option value="{{ $cust->id }}">
                                        {{ $cust->name }} | {{ $cust->type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Metode</label>
                            <select name="method" class="form-control">
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>No Ref</label>
                            <input type="text" name="refno" class="form-control">
                        </div>

                        <div class="col-md-12">
                            <label>Catatan</label>
                            <textarea name="note" class="form-control"></textarea>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header fw-bold">Daftar Hutang (AP)</div>

                    <div class="card-body">
                        <table class="table table-bordered" id="apTable">
                            <thead>
                                <tr>
                                    <th width="5%">✔</th>
                                    <th>No AP</th>
                                    <th>Tanggal</th>
                                    <th>Jatuh Tempo</th>
                                    <th class="text-end">Sisa Hutang</th>
                                    <th class="text-end">Bayar</th>
                                </tr>
                            </thead>
                            <tbody></tbody>

                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-end">TOTAL BAYAR</th>
                                    <th class="text-end">
                                        <input type="text" id="totalPay" name="totalPay"
                                            class="form-control text-end" readonly>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <button class="btn btn-primary mt-3">
                    Simpan Pembayaran
                </button>
        </div>
    </div>

    @push('scripts')
        <script>
            $('#customer_id').change(function() {
                let custid = $(this).val();
                $('#apTable tbody').html('');

                if (!custid) return;

                $.get('/ajax/ar-by-customer/' + custid, function(data) {
                    data.forEach((ar, i) => {
                        $('#apTable tbody').append(`
                <tr>
                    <td>
                        <input type="checkbox" class="chk-ar">
                        <input type="hidden" name="items[${i}][ar_id]" value="${ar.ar_mstr_id}">
                    </td>
                    <td>${ar.ar_mstr_nbr}</td>
                    <td>${ar.ar_mstr_date}</td>
                    <td>${ar.ar_mstr_duedate}</td>
                    <td class="text-end">${format(ar.ar_mstr_balance)}</td>
                    <td>
                        <input type="number"
                            name="items[${i}][pay_amount]"
                            class="form-control text-end pay-input"
                            max="${ar.ar_mstr_balance}"
                            step="0.01"
                            disabled>
                    </td>
                </tr>
            `);
                    });
                });
            });

            $(document).on('change', '.chk-ar', function() {
                let input = $(this).closest('tr').find('.pay-input');
                input.prop('disabled', !this.checked);
                if (!this.checked) input.val('');
                calcTotal();
            });

            $(document).on('input', '.pay-input', function() {
                let max = parseFloat($(this).attr('max'));
                if (parseFloat(this.value) > max) {
                    this.value = max;
                }
                calcTotal();
            });

            function calcTotal() {
                let total = 0;
                $('.pay-input').each(function() {
                    total += parseFloat(this.value) || 0;
                });
                $('#totalPay').val(format(total));
            }

            function format(num) {
                return new Intl.NumberFormat('id-ID').format(num);
            }
        </script>
    @endpush

</x-app-layout>
