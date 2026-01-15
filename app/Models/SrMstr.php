<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SrMstr extends Model
{
    /** @use HasFactory<\Database\Factories\SrMstrFactory> */
    use HasFactory;

    protected $table = 'sr_mstr';
    protected $primaryKey = 'sr_mstr_id';

    protected $fillable = [
        'sr_mstr_nbr',
        'sr_mstr_smid',
        'sr_mstr_custid',
        'sr_mstr_date',
        'sr_mstr_reason',
        'sr_mstr_createdby',
    ];

    /* ================= RELATIONS ================= */

    public function sales()
    {
        return $this->belongsTo(
            SalesMstr::class,
            'sr_mstr_smid',
            'sales_mstr_id'
        );
    }

    public function details()
    {
        return $this->hasMany(
            SrDet::class,
            'sr_det_mstrid',
            'sr_mstr_id'
        );
    }

    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'sr_mstr_createdby',
            'user_mstr_id'
        );
    }

    public function customer()
    {
        return $this->belongsTo(
            Customer::class,
            'sr_mstr_custid',
            'id'
        );
    }
}
