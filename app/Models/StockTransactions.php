<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransactions extends Model
{
    /** @use HasFactory<\Database\Factories\StockTransactionsFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'loc_id',
        'batch_id',
        'type',
        'quantity',
        'note',
        'date',
        'source_type',
        'source_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function batch()
    {
        return $this->belongsTo(BatchMstr::class, 'batch_id');
    }

    public function location()
    {
        return $this->belongsTo(LocMstr::class, 'loc_id');
    }

    public function source()
    {
        return $this->morphTo();
    }
}
