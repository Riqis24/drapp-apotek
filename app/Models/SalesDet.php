<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesDet extends Model
{
    /** @use HasFactory<\Database\Factories\SalesDetFactory> */
    use HasFactory;

    protected $table = 'sales_det';
    protected $primaryKey = 'sales_det_id';

    const CREATED_AT = 'sales_det_createdat';
    const UPDATED_AT = 'sales_det_updatedat';

    protected $fillable = [
        'sales_det_mstrid',
        'sales_det_productid',

        // UM & konversi
        'sales_det_um',
        'sales_det_umconv',

        // qty input & qty real (after conv)
        'sales_det_qty',
        'sales_det_qtyconv',
        'sales_det_qtyreturn',

        // harga
        'sales_det_price',
        'sales_det_priceconv',

        // diskon
        'sales_det_disctype',
        'sales_det_discvalue',
        'sales_det_discamt',

        // total
        'sales_det_subtotal',

        // batch & lokasi (penting untuk FIFO)
        'sales_det_locid',
        'sales_det_batchid',

        // single/bundle
        'sales_det_parentid',
        'sales_det_type',

        // resep
        'sales_det_comp',
        'sales_det_pmid',

        // custom timestamp
        'sales_det_createdat',
        'sales_det_updatedat',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'sales_det_productid');
    }

    public function master()
    {
        return $this->belongsTo(SalesMstr::class, 'sales_det_mstrid');
    }

    public function batch()
    {
        return $this->belongsTo(BatchMstr::class, 'sales_det_batchid', 'batch_mstr_id');
    }

    public function measurement()
    {
        return $this->belongsTo(Measurement::class, 'sales_det_um');
    }

    public function parent()
    {
        return $this->belongsTo(
            self::class,
            'sales_det_parentid'
        );
    }

    public function children()
    {
        return $this->hasMany(
            self::class,
            'sales_det_parentid'
        );
    }

    public function returns()
    {
        return $this->hasMany(SrDet::class, 'sr_det_sdid', 'sales_det_id');
    }

    public function getRemainingQtyAttribute()
    {
        return $this->sales_det_qty - $this->sales_det_qtyreturn;
    }

    protected function qtyReturned(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->returns()->sum('sr_det_qty')
        );
    }

    protected function qtyRemaining(): Attribute
    {
        return Attribute::make(
            get: fn() => max(
                0,
                $this->sales_det_qty - $this->qty_returned
            )
        );
    }

    public function prescription()
    {
        // Parameter: (Model Target, Foreign Key di tabel ini, Primary Key di tabel target)
        return $this->belongsTo(PresMstr::class, 'sales_det_pmid', 'pres_mstr_id');
    }
}
