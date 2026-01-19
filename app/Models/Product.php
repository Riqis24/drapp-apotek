<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'name',
        'category',
        'type',
        'measurement_id',
        'margin',
        'is_stockable',
        'is_visible',
    ];

    public function measurement()
    {
        return $this->belongsTo(Measurement::class);
    }

    public function measurements()
    {
        return $this->belongsToMany(Measurement::class, 'product_measurements');
    }

    public function stock()
    {
        return $this->belongsTo(stocks::class, 'id', 'product_id');
    }

    public function cat()
    {
        return $this->belongsTo(ProductCat::class, 'category', 'product_cat_id');
    }

    public function stockTransactions()
    {
        return $this->hasMany(StockTransactions::class, 'product_id');
    }

    public function getStockAttribute()
    {
        return $this->stockTransactions()
            ->selectRaw("
            SUM(
                CASE
                    WHEN type = 'in' THEN quantity
                    WHEN type = 'out' THEN -quantity
                    ELSE quantity
                END
            ) as stock
        ")
            ->value('stock') ?? 0;
    }

    public function stocks()
    {
        return $this->hasMany(stocks::class, 'product_id');
    }

    // Jika produk ini bundle, ambil komponennya
    public function bundleItems()
    {
        return $this->hasMany(ProductBundle::class, 'bundle_product_id', 'id');
    }

    public function isBundle()
    {
        return $this->type === 'bundle';
    }

    // Di model Product.php
    public function ProductMeasurements()
    {
        return $this->hasMany(ProductMeasurements::class, 'product_id', 'id');
    }
}
