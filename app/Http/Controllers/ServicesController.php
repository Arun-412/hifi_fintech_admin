<?php

namespace App\Http\Controllers;

use App\Models\Services;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Providers;

class ServicesController extends Controller
{
    public function Services() {
        try{
            $providers = Providers::get();
            $services = Services::get();
            $data['providers'] = $providers;
            $data['services'] = $services;
            return view('services')->with("data",$data);   
        }
        catch(\Throwable $e){
            return back()->with("failed",$e->getmessage());
        }
    }
}
