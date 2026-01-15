<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivablePayments extends Model
{
    /** @use HasFactory<\Database\Factories\ReceivablePaymentsFactory> */
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'transaction_id',
        'amount_paid',
        'date',

    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function custtr()
    {
        return $this->belongsTo(CustTransactions::class, 'transaction_id', 'id');
    }
}
