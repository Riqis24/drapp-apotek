<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCat extends Model
{
    protected $table = 'product_cat';
    protected $primaryKey = 'product_cat_id'; // Sesuaikan jika PK bukan 'id'
    protected $fillable = ['product_cat_name', 'product_cat_desc'];

    // Relasi: Satu kategori memiliki banyak produk
    public function products(): HasMany
    {
        // Parameter: (ModelTujuan, ForeignKey_di_tabel_produk, LocalKey_di_tabel_kategori)
        return $this->hasMany(Product::class, 'category', 'product_cat_id');
    }
}
