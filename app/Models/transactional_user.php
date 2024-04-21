<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transactional_user extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_code',
        'mobile_number',
        'verify_otp',
        'status',
        'created_by'
    ];
    protected $hidden = [];
    protected $casts = [];
}
