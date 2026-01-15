<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'user_mstr';
    protected $primaryKey = 'user_mstr_id';
    protected $keyType = 'string';
    const CREATED_AT = 'user_mstr_ct';
    const UPDATED_AT = 'user_mstr_ut';



    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->useLogName('user_mstr')
    //         ->setDescriptionForEvent(fn(string $eventName) => "You have {$eventName} user")
    //         ->logOnly([
    //             'user_mstr_name',
    //             'user_mstr_email',
    //             'user_mstr_nik',
    //             'user_mstr_active',
    //             'user_mstr_cb',
    //             'user_mstr_image'
    //         ])
    //         ->dontSubmitEmptyLogs();
    //     // Chain fluent methods for configuration options
    // }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_mstr_name',
        'user_mstr_email',
        'email_verified_at',
        'user_mstr_password',
        'user_mstr_active',
        'user_mstr_cb',
        'user_mstr_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'user_mstr_password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'user_mstr_password' => 'hashed',
        ];
    }

    public function getAuthPassword()
    {
        return $this->user_mstr_password;
    }
}
