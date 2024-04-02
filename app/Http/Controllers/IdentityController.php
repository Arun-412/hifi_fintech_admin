<?php

namespace App\Http\Controllers;

use App\Models\identity;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use App\Models\stoneseeds;
use App\Models\sandstone;

class IdentityController extends Controller
{
    private $Access_Key;
    private $Base_URL;
    private $Onboarding_URL;
    private $Initiator_ID;
    private $Developer_Key;
    private $Authenticator_Key;
    private $admin_code;

    public function __construct() {
        if(env("API_ACCESS_MODE") == "LIVE"){
            $this->Access_Key = env("API_PRODUCTION_ACCESS_KEY");
        }else if(env("API_ACCESS_MODE") == "TEST"){
            $this->Access_Key = env("API_STAGING_ACCESS_KEY");
        }else{
            $this->Access_Key = env("API_LOCAL_ACCESS_KEY");         
        }
        if(env("EKO_MODE") == "LIVE"){
            $this->Onboarding_URL = env("EKO_ONBOARDING_PRODUCTION");
            $this->Base_URL = env("EKO_BASE_URL_PRODUCTION");
            $this->Initiator_ID = env("EKO_INITIATOR_ID_PRODUCTION");
            $this->Developer_Key = env("EKO_DEVELOPER_KEY_PRODUCTION");
            $this->Authenticator_Key = env("EKO_AUTHENTICATOR_KEY_PRODUCTION");
            $this->admin_code = env("EKO_ADMIN_CODE_PRODUCTION");
        }else{
            $this->Onboarding_URL = env("EKO_ONBOARDING_STAGING");
            $this->Base_URL = env("EKO_BASE_URL_STAGING");
            $this->Initiator_ID = env("EKO_INITIATOR_ID_STAGING");
            $this->Developer_Key = env("EKO_DEVELOPER_KEY_STAGING");
            $this->Authenticator_Key = env("EKO_AUTHENTICATOR_KEY_STAGING");
            $this->admin_code = env("EKO_ADMIN_CODE_STAGING");
        }
    }

    public function curl_post($data){
        try{
            // return $data;
            $curl = curl_init();
            $encodedKey = base64_encode($this->Authenticator_Key);
            $secret_key_timestamp = (int)(round(microtime(true) * 1000));
            $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
            $secret_key = base64_encode($signature);
            curl_setopt_array($curl, array(
                CURLOPT_SSL_VERIFYHOST => env("SSL_VERIFY"),
                CURLOPT_SSL_VERIFYPEER => env("SSL_VERIFY"),
                CURLOPT_URL => $data['url'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data['data'],
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded',
                    'developer_key: '.$this->Developer_Key,
                    'secret-key:'.$secret_key,
                    'secret-key-timestamp:'.$secret_key_timestamp
                    // "developer_key: becbbce45f79c6f5109f848acd540567",
                    // "secret-key: MC6dKW278tBef+AuqL/5rW2K3WgOegF0ZHLW/FriZQw=",
                    // "secret-key-timestamp: 1516705204593"
                ),
            ));
            $responses = curl_exec($curl);
            $err = curl_error($curl);
            $response = 'Something went wrong from sending values';
            if ($err) {
                $response = $err;
            }else{
                $response = $responses;
            }
            curl_close($curl);
            return json_decode($response,true);
        }catch(\Throwable $e){
            return $e->getmessage();
        }
    }

