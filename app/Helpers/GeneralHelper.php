<?php

use App\Models\stocks;
use App\Models\StockTransactions;

if (!function_exists('rupiah')) {
  function rupiah($number)
  {
    return 'Rp. ' . number_format($number, 0, ',', '.');
  }
}

if (! function_exists('numfmt')) {
  /**
   * Format number:
   * - tanpa desimal jika bilangan bulat
   * - dengan desimal jika ada nilai pecahan
   *
   * Contoh:
   *  numfmt(10)        -> 10
   *  numfmt(10.5)      -> 10,5
   *  numfmt(10.25)     -> 10,25
   *  numfmt(1000)      -> 1.000
   *  numfmt(1000.75)   -> 1.000,75
   */
  function numfmt($value, $maxDecimal = 2)
  {
    if ($value === null || $value === '') {
      return '0';
    }

    $value = (float) $value;

    // Cek apakah bilangan bulat
    if (floor($value) == $value) {
      return number_format($value, 0, ',', '.');
    }

    // Ada desimal → trim trailing zero
    $formatted = number_format($value, $maxDecimal, ',', '.');

    return rtrim(rtrim($formatted, '0'), ',');
  }
}

if (!function_exists('update_stock')) {
  /**
   * Update stok berdasarkan transaksi masuk/keluar
   *
   * @param int $productId
   * @param float $qty
   * @param string $type 'in' atau 'out'
   * @return void
   */
  function recalculateStock($productId)
  {
    $final = StockTransactions::where('product_id', $productId)->sum('quantity');
    // $in = StockTransactions::where('product_id', $productId)->where('type', 'in')->sum('quantity');
    // $out = StockTransactions::where('product_id', $productId)->where('type', 'out')->sum('quantity');
    // $adj = StockTransactions::where('product_id', $productId)->where('type', 'adjustment')->sum('quantity');

    // $final = $in + $out + $adj;

    // Cegah stok negatif (optional, tergantung kebijakan kamu)
    if ($final < 0) {
      $final = 0;
    }

    stocks::updateOrCreate(
      ['product_id' => $productId],
      ['quantity' => $final]
    );
  }
}
