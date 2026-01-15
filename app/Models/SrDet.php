<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SrDet extends Model
{
    /** @use HasFactory<\Database\Factories\SrDetFactory> */
    use HasFactory;

    protected $table = 'sr_det';
    protected $primaryKey = 'sr_det_id';

    protected $fillable = [
        'sr_det_mstrid',
        'sr_det_sdid',
        'sr_det_productid',
        'sr_det_um',
        'sr_det_umconv',
        'sr_det_qty',
        'sr_det_qtyconv',
        'sr_det_batchid',
        'sr_det_price',
        'sr_det_subtotal',
    ];

    /* ================= RELATIONS ================= */

    public function master()
    {
        return $this->belongsTo(
            SrMstr::class,
            'sr_det_mstrid',
            'sr_mstr_id'
        );
    }

    public function salesDetail()
    {
        return $this->belongsTo(
            SalesDet::class,
            'sr_det_sdid',
            'sales_det_id'
        );
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'sr_det_productid');
    }

    public function measurement()
    {
        return $this->belongsTo(Measurement::class, 'sr_det_um');
    }

    public function batch()
    {
        return $this->belongsTo(
            BatchMstr::class,
            'sr_det_batchid',
            'batch_mstr_id'
        );
    }
}
