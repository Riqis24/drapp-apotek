<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialRecords extends Model
{
    /** @use HasFactory<\Database\Factories\FinancialRecordsFactory> */
    use HasFactory;

    protected $fillable = [
        'date',
        'type',
        'data_source',
        'description',
        'amount',
        'source_type',
        'source_id',
    ];

    public function source()
    {
        return $this->morphTo();
    }
}
