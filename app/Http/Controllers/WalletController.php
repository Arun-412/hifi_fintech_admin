<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use View;

class WalletController extends Controller
{
    public function distributer_wallets() {
        try{
            $distributer_wallet = User::where(['door_mode'=>'HF11','kyc_status'=>'HFY','door_status'=>'HFY'])->orderBy('awards', 'ASC')->get();
            return view('wallet.wallet_topup')->with('data',$distributer_wallet);
        }
        catch(\Throwable $e){
            return back()->with("failed",$e->getmessage());
        }
    }

    public function wallet_actions(Request $request) {
        try{
            if($request->user_code){
                if($request->amount > 0){
                    if(User::where(['door_code'=>$request->user_code])->exists()){
                        $user = User::where(['door_code'=>$request->user_code])->first();
                        if($request->action_type == "credit"){
                            if(Auth::user()->awards >= $request->amount){
                                Auth::user()->awards = Auth::user()->awards - $request->amount;
                                Auth::user()->save();
                                $user->awards = $user->awards + $request->amount;
                                $user->save();
                                return response()->json(['status'=>true,'message'=>"Wallet amount ₹".$request->amount." CREDITED to ".$request->shop_name." successfully"]);
                            }
                            else{
                                return response()->json(['status'=>false,'message'=>"Insufficient wallet Balance"]);
                            }
                        }
                        else if($request->action_type == "debit"){
                            if($user->awards >= $request->amount){
                                Auth::user()->awards = Auth::user()->awards + $request->amount;
                                Auth::user()->save();
                                $user->awards = $user->awards - $request->amount;
                                $user->save();
                                return response()->json(['status'=>true,'message'=>"Wallet amount ₹".$request->amount." DEBITED from ".$request->shop_name." successfully"]);
                            }
                            else{
                                return response()->json(['status'=>false,'message'=>"Insufficient Distributer Balance"]);
                            }
                        }
                        else if($request->action_type == "hold"){
                            $user->awards_hold = $user->awards_hold + $request->amount;
                            $user->awards = $user->awards - $request->amount;
                            $user->save();
                            return response()->json(['status'=>true,'message'=>"Wallet amount ₹".$request->amount." HOLDED for ".$request->shop_name." successfully"]);
                        }
                        else if($request->action_type == "unhold"){
                            if($request->amount <= $user->awards_hold){
                                $user->awards_hold = $user->awards_hold - $request->amount;
                                $user->awards = $user->awards + $request->amount;
                                $user->save();
                                return response()->json(['status'=>true,'message'=>"Wallet amount ₹".$request->amount." UN-HOLDED for ".$request->shop_name." successfully"]);
                            }
                            else{
                                return response()->json(['status'=>false,'message'=>"You can Un-Hold wallet amount upto ₹".$user->awards_hold]);
                            }
                        }
                        else{
                            return response()->json(['status'=>false,'message'=>"Action required"]);
                        }
                    }
                    else{
                        return response()->json(['status'=>false,'message'=>"User Not Allowed"]);
                    }
                }
                else{
                    return response()->json(['status'=>false,'message'=>"Amount not found"]);
                }
            }
            else{
                return response()->json(['status'=>false,'message'=>"User required"]);
            }
        }
        catch(\Throwable $e){
            return back()->with("failed",$e->getmessage());
        }
    }

    public function wallet_approval (Request $request){
        try{
            return view('wallet.wallet_approvals');
        }
        catch(\Throwable $e){
            return back()->with("failed",$e->getmessage());
        }
    }

    public function wallet_auto_topup (Request $request) {
        try{
            return view('wallet.wallet_OD');
        }
        catch(\Throwable $e){
            return back()->with("failed",$e->getmessage());
        }
    }
}
