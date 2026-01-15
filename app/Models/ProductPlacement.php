<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPlacement extends Model
{
    /** @use HasFactory<\Database\Factories\ProductPlacementFactory> */
    use HasFactory;

    protected $fillable = ['code', 'name', 'description'];

    public function productMeasurements()
    {
        return $this->hasMany(ProductMeasurements::class);
    }
}
