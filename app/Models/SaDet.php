<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaDet extends Model
{
    /** @use HasFactory<\Database\Factories\SaDetFactory> */
    use HasFactory;

    protected $table = 'sa_det';
    protected $primaryKey = 'sa_det_id';

    protected $fillable = [
        'sa_det_mstrid',
        'sa_det_productid',
        'sa_det_batchid',
        'sa_det_qtysystem',
        'sa_det_qtyphysical',
        'sa_det_qtydiff',
        'sa_det_note',
    ];

    /* ================== RELATIONS ================== */

    public function master()
    {
        return $this->belongsTo(
            SaMstr::class,
            'sa_det_mstrid',
            'sa_mstr_id'
        );
    }

    public function product()
    {
        return $this->belongsTo(
            Product::class,
            'sa_det_productid'
        );
    }

    public function batch()
    {
        return $this->belongsTo(
            BatchMstr::class,
            'sa_det_batchid',
            'batch_mstr_id'
        );
    }
}
