<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppayDet extends Model
{
    /** @use HasFactory<\Database\Factories\AppayDetFactory> */
    use HasFactory;

    protected $table = 'appay_det';
    protected $primaryKey = 'appay_det_id';

    protected $fillable = [
        'appay_det_mstrid', //relasi ke appay_mstr
        'appay_det_apid', //relasi ke ap_mstr
        'appay_det_payamount',
    ];

    public function master()
    {
        return $this->belongsTo(AppayMstr::class, 'appay_det_mstrid');
    }

    public function ap()
    {
        return $this->belongsTo(ApMstr::class, 'appay_det_apid');
    }
}
