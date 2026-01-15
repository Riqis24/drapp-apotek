<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApDet extends Model
{
    /** @use HasFactory<\Database\Factories\ApDetFactory> */
    use HasFactory;

    protected $table = 'ap_det';
    protected $primaryKey = 'ap_det_id';

    protected $fillable = [
        'ap_det_mstrid',
        'ap_det_paydate',
        'ap_det_payamount',
        'ap_det_paymethod',
        'ap_det_note',
        'ap_det_createdby',
    ];
}
