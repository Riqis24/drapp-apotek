<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PresMstr extends Model
{
    use HasFactory;

    protected $table = 'pres_mstr';
    protected $primaryKey = 'pres_mstr_id';

    protected $fillable = [
        'pres_mstr_code',
        'pres_mstr_name',
        'pres_mstr_doctor',
        'pres_mstr_type',
        'pres_mstr_qty',
        'pres_mstr_status',
        'pres_mstr_mat',
        'pres_mstr_fee',
        'pres_mstr_mark',
        'pres_mstr_total',
        'pres_mstr_smid',
        'pres_mstr_createdby',
    ];

    /* =====================
     * RELATIONS
     * ===================== */

    // master -> detail bahan
    public function details()
    {
        return $this->hasMany(
            PresDet::class,
            'pres_det_mstrid',
            'pres_mstr_id'
        );
    }

    // resep -> sales master
    public function sales()
    {
        return $this->belongsTo(
            SalesMstr::class,
            'sales_mstr_id',
            'sales_mstr_id'
        );
    }

    // user pembuat
    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by',
            'id'
        );
    }

    /* =====================
     * SCOPES
     * ===================== */

    public function scopeReady($query)
    {
        return $query->where('pres_mstr_status', 'ready');
    }

    public function scopePaid($query)
    {
        return $query->where('pres_mstr_status', 'paid');
    }
}
