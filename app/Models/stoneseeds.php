<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stoneseeds extends Model
{
    use HasFactory;
    protected $fillable = [
        'account_code',
        'bank_name',
        'ifsc_code',
        'account_number',
        'account_holder_name',
        'verification_response',
        'verification_status',
        'account_status',
        'created_by',
        'verified_by',
        'actions'
    ];
    protected $hidden = [];
    protected $casts = [];
}
