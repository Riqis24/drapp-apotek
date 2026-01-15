<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTransactions extends Model
{
    /** @use HasFactory<\Database\Factories\ProductTransactionsFactory> */
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'product_id',
        'measurement_id',
        'quantity',
        'unit_price',
        'subtotal'
    ];

    public function custTransaction()
    {
        return $this->belongsTo(CustTransactions::class, 'transaction_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function measurement()
    {
        return $this->belongsTo(Measurement::class, 'measurement_id', 'id');
    }
}
