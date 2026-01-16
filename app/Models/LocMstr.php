<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocMstr extends Model
{
    /** @use HasFactory<\Database\Factories\LocMstrFactory> */
    use HasFactory;

    protected $table = 'loc_mstr';
    protected $primaryKey = 'loc_mstr_id';
    protected $fillable = [
        'loc_mstr_code',
        'loc_mstr_name',
        'loc_mstr_active',
        'loc_mstr_isvisible'
    ];
}
