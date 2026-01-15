<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PresDet extends Model
{
    use HasFactory;

    protected $table = 'pres_det';
    protected $primaryKey = 'pres_det_id';

    protected $fillable = [
        'pres_det_mstrid',
        'pres_det_productid',
        'pres_det_um',
        'pres_det_batchid',
        'pres_det_qty',
        'pres_det_price',
    ];

    /* =====================
     * RELATIONS
     * ===================== */

    public function master()
    {
        return $this->belongsTo(
            PresMstr::class,
            'pres_det_mstrid',
            'pres_mstr_id'
        );
    }

    public function product()
    {
        return $this->belongsTo(
            Product::class,
            'pres_det_productid'
        );
    }

    public function measurement()
    {
        return $this->belongsTo(
            Measurement::class,
            'pres_det_um'
        );
    }

    public function batch()
    {
        return $this->belongsTo(
            BatchMstr::class,
            'pres_det_batchid'
        );
    }
}
