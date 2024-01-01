<?php

namespace App\Http\Controllers;

use App\Models\identity;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;

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
            $curl = curl_init();
            $encodedKey = base64_encode($this->Authenticator_Key);
            $secret_key_timestamp = (int)(round(microtime(true) * 1000));
            $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
            $secret_key = base64_encode($signature);
            curl_setopt_array($curl, array(
                CURLOPT_URL =>  $this->Onboarding_URL.$data['url'],
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
            return json_decode($response);
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
                CURLOPT_URL =>  $this->Onboarding_URL.$data['url'],
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
            return json_decode($response);
        }catch(\Throwable $e){
            return $e->getmessage();
        }        
    }

    public function pan_address(Request $request){
        try{
            if($this->Access_Key == $request->token){
                $data = array(
                    "url"=>'pan/verify',
                    "data"=>'pan_number='.$request->pan.'&purpose=1&initiator_id='.$this->Initiator_ID.'&purpose_desc=onboarding'
                );
                $Pan_Verify = $this->curl_post($data);
                if($Pan_Verify != "" && $Pan_Verify->message != ''){
                    if(env("API_ACCESS_MODE") == "LIVE"){
                        if($Pan_Verify->message == "PAN verification successful"){
                            $Pan_Verify = array("status"=>true,"message"=>$Pan_Verify->message);
                        }
                        else{
                            $Pan_Verify = array("status"=>false,"message"=>$Pan_Verify->message);
                        }
                    }
                    else{
                        if($Pan_Verify->message == "Customer not allowed"){
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
                                $kyc_user = identity::where(['door_code'=>'HFRvS27Dz9dc'])->first();
                                $user = User::where(['door_code'=>'HFRvS27Dz9dc'])->first();
                                $name = json_decode($kyc_user->name);
                                $data = array(
                                    "url"=>'user/onboard',
                                    "data"=>"initiator_id=".$this->Initiator_ID."&pan_number=CCAPA9739C&mobile=".$user->mobile_number."&first_name=".$name->first_name."&last_name=".$name->last_name."&email=".$user->email."&residence_address=".$kyc_user->address."&dob=".$kyc_user->date_of_birth."&shop_name=".$user->shop_name,
                                );
                                $user_onboard = $this->curl_put($data);
                                if($user_onboard->message == "PAN verification fail"){
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
                                    $Pan_Verify = array("status"=>false,"message"=>$user_onboard->message);
                                }
                            }
                            else{
                                $Pan_Verify = array("status"=>false,"message"=>"Something went wrong in PAN Verification");
                            }
                        }
                        else{
                            $Pan_Verify = array("status"=>false,"message"=>$Pan_Verify->message);
                        }
                    }
                }
                else{
                    $Pan_Verify = array("status"=>false,"message"=>"Something went wrong from PAN");
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
