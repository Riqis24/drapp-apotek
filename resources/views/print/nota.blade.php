<!DOCTYPE html>
<html>

<head>
    <title>Nota Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 4px;
        }

        .center {
            text-align: center;
        }

        .no-border {
            border: none !important;
        }
    </style>
</head>

<body>
    
    <div class="center">
        <strong>{{ $store->name }}</strong><br>
        {{ $store->address }}<br>
        Telp: {{ $store->phone }}
    </div>

    <hr>

    <table class="no-border">
        <tr class="no-border">
            <td class="no-border">
                <strong>No Nota:</strong> {{ $custTr->invoice_number }}<br>
                <strong>Tanggal:</strong> {{ $custTr->date }}
            </td>
            <td class="no-border">
                <strong>Pembeli:</strong> {{ $custTr->customer->name }}
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

    <div style="margin-top:10px; text-align:center; font-size:10px;">
        {{ $store->footer_note }}
    </div>

</body>

</html>
