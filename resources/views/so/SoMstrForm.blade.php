<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>SO Form</h3>
        </div>
        <div class="page-content">
            <div class="card card-body">
                <form method="POST" action="{{ route('SoMstr.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label>Tanggal</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Lokasi</label>
                        <select name="locid" class="form-control" required>
                            <option value="">-- Pilih Lokasi --</option>
                            @foreach ($locations as $loc)
                                <option value="{{ $loc->loc_mstr_id }}">
                                    {{ $loc->loc_mstr_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button class="btn btn-primary">Buat Opname</button>
                    <button type="button" onclick="window.location.href='{{ route('SoMstr.index') }}'"
                        class="btn btn-dark">Back</button>
                </form>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            let rowIndex = 0;

            $('#poSelect').on('change', function() {
                let poId = $(this).val();
                if (!poId) return;

                $('#bpbTable tbody').empty();
                rowIndex = 0;

                $.get(`/BpbMstr/getPoItems/${poId}`, function(items) {

                    items.forEach(item => {

                        let row = `
<tr class="bpb-line">
    <td>
        <input type="hidden" name="items[${rowIndex}][po_det_id]" value="${item.po_det_id}">
        <input type="hidden" name="items[${rowIndex}][productid]" value="${item.po_det_productid}">
        <strong>${item.product.name}</strong>
    </td>

    <td>
        <input type="hidden" name="items[${rowIndex}][umid]" value="${item.po_det_um}">
        <input type="hidden" name="items[${rowIndex}][umconv]" value="${item.po_det_umconv}">
        ${item.um.name}
    </td>

    <td>
        <input type="number"
            name="items[${rowIndex}][qty]"
            class="form-control"
            value="${item.po_det_qtyremain}"
            max="${item.po_det_qtyremain}">
    </td>

    <td>
        <input type="number"
            name="items[${rowIndex}][price]"
            class="form-control"
            value="${item.po_det_price}" readonly>
    </td>

    <td>
        <input type="text"
            name="items[${rowIndex}][batch_no]"
            class="form-control"
            required>
    </td>

    <td>
        <input type="date"
            name="items[${rowIndex}][expired_date]"
            class="form-control"
            required>
    </td>

    <td class="text-center">
        <input type="hidden" name="items[${rowIndex}][margin]" value="${item.product.margin}">
        <input type="checkbox"
                name="items[${rowIndex}][updateprice]"
                value="1">
                update price?
    </td>
    
     <td>
        <button type="button" class="btn btn-danger btn-sm removeRow">×</button>
    </td>
</tr>
            `;

                        $('#bpbTable tbody').append(row);
                        rowIndex++;
                    });
                    $(document).on('click', '.removeRow', function() {
                        $(this).closest('tr').remove();
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
