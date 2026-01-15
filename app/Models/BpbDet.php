<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BpbDet extends Model
{
    /** @use HasFactory<\Database\Factories\BpbDetFactory> */
    use HasFactory;

    protected $table = 'bpb_det';
    protected $primaryKey = 'bpb_det_id';

    protected $fillable = [
        'bpb_det_mstrid',
        'bpb_det_podetid',
        'bpb_det_productid',
        'bpb_det_locid',
        'bpb_det_qty',
        'bpb_det_um',
        'bpb_det_umconv',
        'bpb_det_qtyrcvd',
        'bpb_det_price',
        'bpb_det_priceconv',
        'bpb_det_disctype',
        'bpb_det_discvalue',
        'bpb_det_discamt',
        'bpb_det_total',
        'bpb_det_updateprice',
        'bpb_det_batch',
        'bpb_det_expired',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'bpb_det_productid', 'id');
    }

    public function batch()
    {
        return $this->belongsTo(BatchMstr::class, 'bpb_det_batch', 'batch_mstr_id');
    }

    public function podet()
    {
        return $this->belongsTo(PoDet::class, 'bpb_det_podetid', 'po_det_id');
    }

    public function returns()
    {
        return $this->hasMany(PrDet::class, 'pr_det_bpbdetid', 'bpb_det_id');
    }

    protected function qtyReturned(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->returns()->sum('pr_det_qty')
        );
    }

    protected function qtyRemaining(): Attribute
    {
        return Attribute::make(
            get: fn() => max(
                0,
                $this->bpb_det_qty - $this->qty_returned
            )
        );
    }
}
