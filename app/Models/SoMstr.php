<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoMstr extends Model
{
    /** @use HasFactory<\Database\Factories\SoMstrFactory> */
    use HasFactory;


    protected $table = 'so_mstr';
    protected $primaryKey = 'so_mstr_id';

    protected $fillable = [
        'so_mstr_nbr',
        'so_mstr_date',
        'so_mstr_locid',
        'so_mstr_status',
        'so_mstr_note',
        'so_mstr_createdby',
        'so_mstr_approvedby',
        'so_mstr_approvedat',
    ];

    /* ================== RELATIONS ================== */

    // detail opname
    public function details()
    {
        return $this->hasMany(
            SoDet::class,
            'so_det_mstrid',
            'so_mstr_id'
        );
    }

    // lokasi opname
    public function location()
    {
        return $this->belongsTo(
            LocMstr::class,
            'so_mstr_locid',
            'loc_mstr_id'
        );
    }

    // user pembuat
    public function createdBy()
    {
        return $this->belongsTo(
            User::class,
            'so_mstr_createdby',
            'user_mstr_id'
        );
    }

    // user approver
    public function approvedBy()
    {
        return $this->belongsTo(
            User::class,
            'so_mstr_approvedby',
            'user_mstr_id'
        );
    }

    // 1 opname → 1 adjustment (optional)
    public function adjustment()
    {
        return $this->hasOne(
            SaMstr::class,
            'sa_mstr_ref',
            'so_mstr_id'
        );
    }

    public function stockTransactions()
    {
        return $this->morphMany(StockTransactions::class, 'source');
    }
}
