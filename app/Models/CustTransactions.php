<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustTransactions extends Model
{
    /** @use HasFactory<\Database\Factories\CustTransactionsFactory> */
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'date',
        'method_payment',
        'status',
        'total',
        'paid',
        'change',
        'debt',
    ];


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getHutangAttribute()
    {
        return max(0, $this->total - $this->paid);
    }

    public static function generateInvoiceNumber(Carbon $date)
    {
        $prefix = 'INV-' . $date->format('Ymd');

        $latest = self::whereDate('created_at', $date)
            ->where('invoice_number', 'like', $prefix . '%')
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($latest && preg_match('/(\d+)$/', $latest->invoice_number, $matches)) {
            $lastNumber = (int) $matches[1];
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }

        return $prefix . '-' . $nextNumber;
    }
}
