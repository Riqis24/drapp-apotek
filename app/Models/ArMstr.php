<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArMstr extends Model
{
    /** @use HasFactory<\Database\Factories\ArMstrFactory> */
    use HasFactory;

    protected $table = 'ar_mstr';
    protected $primaryKey = 'ar_mstr_id';

    protected $fillable = [
        'ar_mstr_nbr',
        'ar_mstr_salesid',          // sales
        'ar_mstr_customerid', // cust
        'ar_mstr_date',
        'ar_mstr_duedate',
        'ar_mstr_amount',
        'ar_mstr_paid',
        'ar_mstr_balance',
        'ar_mstr_status',
        'ar_mstr_createdby',
    ];

    public function payments()
    {
        return $this->hasMany(ArpayDet::class, 'arpay_det_arid');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'ar_mstr_customerid', 'id');
    }

    public function sales()
    {
        return $this->belongsTo(SalesMstr::class, 'ar_mstr_salesid', 'sales_mstr_id');
    }
}
