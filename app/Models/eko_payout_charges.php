<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class eko_payout_charges extends Model
{
    use HasFactory;
    protected $fillable = [
        'counter_code',
        'from_amount',
        'to_amount',
        'room_charge',
        'charge_type',
        'charge',
        'charge_status'
    ];
    protected $hidden = [];
    protected $casts = [];
}
