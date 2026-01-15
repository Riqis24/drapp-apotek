<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoDet extends Model
{
    /** @use HasFactory<\Database\Factories\PoDetFactory> */
    use HasFactory;

    protected $table = 'po_det';
    protected $primaryKey = 'po_det_id';

    protected $fillable = [
        'po_det_id',
        'po_det_mstrid',
        'po_det_productid',
        'po_det_um',
        'po_det_umconv',
        'po_det_qty',
        'po_det_qtyrcvd',
        'po_det_qtyremain',
        'po_det_price',
        'po_det_disctype', // percent | amount
        'po_det_discvalue',
        'po_det_discamt',
        'po_det_total',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'po_det_productid', 'id');
    }

    public function pm()
    {
        return $this->belongsTo(ProductMeasurements::class, 'po_det_productid', 'product_id')
            ->whereColumn('measurement_id', 'po_det_um'); // Membandingkan ID Satuan
    }

    public function um()
    {
        return $this->belongsTo(Measurement::class, 'po_det_um', 'id');
    }

    public function getRemainingQtyAttribute()
    {
        return $this->po_det_qty - $this->po_det_qtyreturn;
    }

    public function bpbdet()
    {
        return $this->hasOne(BpbDet::class, 'bpb_det_podetid', 'po_det_id');
    }
}
