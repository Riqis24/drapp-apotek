<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesMstr extends Model
{
    /** @use HasFactory<\Database\Factories\SalesMstrFactory> */
    use HasFactory;

    protected $table = 'sales_mstr';
    protected $primaryKey = 'sales_mstr_id';

    const CREATED_AT = 'sales_mstr_createdat';
    const UPDATED_AT = 'sales_mstr_updatedat';

    protected $fillable = [
        'sales_mstr_nbr',
        'sales_mstr_date',
        'sales_mstr_custid',
        'sales_mstr_locid',
        'sales_mstr_pmid', //resep
        'sales_mstr_subtotal',
        'sales_mstr_discamt',

        'sales_mstr_paidamt',
        'sales_mstr_changeamt',

        'sales_mstr_ppnamt',
        'sales_mstr_grandtotal',
        'sales_mstr_paymenttype',
        'sales_mstr_paymentmethod',
        'sales_mstr_status',
        'sales_mstr_note',
        'cashier_session_id',

        'sales_mstr_createdby'
    ];

    public function loc()
    {
        return $this->belongsTo(LocMstr::class, 'sales_mstr_locid', 'loc_mstr_id');
    }

    public function details()
    {
        return $this->hasMany(SalesDet::class, 'sales_det_mstrid', 'sales_mstr_id');
    }

    public function prescription()
    {
        return $this->belongsTo(
            PresMstr::class,
            'pres_mstr_id',
            'pres_mstr_id'
        );
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'sales_mstr_custid', 'id');
    }

    public function createdby()
    {
        return $this->belongsTo(
            User::class,
            'sales_mstr_createdby',
            'user_mstr_id'
        );
    }
}
