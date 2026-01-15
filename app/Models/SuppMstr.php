<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuppMstr extends Model
{
    /** @use HasFactory<\Database\Factories\SuppMstrFactory> */
    use HasFactory;

    protected $table = 'supp_mstr';
    protected $primaryKey = 'supp_mstr_id';

    protected $fillable = [
        'supp_mstr_code',
        'supp_mstr_name',
        'supp_mstr_addr',
        'supp_mstr_phone',
        'supp_mstr_npwp',
        'supp_mstr_ppn',
        'supp_mstr_active',
    ];
}
