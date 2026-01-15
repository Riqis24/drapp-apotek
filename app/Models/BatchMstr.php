<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchMstr extends Model
{
    /** @use HasFactory<\Database\Factories\BatchMstrFactory> */
    use HasFactory;

    protected $table = 'batch_mstr';
    protected $primaryKey = 'batch_mstr_id';

    protected $fillable = [
        'batch_mstr_productid',
        'batch_mstr_no',
        'batch_mstr_expireddate',
    ];
}
