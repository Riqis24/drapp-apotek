<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBundle extends Model
{
    /** @use HasFactory<\Database\Factories\ProductBundleFactory> */
    use HasFactory;
    protected $table = 'product_bundle'; // tabel pivot
    protected $fillable = [
        'bundle_product_id',
        'product_measurement_id',
        'quantity'
    ];

    public function productMeasurement()
    {
        return $this->belongsTo(ProductMeasurements::class, 'product_measurement_id');
    }

    public function bundle()
    {
        return $this->belongsTo(Product::class, 'bundle_product_id');
    }

    public function stocks()
    {
        return $this->hasMany(stocks::class, 'product_id', 'bundle_product_id');
    }
    
}
