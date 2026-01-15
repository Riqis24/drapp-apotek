<?php

namespace App\Models;

use App\Models\SaDet;
// use App\Models\SaDet as ModelsSaDet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaMstr extends Model
{
    /** @use HasFactory<\Database\Factories\SaMstrFactory> */
    use HasFactory;

    protected $table = 'sa_mstr';
    protected $primaryKey = 'sa_mstr_id';

    protected $fillable = [
        'sa_mstr_nbr',
        'sa_mstr_date',
        'sa_mstr_locid',
        'sa_mstr_ref',
        'sa_mstr_reason',
        'sa_mstr_status',
        'sa_mstr_createdby',
    ];

    /* ================== RELATIONS ================== */

    public function details()
    {
        return $this->hasMany(
            SaDet::class,
            'sa_det_mstrid',
            'sa_mstr_id'
        );
    }

    public function location()
    {
        return $this->belongsTo(
            LocMstr::class,
            'sa_mstr_locid',
            'loc_mstr_id'
        );
    }

    public function createdBy()
    {
        return $this->belongsTo(
            User::class,
            'sa_mstr_createdby',
            'user_mstr_id'
        );
    }

    // relasi balik ke stock opname
    public function stockOpname()
    {
        return $this->belongsTo(
            SoMstr::class,
            'sa_mstr_ref',
            'so_mstr_id'
        );
    }
}
