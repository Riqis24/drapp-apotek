<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TsDet extends Model
{
    /** @use HasFactory<\Database\Factories\TsDetFactory> */
    use HasFactory;
    protected $table = 'ts_det';

    protected $primaryKey = 'ts_det_id';

    protected $fillable = [
        'ts_det_mstrid',
        'ts_det_productid',
        'ts_det_batchid',
        'ts_det_um',
        'ts_det_qty',
        // 'ts_det_qtyconv',
        'ts_det_note',
    ];

    public function master()
    {
        return $this->belongsTo(TsMstr::class, 'ts_det_mstrid');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'ts_det_productid');
    }

    public function batch()
    {
        return $this->belongsTo(BatchMstr::class, 'ts_det_batchid');
    }
}
