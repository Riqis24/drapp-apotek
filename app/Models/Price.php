<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    /** @use HasFactory<\Database\Factories\PriceFactory> */
    use HasFactory;

    protected $fillable = [
        'price',
        'product_measurement_id',
    ];

    public function productMeasurement()
    {
        return $this->belongsTo(ProductMeasurements::class);
    }

}
