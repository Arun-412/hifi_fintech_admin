<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sand_log extends Model
{
    use HasFactory;
    protected $table = 'sands_log';
    protected $fillable = [
        'sandt_Hid',
        'sandt_id',
        'sand_status',
        'sandt_mode',
        'sand_name',
        'sand_account',
        'sand_amount',
        'sand_fees',
        'sandt_user',
        'created_by',
        'sand_response',
        'created_at',
        'updated_at'
    ];
    protected $hidden = [
        'updated_at'
    ];
    protected $casts = [];
}
