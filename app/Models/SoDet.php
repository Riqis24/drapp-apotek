<?php

namespace App\Models;

use App\Models\Product;
use App\Models\BatchMstr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SoDet extends Model
{
    /** @use HasFactory<\Database\Factories\SoDetFactory> */
    use HasFactory;

    protected $table = 'so_det';
    protected $primaryKey = 'so_det_id';

    protected $fillable = [
        'so_det_mstrid',
        'so_det_productid',
        'so_det_batchid',
        'so_det_qtysystem',
        'so_det_qtyphysical',
        'so_det_note',
    ];

    /* ================== RELATIONS ================== */

    public function master()
    {
        return $this->belongsTo(
            SoMstr::class,
            'so_det_mstrid',
            'so_mstr_id'
        );
    }

    public function product()
    {
        return $this->belongsTo(
            Product::class,
            'so_det_productid'
        );
    }

    public function batch()
    {
        return $this->belongsTo(
            BatchMstr::class,
            'so_det_batchid',
            'batch_mstr_id'
        );
    }

   
}
