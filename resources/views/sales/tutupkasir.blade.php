<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Tutup Kasir - {{ $data['kasir'] }}</title>
    <style>
        /* CSS KHUSUS UNTUK PRINTER THERMAL 58mm */
        @page {
            margin: 0;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 9pt;
            /* Ukuran sedikit diperbesar agar terbaca di kertas thermal */
            line-height: 1.2;
            width: 48mm;
            /* Lebar area cetak efektif printer 58mm biasanya 48mm */
            color: #000;
            margin: 0;
            padding: 5px;
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
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        td {
            vertical-align: top;
            word-wrap: break-word;
        }

        .item-table td:first-child {
            width: 50%;
        }

        .item-table td:last-child {
            width: 50%;
        }

        .grand-total {
            font-size: 10pt;
            border-top: 1px solid #000;
            margin-top: 2px;
        }

        .footer {
            margin-top: 10px;
            font-size: 8pt;
        }

        /* Memaksa browser menyembunyikan elemen non-cetak jika ada */
        @media print {
            .no-print {
                display: none;
            }

            body {
                width: 48mm;
            }
        }
    </style>
</head>

<body>
    <div class="text-center">
        <span class="fw-bold" style="font-size: 11pt;">{{ $apotek->name }}</span><br>
        Kasir: {{ $data['kasir'] ?? '-' }}<br>
        {{ $data['tanggal'] }}
    </div>

    <div class="divider"></div>

    <table class="item-table">
        <tr>
            <td>Bruto</td>
            <td class="text-right">{{ rupiah($data['bruto']) }}</td>
        </tr>
        <tr>
            <td>Disc</td>
            <td class="text-right">({{ rupiah($data['diskon']) }})</td>
        </tr>
        <tr>
            <td>PPN</td>
            <td class="text-right">{{ rupiah($data['ppn']) }}</td>
        </tr>
        <tr class="fw-bold grand-total">
            <td>OMZET</td>
            <td class="text-right">{{ rupiah($data['omzet']) }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <table class="item-table">
        <tr>
            <td>Modal awal</td>
            <td class="text-right">{{ rupiah($activeSession->opening_amount) }}</td>
        </tr>
        <tr>
            <td>Laci (Cash)</td>
            <td class="text-right">{{ rupiah($data['cash']) }}</td>
        </tr>
        <tr>
            <td>QRIS</td>
            <td class="text-right">{{ rupiah($data['qris']) }}</td>
        </tr>
        <tr>
            <td>Transfer</td>
            <td class="text-right">{{ rupiah($data['transfer']) }}</td>
        </tr>
        <tr>
            <td>C/D Card</td>
            <td class="text-right">{{ rupiah($data['creditcard']) }}</td>
        </tr>
    </table>

    <div class="divider"></div>
    <table>
        <tr>
            <td>Total Uang</td>
            <td class="text-right">{{ rupiah($activeSession->opening_amount + $data['ttlTransactions']) }}</td>
        </tr>
    </table>
    <div class="divider"></div>


    <div class="text-center footer">
        {{ now()->format('d/m/Y H:i:s') }}<br>
        -- LAPORAN TUTUP KASIR --
        <br><br>.
    </div>

    <script>
        window.onload = function() {
            window.print();

            // Memberikan jeda sebelum menutup window otomatis (jika dibuka via tab baru)
            window.onafterprint = function() {
                // window.close(); // Aktifkan jika ingin tab otomatis tertutup setelah print selesai/batal
            };
        }
    </script>
</body>

</html>
