<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArpayDet extends Model
{
    /** @use HasFactory<\Database\Factories\ArpayDetFactory> */
    use HasFactory;

    protected $table = 'arpay_det';
    protected $primaryKey = 'arpay_det_id';

    protected $fillable = [
        'arpay_det_mstrid', //relasi ke arpay_mstr
        'arpay_det_arid', //relasi ke ap_mstr
        'arpay_det_amount',
    ];

    public function master()
    {
        return $this->belongsTo(ArpayMstr::class, 'arpay_det_mstrid');
    }

    public function ar()
    {
        return $this->belongsTo(ArMstr::class, 'arpay_det_apid');
    }
}
