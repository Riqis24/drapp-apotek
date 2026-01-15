<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PrDet extends Model
{
    /** @use HasFactory<\Database\Factories\PrDetFactory> */
    use HasFactory;

    protected $table = 'pr_det';
    protected $primaryKey = 'pr_det_id';

    protected $fillable = [
        'pr_det_mstrid',
        'pr_det_bpbdetid',
        'pr_det_productid',
        'pr_det_um',
        'pr_det_umconv',
        'pr_det_qty',
        'pr_det_qtyconv',
        'pr_det_batchid',
        'pr_det_price',
        'pr_det_subtotal',
    ];

    /* ================= RELATIONS ================= */

    public function master()
    {
        return $this->belongsTo(
            PrMstr::class,
            'pr_det_mstrid',
            'pr_mstr_id'
        );
    }

    public function bpbDetail()
    {
        return $this->belongsTo(
            BpbDet::class,
            'pr_det_bpbdetid',
            'bpb_det_id'
        );
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'pr_det_productid');
    }

    public function measurement()
    {
        return $this->belongsTo(Measurement::class, 'pr_det_um');
    }

    public function batch()
    {
        return $this->belongsTo(
            BatchMstr::class,
            'pr_det_batchid',
            'batch_mstr_id'
        );
    }
}
