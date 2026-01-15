<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreProfile extends Model
{
    /** @use HasFactory<\Database\Factories\StoreProfileFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'logo', // simpan path file logo
        'npwp',
        'owner',
        'footer_note', // untuk catatan di invoice
    ];
}
