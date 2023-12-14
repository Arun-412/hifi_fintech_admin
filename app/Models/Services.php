<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use HasFactory;
    protected $table = 'counters';
    protected $fillable = [
        'counter_status'
    ];
    protected $hidden = [];
    protected $casts = [];
}
