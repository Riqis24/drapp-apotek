<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoMstr extends Model
{
    /** @use HasFactory<\Database\Factories\PoMstrFactory> */
    use HasFactory;

    protected $table = 'po_mstr';
    protected $primaryKey = 'po_mstr_id';

    protected $fillable = [
        'po_mstr_nbr',
        'po_mstr_suppid',
        'po_mstr_payment',
        'po_mstr_duedate',
        'po_mstr_date',
        'po_mstr_eta',
        'po_mstr_status',
        'po_mstr_subtotal',
        'po_mstr_disctype', // percent | amount
        'po_mstr_discvalue',
        'po_mstr_discamt',
        'po_mstr_ppntype', // include | exclude | none
        'po_mstr_ppnrate',
        'po_mstr_ppnamt',
        'po_mstr_grandtotal',
        'po_mstr_note',
        'po_mstr_createdby',
        'po_mstr_approvedby',
        'po_mstr_createdat',
        'po_mstr_updatedat',
    ];

    public function bpbs()
    {
        return $this->hasMany(BpbMstr::class, 'bpb_mstr_poid', 'po_mstr_id');
    }

    public function details()
    {
        return $this->hasMany(PoDet::class, 'po_det_mstrid', 'po_mstr_id');
    }

    public function supplier()
    {
        return $this->belongsTo(SuppMstr::class, 'po_mstr_suppid', 'supp_mstr_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'po_mstr_createdby', 'user_mstr_id');
    }
}
