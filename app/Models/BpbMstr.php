<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BpbMstr extends Model
{
    /** @use HasFactory<\Database\Factories\BpbMstrFactory> */
    use HasFactory;

    protected $table = 'bpb_mstr';
    protected $primaryKey = 'bpb_mstr_id';

    const CREATED_AT = 'bpb_mstr_createdat';
    const UPDATED_AT = 'bpb_mstr_updatedat';

    protected $fillable = [
        'bpb_mstr_nbr',
        'bpb_mstr_poid',
        'bpb_mstr_locid',
        'bpb_mstr_suppid',

        'bpb_mstr_nofaktur',
        'bpb_mstr_nosj',
        'bpb_mstr_payment',
        'bpb_mstr_duedate',

        'bpb_mstr_date',
        'bpb_mstr_status',

        'bpb_mstr_subtotal',
        'bpb_mstr_dpp',

        'bpb_mstr_disctype',
        'bpb_mstr_discvalue',
        'bpb_mstr_discamt',

        'bpb_mstr_ppntype',
        'bpb_mstr_ppnrate',
        'bpb_mstr_ppnamt',

        'bpb_mstr_grandtotal',

        'bpb_mstr_note',
        'bpb_mstr_createdby',
        'bpb_mstr_createdat',
        'bpb_mstr_updatedat',
    ];

    public function supplier()
    {
        return $this->belongsTo(SuppMstr::class, 'bpb_mstr_suppid', 'supp_mstr_id');
    }

    public function po()
    {
        return $this->belongsTo(PoMstr::class, 'bpb_mstr_poid', 'po_mstr_id');
    }

    public function batch()
    {
        return $this->belongsTo(BatchMstr::class, 'bpb_mstr_batch', 'batch_mstr_id');
    }

    public function details()
    {
        return $this->hasMany(BpbDet::class, 'bpb_det_mstrid', 'bpb_mstr_id');
    }
}
