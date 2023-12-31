<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class identity extends Model
{
    use HasFactory;
    protected $fillable = [
        'kyc_code',
        'door_code',
        'name',
        'date_of_birth',
        'pan_number',
        'pan_response',
        'aadhar_number',
        'aadhar_response',
        'address',
        'documents'
    ];
    protected $hidden = [];
    protected $casts = [];
}
