<?php

namespace App\Http\Controllers;

use App\Models\eko_payout_charges;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EkoPayoutChargesController extends Controller
{
    public function get_charges(){
        return view('services.payout.eko')->with('data', eko_payout_charges::get());
    }
}
