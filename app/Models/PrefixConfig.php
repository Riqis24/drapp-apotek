<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrefixConfig extends Model
{
    /** @use HasFactory<\Database\Factories\PrefixConfigFactory> */
    use HasFactory;


    protected $fillable = ['name', 'pre', 'last_number'];
}
