<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArpayMstr extends Model
{
    /** @use HasFactory<\Database\Factories\ArpayMstrFactory> */
    use HasFactory;

    protected $table = 'arpay_mstr';
    protected $primaryKey = 'arpay_mstr_id';

    protected $fillable = [
        'arpay_mstr_nbr',
        'arpay_mstr_date',
        'arpay_mstr_customerid',
        'arpay_mstr_amount',
        'arpay_mstr_method', // cash / transfer / bank / giro
        'arpay_mstr_ref',  // no transfer / giro
        'arpay_mstr_note',
        'arpay_mstr_createdby',
    ];

    public function details()
    {
        return $this->hasMany(ArPayDet::class, 'arpay_det_mstrid');
    }
}
