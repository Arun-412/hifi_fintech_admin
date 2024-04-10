<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sand extends Model
{
    use HasFactory;
    protected $table = 'sands';
    protected $fillable = [
        'sandt_id',
        'sand_status',
        'sand_name',
        'sand_account',
        'sand_fees',
        'created_by',
        'sand_response',
        'created_at',
        'updated_at'
    ];
    protected $hidden = [];
    protected $casts = [];
}
