<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApMstr extends Model
{
    /** @use HasFactory<\Database\Factories\ApMstrFactory> */
    use HasFactory;

    protected $table = 'ap_mstr';
    protected $primaryKey = 'ap_mstr_id';

    protected $fillable = [
        'ap_mstr_id',
        'ap_mstr_nbr',
        'ap_mstr_reftype', // bpb
        'ap_mstr_refid', // bpb_mstr_id
        'ap_mstr_suppid',
        'ap_mstr_date',
        'ap_mstr_duedate',
        'ap_mstr_amount',
        'ap_mstr_paid',
        'ap_mstr_balance',
        'ap_mstr_status',
        'ap_mstr_createdby',
    ];

    public function supplier()
    {
        return $this->belongsTo(SuppMstr::class, 'ap_mstr_suppid', 'supp_mstr_id');
    }

    public function bpbmstr()
    {
        return $this->belongsTo(BpbMstr::class, 'ap_mstr_refid', 'bpb_mstr_id');
    }


}
