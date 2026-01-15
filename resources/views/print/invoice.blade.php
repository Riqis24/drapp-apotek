<!DOCTYPE html>
<html>

<head>
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 5px;
            text-align: left;
        }

        .no-border {
            border: none !important;
        }

        .header-title {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }

        .footer {
            margin-top: 20px;
            font-size: 11px;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="header-title">INVOICE</div>

    <table class="no-border">
        <tr class="no-border">
            <td class="no-border">
                <strong>{{ $store->name }}</strong><br>
                {{ $store->address }}<br>
                Telp: {{ $store->phone }}<br>
                Email: {{ $store->email }}
            </td>

            <td class="no-border" style="text-align:right;">
                <strong>Tanggal:</strong> {{ $custTr->date }}<br>
                <strong>No. Invoice:</strong> {{ $custTr->invoice_number }}
            </td>
        </tr>
    </table>

    <hr>

    <table class="no-border">
        <tr class="no-border">
            <td class="no-border" style="width:50%">
                <strong>Kepada Yth:</strong><br>
                {{ $custTr->customer->name }}<br>
                {{ $custTr->customer->address }}<br>
                Telp: {{ $custTr->customer->phone }}
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Qty</th>
                <th>Satuan</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandtotal = 0;
            @endphp
            @foreach ($items as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->measurement->name }}</td>
                    <td>{{ number_format($item->unit_price) }}</td>
                    <td>{{ number_format($item->subtotal) }}</td>
                </tr>
                @php
                    $grandtotal += $item->subtotal;
                @endphp
            @endforeach
            <tr>
                <td colspan="5" style="text-align:right;"><strong>Grand Total</strong></td>
                <td><strong>{{ number_format($grandtotal) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <br><br>
    <table class="no-border">
        <tr class="no-border">
            <td class="no-border" style="text-align:center;">
                <br><br><br>
                ...................................... <br>
                <strong>Penerima</strong>
            </td>

            <td class="no-border" style="text-align:center;">
                <br><br><br>
                ...................................... <br>
                <strong>Hormat Kami</strong>
            </td>
        </tr>
    </table>

    <div class="footer">
        {{ $store->footer_note }}
    </div>

</body>

</html>
