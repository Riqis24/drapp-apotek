<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Invoice - {{ $transaction->sales_mstr_nbr }}</title>
    <style>
        /* Desain Khusus Printer Thermal 80mm/58mm */
        @page {
            size: auto;
            margin: 0mm;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            width: 72mm;
            /* Standar struk 80mm */
            margin: 0 auto;
            padding: 5mm;
            font-size: 10pt;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .item-name {
            font-weight: bold;
            display: block;
        }

        .item-detail {
            font-size: 8pt;
            color: #333;
            padding-left: 10px;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="text-center">
        <h3 style="margin-bottom: 2px;">{{ $apotek->name }}</h3>
        <p style="margin-top: 0; font-size: 8pt;">{{ $apotek->address }}</p>
    </div>

    <div class="divider"></div>
    <table>
        @foreach ($details as $item)
            <tr>
                <td colspan="3">
                    {{-- LOGIKA NAMA: Racikan vs Produk Reguler --}}
                    <span class="item-name">
                        @if ($item->sales_det_pmid && $item->prescription)
                            {{ $item->prescription->pres_mstr_name }}
                            <small>(Racikan)</small>
                        @else
                            {{ $item->product->name }}
                        @endif
                    </span>

                    {{-- LOOP DETAIL BAHAN (Hanya jika Racikan) --}}
                    @if ($item->sales_det_pmid && $item->prescription)
                        @foreach ($item->prescription->details as $bahan)
                            <span class="item-detail" style="font-size: 8pt; display: block; padding-left: 5px;">
                                - {{ $bahan->product->name }} ({{ (float) $bahan->pres_det_qty }})
                            </span>
                        @endforeach
                    @endif
                </td>
            </tr>
            <tr>
                <td style="width: 30%;">{{ (float) $item->sales_det_qty }} x</td>
                @if (!empty($item->sales_det_parentid))
                    <td></td>
                @else
                    <td class="text-right">{{ number_format($item->sales_det_price, 0, ',', '.') }}</td>
                @endif
                @if (!empty($item->sales_det_parentid))
                    <td></td>
                @else
                    <td class="text-right">
                        {{ number_format($item->sales_det_subtotal + $item->sales_det_discamt, 0, ',', '.') }}</td>
                @endif
            </tr>

            {{-- TAMPILKAN DISKON JIKA ADA --}}
            @if ($item->sales_det_discamt > 0)
                <tr>
                    <td colspan="2" class="text-right" style="font-size: 8pt; font-style: italic;">Disc:</td>
                    <td class="text-right" style="font-size: 8pt; font-style: italic;">
                        -{{ number_format($item->sales_det_discamt, 0, ',', '.') }}
                    </td>
                </tr>
            @endif
        @endforeach
    </table>
    <div class="divider"></div>

    <table>
        <tr>
            <td class="text-right">Total:</td>
            <td class="text-right fw-bold">{{ number_format($transaction->sales_mstr_grandtotal, 0, ',', '.') }}</td>
        </tr>
        {{-- Jika ada pembayaran tunai, tampilkan kembalian --}}
        @if ($transaction->sales_mstr_paidamt > 0)
            <tr>
                <td class="text-right" style="font-size: 9pt;">Disc:</td>
                <td class="text-right" style="font-size: 9pt;">
                    {{ number_format($transaction->sales_mstr_discamt, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="text-right" style="font-size: 9pt;">PPN:</td>
                <td class="text-right" style="font-size: 9pt;">
                    {{ number_format($transaction->sales_mstr_ppnamt, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="text-right" style="font-size: 9pt;">Bayar:</td>
                <td class="text-right" style="font-size: 9pt;">
                    {{ number_format($transaction->sales_mstr_paidamt, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="text-right" style="font-size: 9pt;">Kembali:</td>
                <td class="text-right" style="font-size: 9pt;">
                    {{ number_format($transaction->sales_mstr_changeamt, 0, ',', '.') }}
                </td>
            </tr>
        @endif
    </table>

    <div class="divider"></div>
    <p class="text-center" style="font-size: 8pt;">{{ $apotek->footer_note }}</p>

    {{-- Script Otomatis Cetak --}}
    <script>
        // Menutup tab secara otomatis setelah dialog print selesai (di-print atau di-cancel)
        window.onafterprint = function() {
            window.close();
        };

        // Fallback untuk browser lama
        setTimeout(function() {
            if (!window.closed) {
                // window.close(); // Aktifkan jika ingin menutup paksa setelah 3 detik
            }
        }, 3000);
    </script>
</body>

</html>
