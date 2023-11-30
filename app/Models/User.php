<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'doors';
    protected $fillable = [
        'shop_name',
        'door_code',
        'mobile_number',
        'mobile_otp',
        'email',
        'email_otp',
        'password',
        'kyc_status',
        'door_mode',
        'door_opened_by',
        'door_status',
        'door_key'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'mobile_otp',
        'email_otp',
        'password',
        'remember_token',
        'door_key',
        'mobile_verified_at',
        'email_verified_at',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'mobile_verified_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
