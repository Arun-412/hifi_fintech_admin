<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chair extends Model
{
    use HasFactory;
    protected $fillable = [
        'chair_from',
        'chair_to',
        'chair_user_charge_type',
        'chair_user_charge',
        'chair_status'
    ];
    protected $hidden = [];
    protected $casts = [];
}