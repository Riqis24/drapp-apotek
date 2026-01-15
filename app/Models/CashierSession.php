<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashierSession extends Model
{
    /** @use HasFactory<\Database\Factories\CashierSessionFactory> */
    use HasFactory;
    protected $fillable = [
        'user_id',
        'loc_id',
        'opening_amount',
        'closing_amount',
        'transactions_total',
        'discrepancy',
        'status',
        'opened_at',
        'closed_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(SalesMstr::class, 'cashier_session_id');
    }
}
