<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        /* 1. Hilangkan margin kertas bawaan PDF */
        @page {
            margin: 0;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 8pt;
            /* Ukuran aman untuk 58mm */
            line-height: 1.2;
            width: 100%;
            color: #000;
            margin: 0;
            /* Gunakan padding kecil agar teks tidak mepet ke pinggir fisik kertas */
            padding: 5px 2px;
            box-sizing: border-box;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 4px 0;
        }

        /* 2. Penting: Gunakan fixed layout agar table tidak melebar keluar kertas */
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        td {
            vertical-align: top;
            overflow: hidden;
            word-wrap: break-word;
            /* Teks akan turun ke bawah jika terlalu panjang */
        }

        /* Atur proporsi kolom rincian barang */
        .item-table td:first-child {
            width: 60%;
            /* Nama Barang */
        }

        .item-table td:last-child {
            width: 40%;
            /* Harga/Total */
        }

        .grand-total {
            font-size: 9pt;
            border-top: 1px solid #000;
        }

        .footer {
            margin-top: 10px;
            font-size: 7pt;
        }

        /* Memberikan ruang kosong di akhir agar tidak terpotong cutter printer */
        .spacer {
            height: 30px;
        }
    </style>
</head>

<body>

    <div class="text-center">
        <span class="fw-bold" style="font-size: 10pt;">{{ $apotek->name }}</span><br>
        Kasir: {{ $kasir }}<br>
        {{ $tanggal }}
    </div>

    <div class="divider"></div>

    <table class="item-table">
        <tr>
            <td>Bruto</td>
            <td class="text-right">{{ number_format($bruto) }}</td>
        </tr>
        <tr>
            <td>Disc</td>
            <td class="text-right">({{ number_format($diskon) }})</td>
        </tr>
        <tr>
            <td>PPN</td>
            <td class="text-right">{{ number_format($ppn) }}</td>
        </tr>
        <tr class="fw-bold grand-total">
            <td>OMZET</td>
            <td class="text-right">{{ number_format($omzet) }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <table class="item-table">
        <tr>
            <td>Bayar</td>
            <td class="text-right">{{ number_format($tunai + $kembalian) }}</td>
        </tr>
        <tr>
            <td>Kembali</td>
            <td class="text-right">{{ number_format($kembalian) }}</td>
        </tr>
        <tr class="fw-bold">
            <td>LACI</td>
            <td class="text-right">{{ number_format($tunai) }}</td>
        </tr>
        <tr>
            <td>PIUTANG</td>
            <td class="text-right">{{ number_format($piutang) }}</td>
        </tr>
    </table>

    <div class="divider"></div>
    <div class="text-center fw-bold">RINCIAN BARANG TERJUAL</div>
    <div class="divider"></div>

    <table class="item-table">
        @foreach ($rincian as $item)
            <tr>
                <td colspan="2">{{ $item->product_name }}</td>
            </tr>
            <tr>
                <td>{{ $item->total_qty }}x</td>
                <td class="text-right">{{ number_format($item->total_price) }}</td>
            </tr>
        @endforeach
    </table>

    <div class="divider"></div>

    <div class="text-center footer">
        {{ now()->format('d/m/Y H:i') }}<br>
        -- LAPORAN TUTUP KASIR --
    </div>

</body>

</html>
