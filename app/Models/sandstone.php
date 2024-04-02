<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sandstone extends Model
{
    use HasFactory;
    protected $table = 'sandstones';
    protected $fillable = [
        'account_code',
        'user_code'
    ];
    protected $hidden = [];
    protected $casts = [];
}
