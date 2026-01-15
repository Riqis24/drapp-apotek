<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TsMstr extends Model
{
    /** @use HasFactory<\Database\Factories\TsMstrFactory> */
    use HasFactory;

    protected $table = 'ts_mstr';

    protected $primaryKey = 'ts_mstr_id';

    protected $fillable = [
        'ts_mstr_nbr',
        'ts_mstr_date',

        'ts_mstr_from',
        'ts_mstr_to',

        'ts_mstr_status',

        'ts_mstr_note',
        'ts_mstr_createdby',
    ];

    public function details()
    {
        return $this->hasMany(TsDet::class, 'ts_det_mstrid');
    }

    public function fromLocation()
    {
        return $this->belongsTo(LocMstr::class, 'ts_mstr_from', 'loc_mstr_id');
    }

    public function toLocation()
    {
        return $this->belongsTo(LocMstr::class, 'ts_mstr_to', 'loc_mstr_id');
    }
}
