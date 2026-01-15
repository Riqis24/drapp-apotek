<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppayMstr extends Model
{
    /** @use HasFactory<\Database\Factories\AppayMstrFactory> */
    use HasFactory;

    protected $table = 'appay_mstr';
    protected $primaryKey = 'appay_mstr_id';

    protected $fillable = [
        'appay_mstr_nbr',
        'appay_mstr_date',
        'appay_mstr_suppid',
        'appay_mstr_total',
        'appay_mstr_method', // cash / transfer / bank / giro
        'appay_mstr_refno',  // no transfer / giro
        'appay_mstr_note',
        'appay_mstr_createdby',
    ];

    public function details()
    {
        return $this->hasMany(AppayDet::class, 'appay_det_mstrid');
    }

    public function supplier()
    {
        return $this->belongsTo(SuppMstr::class, 'appay_mstr_suppid');
    }
}
