<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stocks extends Model
{
    /** @use HasFactory<\Database\Factories\StocksFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'loc_id',
        'batch_id',
        'quantity',

    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function batch()
    {
        return $this->belongsTo(BatchMstr::class, 'batch_id', 'batch_mstr_id');
    }

    public function loc()
    {
        return $this->belongsTo(LocMstr::class, 'loc_id', 'loc_mstr_id');
    }
}
