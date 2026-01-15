<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrMstr extends Model
{
    /** @use HasFactory<\Database\Factories\PrMstrFactory> */
    use HasFactory;

    protected $table = 'pr_mstr';
    protected $primaryKey = 'pr_mstr_id';

    protected $fillable = [
        'pr_mstr_nbr',
        'pr_mstr_poid',
        'pr_mstr_bpbid',
        'pr_mstr_suppid',
        'pr_mstr_date',
        'pr_mstr_reason',
        'pr_mstr_createdby',
    ];

    /* ================= RELATIONS ================= */

    public function bpb()
    {
        return $this->belongsTo(
            BpbMstr::class,
            'pr_mstr_bpbid',
            'bpb_mstr_id'
        );
    }
    public function po()
    {
        return $this->belongsTo(
            PoMstr::class,
            'pr_mstr_poid',
            'po_mstr_id'
        );
    }

    public function details()
    {
        return $this->hasMany(
            PrDet::class,
            'pr_det_mstrid',
            'pr_mstr_id'
        );
    }

    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'pr_mstr_createdby',
            'user_mstr_id'
        );
    }

    public function supplier()
    {
        return $this->belongsTo(
            SuppMstr::class,
            'pr_mstr_suppid',
            'supp_mstr_id'
        );
    }
}
