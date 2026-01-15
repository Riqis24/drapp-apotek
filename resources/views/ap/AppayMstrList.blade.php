<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>AP Payment</h3>
        </div>
        <div class="page-content">
            <form action="{{ route('AppayMstr.store') }}" method="POST">
                @csrf
                <div class="card mb-3">
                    <div class="card-body row g-3">
                        <div class="col-md-3">
                            <label>Tanggal Bayar</label>
                            <input type="date" name="appay_date" class="form-control" required>
                        </div>

                        <div class="col-md-3">
                            <label>Supplier</label>
                            <select name="suppid" id="supplier_id" class="form-control select2" required>
                                <option value="">-- pilih supplier --</option>
                                @foreach ($suppliers as $s)
                                    <option value="{{ $s->supp_mstr_id }}">
                                        {{ $s->supp_mstr_code }} | {{ $s->supp_mstr_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Metode</label>
                            <select name="method" class="form-control">
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer</option>
                                <option value="bank">Bank</option>
                                <option value="giro">Giro</option>
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
                                        <input type="text" id="totalPay" class="form-control text-end" readonly>
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
            $('#supplier_id').change(function() {
                let suppid = $(this).val();
                $('#apTable tbody').html('');

                if (!suppid) return;

                $.get('/ajax/ap-by-supplier/' + suppid, function(data) {
                    data.forEach((ap, i) => {
                        $('#apTable tbody').append(`
                <tr>
                    <td>
                        <input type="checkbox" class="chk-ap">
                        <input type="hidden" name="items[${i}][ap_id]" value="${ap.ap_mstr_id}">
                    </td>
                    <td>${ap.ap_mstr_nbr}</td>
                    <td>${ap.ap_mstr_date}</td>
                    <td>${ap.ap_mstr_duedate}</td>
                    <td class="text-end">${format(ap.ap_mstr_balance)}</td>
                    <td>
                        <input type="number"
                            name="items[${i}][pay_amount]"
                            class="form-control text-end pay-input"
                            max="${ap.ap_mstr_balance}"
                            step="0.01"
                            disabled>
                    </td>
                </tr>
            `);
                    });
                });
            });

            $(document).on('change', '.chk-ap', function() {
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
