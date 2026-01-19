<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMeasurements extends Model
{
    /** @use HasFactory<\Database\Factories\ProductMeasurementsFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'measurement_id',
        'conversion',
        'placement_id',
        'last_buy_price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function measurement()
    {
        return $this->belongsTo(Measurement::class);
    }

    public function price()
    {
        return $this->hasOne(Price::class, 'product_measurement_id');
    }

    public function placement()
    {
        return $this->belongsTo(ProductPlacement::class, 'placement_id');
    }

    
}