    public function curl_put ($data) {
        try{
            $curl = curl_init();
            $encodedKey = base64_encode($this->Authenticator_Key);
            $secret_key_timestamp = (int)(round(microtime(true) * 1000));
            $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
            $secret_key = base64_encode($signature);
            curl_setopt_array($curl, array(
                CURLOPT_URL =>  $data['url'],
                CURLOPT_SSL_VERIFYHOST => env("SSL_VERIFY"),
                CURLOPT_SSL_VERIFYPEER => env("SSL_VERIFY"),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => $data['data'],
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded',
                    'developer_key: '.$this->Developer_Key,
                    'secret-key:'.$secret_key,
                    'secret-key-timestamp:'.$secret_key_timestamp
                    // "developer_key: becbbce45f79c6f5109f848acd540567",
                    // "secret-key: MC6dKW278tBef+AuqL/5rW2K3WgOegF0ZHLW/FriZQw=",
                    // "secret-key-timestamp: 1516705204593"
                ),
            ));
            $responses = curl_exec($curl);
            $err = curl_error($curl);
            $response = 'Something went wrong from sending values';
            if ($err) {
                $response = $err;
            }else{
                $response = $responses;
            }
            curl_close($curl);
            return json_decode($response,true);
        }catch(\Throwable $e){
            return $e->getmessage();
        }        
    }

    public function curl_get ($data) {
        try{
            $curl = curl_init();
            $encodedKey = base64_encode($this->Authenticator_Key);
            $secret_key_timestamp = (int)(round(microtime(true) * 1000));
            $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
            $secret_key = base64_encode($signature);
            curl_setopt_array($curl, array(
                CURLOPT_URL =>  $data['url'],
                CURLOPT_SSL_VERIFYHOST => env("SSL_VERIFY"),
                CURLOPT_SSL_VERIFYPEER => env("SSL_VERIFY"),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HEADER => false,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTPGET=> 1,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    "Cache-Control: no-cache",
                    'Content-Type: application/x-www-form-urlencoded',
                    'developer_key: '.$this->Developer_Key,
                    'secret-key:'.$secret_key,
                    'secret-key-timestamp:'.$secret_key_timestamp
                    // "developer_key: becbbce45f79c6f5109f848acd540567",
                    // "secret-key: MC6dKW278tBef+AuqL/5rW2K3WgOegF0ZHLW/FriZQw=",
                    // "secret-key-timestamp: 1516705204593"
                ),
            ));
            $responses = curl_exec($curl);
            $err = curl_error($curl);
            $response = 'Something went wrong from sending values';
            if ($err) {
                $response = $err;
            }else{
                $response = $responses;
            }
            curl_close($curl);
            return json_decode($response,true);
        }catch(\Throwable $e){
            return $e->getmessage();
        }        
    }

    public function Create_Customer(Request $request){
        try{
            if(empty($this->Base_URL)){
                Artisan::call('config:clear');
                return array("status"=>false,"message"=>"Try Again");
            }
            else{
                $address = array (
                    "line"=> "Main Road",
                    "city"=>"Coimbatore",
                    "state"=>"Tamil Nadu",
                    "pincode"=>"641668",
                    "district"=>"Coimbatore",
                    "area"=>"Somanur"
                );
                // return json_encode($address);
                $data = array(
                    "url"=>$this->Base_URL.'customers/mobile_number:6383224535',
                    "data"=>"initiator_id=".$this->Initiator_ID."&name=Arun&user_code=".$this->admin_code."&dob=1998-12-04&residence_address=".json_encode($address)."&skip_verification=true"
                );
                // return $data;
                $customer = $this->curl_put($data);
                return $customer;
            }
        }
        catch(\Throwable $e){
            return array("status"=>false,"message"=>$e->getmessage());
        }
    }

    public function Verify_Customer(Request $request){
        try{
            if(empty($this->Base_URL)){
                Artisan::call('config:clear');
                return array("status"=>false,"message"=>"Try Again");
            }
            else{
                $data = array(
                    "url"=>$this->Base_URL.'customers/verification/otp:123432',
                    "data"=>'initiator_id='.$this->Initiator_ID.'&id_type=mobile_number&id=6383224535&otp_ref_id=d3e00033-ebd1-5492-a631-53f0dbf00d69&user_code=20810200&pipe=9'
                );
                $otp_verify = $this->curl_put($data);
                return $otp_verify;
            }
        }
        catch(\Throwable $e){
            return array("status"=>false,"message"=>$e->getmessage());
        }
    }

    public function Get_Customer(Request $request){
        try{
            if(empty($this->Base_URL)){
                Artisan::call('config:clear');
                return array("status"=>false,"message"=>"Try Again");
            }
            else{
                $data = array(
                    "url"=>$this->Base_URL.'customers/mobile_number:6383224535?customer_id_type=mobile_number&customer_id=6383224535&initiator_id='.$this->Initiator_ID.'&user_code='.$this->admin_code,
                    
                    // "url"=>$this->Base_URL.'customers/mobile_number:6383224535&initiator_id=9962981729&user_code=20810200',
                    // "data"=>'initiator_id=9962981729&user_code=20810200'
                    // "data"=>'
                );
                // return $data;
                $get_customer = $this->curl_get($data);
                return $get_customer;
            }
        }
        catch(\Throwable $e){
            return array("status"=>false,"message"=>$e->getmessage());
        }
    }

    public function Get_Banks_List (Request $request) {
        try{
            if(empty($this->Base_URL)){
                Artisan::call('config:clear');
                return array("status"=>false,"message"=>"Try Again");
            }
            else{
                if($this->Access_Key == $request->token){
                    $data = array(
                        "url"=>$this->Base_URL.'banks?initiator_id='.$this->Initiator_ID.'&user_code='.$this->admin_code.'&bank_code='.$request->bank_code
                    );
                    $get_bank = $this->curl_get($data);
                    if($get_bank['message'] == "Bank Detials Found"){
                        $bank = array("status"=>true,"message"=>$get_bank);
                    }
                    else{
                        $bank = array("status"=>false,"message"=>$get_bank['message']);
                    }
                    return $bank;
                }
                else{
                    return array("status"=>false,"message"=>"You are noted! Do not try again");
                }
            }
        }
        catch(\Throwable $e){
            return array("status"=>false,"message"=>$e->getmessage());
        }
    }

    public function Bank_Account_Verification(Request $request){
        try{
            if(empty($this->Access_Key)){
                Artisan::call('config:clear');
                return array("status"=>"failed","message"=>"Try Again");
            }
            if($this->Access_Key == $request->token){
                $data = array(
                    "url"=>$this->Base_URL.'banks/bank_code:'.$request->bank_code.'/accounts/'.$request->account_number,
                    "data"=>'customer_id='.$request->customer_id.'&client_ref_id='."HFUS".mt_rand(1000000, 9999999)."VY".Str::random(4).'&user_code='.$this->admin_code.'&initiator_id='.$this->Initiator_ID
                );
                $Bank_Account_Verification = $this->curl_post($data);
                if($Bank_Account_Verification != "" && $Bank_Account_Verification['message'] != ''){
                    if(env("EKO_MODE") == "LIVE"){
                        if($Bank_Account_Verification['response_status_id'] == -1){
                            $bank_account = new stoneseeds;
                            $bank_account->account_code = "HFBA".Str::random(3)."VP0".Str::random(2);
                            $bank_account->bank_name = isset($Bank_Account_Verification['data']['bank']) ? $Bank_Account_Verification['data']['bank'] : $request->bank_name;
                            $bank_account->ifsc_code = $request->bank_code;
                            $bank_account->account_number = isset($Bank_Account_Verification['data']['account']) ? $Bank_Account_Verification['data']['account'] : $request->account_number;
                            $bank_account->account_holder_name = $Bank_Account_Verification['data']['recipient_name'];
                            $bank_account->verification_response = json_encode($Bank_Account_Verification);
                            $bank_account->verification_status = "HFY";
                            $bank_account->account_status = "HFY";
                            $bank_account->created_by = $request->user;
                            $bank_account->verified_by = $request->user;
                            $bank_account->save();
                            if($bank_account->save()){
                                $account_verify = array("status"=>"success","code"=>$bank_account->account_code,"name"=>$bank_account->account_holder_name);
                            }
                            else{
                                $account_verify = array("status"=>"failed","message"=>"Something went wrong in Account Verification");
                            }
                        }
                        else{
                            $account_verify = array("status"=>"failed","message"=>$Bank_Account_Verification['message']);
                        }
                    }
                    else{
                        if($Bank_Account_Verification['response_status_id'] != 1){
                            $bank_account = new stoneseeds;
                            $bank_account->account_code = "HFBA".Str::random(2)."0VP0".Str::random(2);
                            $bank_account->bank_name = $request->bank_name;
                            $bank_account->ifsc_code = $request->bank_code;
                            $bank_account->account_number = $request->account_number;
                            $bank_account->account_holder_name = $Bank_Account_Verification['data']['recipient_name'];
                            $bank_account->verification_response = json_encode($Bank_Account_Verification);
                            $bank_account->verification_status = "HFY";
                            $bank_account->account_status = "HFY";
                            $bank_account->created_by = $request->user;
                            $bank_account->verified_by = $request->user;
                            $bank_account->save();
                            if($bank_account->save()){
                                $account_verify = array("status"=>"success","code"=>$bank_account->account_code,"name"=>$bank_account->account_holder_name);
                            }
                            else{
                                $account_verify = array("status"=>"failed","message"=>"Something went wrong in Account Verification");
                            }
                        }
                        else{
                            $account_verify = array("status"=>"failed","message"=>$Bank_Account_Verification['message']);
                        }
                    }
                }
                else{
                    $account_verify = array("status"=>"failed","message"=>"Something went wrong from Account Verification");
                }
                return $account_verify;
            }else{
                return array("status"=>"failed","message"=>"You are noted! Do not try again");
            }
        }catch(\Throwable $e){
            return array("status"=>"failed","message"=>$e->getmessage());
        }
    }

    public function pan_address(Request $request){
        try{
            if(empty($this->Initiator_ID)){
                Artisan::call('config:clear');
                $Pan_Verify = array("status"=>false,"message"=>"Try again");
            }
            else if($this->Access_Key == $request->token){
                $data = array(
                    "url"=>$this->Onboarding_URL.'pan/verify',
                    "data"=>'pan_number='.$request->pan.'&purpose=1&initiator_id='.$this->Initiator_ID.'&purpose_desc=onboarding'
                );
                $Pan_Verify = $this->curl_post($data);
                if(env("EKO_MODE") == "LIVE"){
                    if($Pan_Verify != "" && $Pan_Verify['message'] != ''){
                        if($Pan_Verify['message'] == "PAN verification successful"){
                            $kyc = new identity;
                            $kyc->kyc_code = "HFI".Str::random(4)."S".Str::random(4);
                            $kyc->door_code = $request->door_code;
                            $name = [];
                            $name['first_name'] = $Pan_Verify->data->first_name;
                            $name['middle_name'] = $Pan_Verify->data->middle_name;
                            $name['last_name'] = $Pan_Verify->data->last_name; 
                            $kyc->name = json_encode($name);
                            $kyc->date_of_birth = $request->date_of_birth;
                            $kyc->pan_number = $Pan_Verify->data->pan_number;
                            $kyc->aadhar_number = $request->aadhar_number;
                            $kyc->pan_response = json_encode($Pan_Verify);
                            $address = [];
                            $address['line']= $request->street;
                            $address['city']= $request->city;
                            $address['state']= $request->state;
                            $address['pincode']= $request->pincode;
                            $kyc->address = json_encode($address);
                            $kyc->save();
                            if($kyc->save() != ""){
                                $kyc_user = identity::where(['door_code'=>$request->door_code])->first();
                                $user = User::where(['door_code'=>$request->door_code])->first();
                                $name = json_decode($kyc_user->name);
                                $data = array(
                                    "url"=>'user/onboard',
                                    "data"=>"initiator_id=".$this->Initiator_ID."&pan_number=".$request->pan."&mobile=".$user->mobile_number."&first_name=".$name->first_name."&last_name=".$name->last_name."&email=".$user->email."&residence_address=".$kyc_user->address."&dob=".$kyc_user->date_of_birth."&shop_name=".$user->shop_name,
                                );
                                $user_onboard = $this->curl_put($data);
                                if($user_onboard['message'] == "User onboarding successfull"){
                                    $provider = [];
                                    $provider['eko'] = $user_onboard->data->user_code;
                                    $user->provider_status = json_encode($provider);
                                    $provider_response = [];
                                    $provider_response['eko'] = $user_onboard;
                                    $user->provider_response = json_encode($provider_response);
                                    $user->kyc_status = "HFY";
                                    $user->save();
                                    if($user->save()){
                                        $Pan_Verify = array("status"=>true,"message"=>"KYC Completed successfully");
                                    }
                                    else{
                                        $Pan_Verify = array("status"=>false,"message"=>"Something went wrong in completing KYC");
                                    }
                                }
                                else{
                                    $Pan_Verify = array("status"=>false,"message"=>$user_onboard['message']);
                                }
                            }
                            else{
                                $Pan_Verify = array("status"=>false,"message"=>"Something went wrong in PAN Verification");
                            }
                        }
                        else{
                            $Pan_Verify = array("status"=>false,"message"=>$Pan_Verify['message']);
                        }
                    }
                    else{
                        $Pan_Verify = array("status"=>false,"message"=>"Something went wrong from PAN");
                    }
                }
                else{
                    if($Pan_Verify['message'] == "Customer not allowed"){
                        $kyc = new identity;
                        $kyc->kyc_code = "HFI".Str::random(4)."S".Str::random(4);
                        $kyc->door_code = $request->door_code;
                        $name = [];
                        $name['first_name'] = "HIFI";
                        $name['middle_name'] = "FINTECH";
                        $name['last_name'] = "USER"; 
                        $kyc->name = json_encode($name);
                        $kyc->date_of_birth = $request->date_of_birth;
                        $kyc->pan_number = $request->pan;
                        $kyc->aadhar_number = $request->aadhar_number;
                        $kyc->pan_response = json_encode($Pan_Verify);
                        $address = [];
                        $address['line']= $request->street;
                        $address['city']= $request->city;
                        $address['state']= $request->state;
                        $address['pincode']= $request->pincode;
                        $kyc->address = json_encode($address);
                        $kyc->save();
                        if($kyc->save() != ""){
                            $kyc_user = identity::where(['door_code'=>$request->door_code])->first();
                            $user = User::where(['door_code'=>$request->door_code])->first();
                            $name = json_decode($kyc_user->name);
                            $data = array(
                                "url"=>'user/onboard',
                                "data"=>"initiator_id=".$this->Initiator_ID."&pan_number=".$request->pan."&mobile=".$user->mobile_number."&first_name=".$name->first_name."&last_name=".$name->last_name."&email=".$user->email."&residence_address=".$kyc_user->address."&dob=".$kyc_user->date_of_birth."&shop_name=".$user->shop_name,
                            );
                            $user_onboard = $this->curl_put($data);
                            if($user_onboard['message'] == "PAN verification fail"){
                                $provider = [];
                                $provider['eko'] = 22123212;
                                $user->provider_status = json_encode($provider);
                                $provider_response = [];
                                $provider_response['eko'] = $user_onboard;
                                $user->provider_response = json_encode($provider_response);
                                $user->kyc_status = "HFY";
                                $user->save();
                                if($user->save()){
                                    $Pan_Verify = array("status"=>true,"message"=>"KYC Completed successfully");
                                }
                                else{
                                    $Pan_Verify = array("status"=>false,"message"=>"Something went wrong in completing KYC");
                                }
                            }
                            else{
                                $Pan_Verify = array("status"=>false,"message"=>$user_onboard['message']);
                            }
                        }
                        else{
                            $Pan_Verify = array("status"=>false,"message"=>"Something went wrong in PAN Verification");
                        }
                    }
                    else{
                        $Pan_Verify = array("status"=>false,"message"=>$Pan_Verify['message']);
                    }
                }
                return $Pan_Verify;
            }else{
                return array("status"=>false,"message"=>"You are noted! Do not try again");
            }
        }catch(\Throwable $e){
            return array("status"=>false,"message"=>$e->getmessage());
        }
    }
}
