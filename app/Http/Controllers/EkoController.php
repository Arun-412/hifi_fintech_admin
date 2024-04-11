<?php

namespace App\Http\Controllers;

use App\Models\eko;
use App\Models\sand;
use App\Models\sand_log;
use App\Models\stoneseeds;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class EkoController extends Controller
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

    public function eko_activate_service ($data) {
        try{
            if(env("EKO_MODE") == "LIVE"){
                $encodedKey = base64_encode($this->Authenticator_Key);
                $secret_key_timestamp = (int)(round(microtime(true) * 1000));
                $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
                $secret_key = base64_encode($signature);
            }
            else{
                $secret_key_timestamp = "1516705204593";
                $secret_key = "MC6dKW278tBef+AuqL/5rW2K3WgOegF0ZHLW/FriZQw=";
            }
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL =>  $data['url'],
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
            $response = 'Something went wrong from Activate service from admin';
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
    
    public function activate_service(Request $request){
        try{
            if($this->Access_Key == $request->token){
                if(env("EKO_MODE") == "LIVE"){
                    $data = array(
                        "url"=>$this->Onboarding_URL.'user/service/activate',
                        "data"=>'service_code='.$request->service_code.'&initiator_id='.$this->Initiator_ID.'&user_code='.$request->user_code
                    );
                    $activated = $this->eko_activate_service($data);
                    if($activated->data->service_status_desc == "Service Activated for the user"){
                        return array("status"=>true,"message"=>"Service activated successfully");
                    }
                    else{
                        return array("status"=>false,"message"=>"Something went wrong in activate service");
                    }
                }
                else{
                    $data = array(
                        "url"=>$this->Onboarding_URL.'user/service/activate',
                        "data"=>'service_code=4&initiator_id='.$this->Initiator_ID.'&user_code='.$request->user_code
                    );
                    $activated = $this->eko_activate_service($data);
                    if($activated->message == "This user does not exist"){
                        return array("status"=>true,"message"=>"Service activated successfully");
                    }
                    else{
                        return array("status"=>false,"message"=>"Something went wrong in activate service");
                    }
                }
            }else{
                return array("status"=>false,"message"=>"You are noted! Do not try again");
            }
        }catch(\Throwable $e){
            return array("status"=>false,"message"=>$e->getmessage());
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

    public function payout_transaction(Request $request) {
        try{
            if(empty($this->Access_Key)){
                Artisan::call('config:clear');
                return array("status"=>"failed","message"=>"Try Again");
            }
            if($this->Access_Key == $request->token){
                if($request->account_id){
                    if(stoneseeds::where(['account_code'=>$request->account_id])->exists()){
                        $account = stoneseeds::where(['account_code'=>$request->account_id])->first();
                        $data = array(
                            "url"=>$this->Onboarding_URL."agent/user_code:".$this->admin_code."/settlement",
                            "data"=>"initiator_id=".$this->Initiator_ID."&amount=".$request->transaction_amount."&payment_mode=".$request->tranaction_mode."&client_ref_id=HFPYOT".date('YmdHis')."&recipient_name=".$account->account_holder_name."&ifsc=".$account->ifsc_code."&account=".$account->account_number."&service_code=45&sender_name=".$request->send_by."&tag=HIFI_FINTECH&beneficiary_account_type=1"
                        );
                        $transaction = $this->curl_post($data);
                        if($transaction != "" && isset($transaction['message']) != ''){
                            if(isset($transaction['invalid_params']) !=''){
                                $transction_res = array("status"=>"failed","message"=>$transaction['message']);
                            }
                            else if(isset($transaction['data']['tx_status']) == "" && $transaction['message']) {
                                $transction_res = array("status"=>"failed","message"=>$transaction['message']);
                            }
                            else if(env("EKO_MODE") == "LIVE"){
                                if($transaction['data']['tx_status'] == 1){ // failed
                                    $records = new sand_log;
                                    $records->sandt_id = $transaction['data']['bank_ref_num'];
                                    $records->sand_status = $transaction['data']['tx_status'];
                                    $records->sand_name = $transaction['data']['recipient_name'];
                                    $records->sand_account = $transaction['data']['account'];
                                    $records->sand_amount = $transaction['data']['amount'];
                                    $records->sand_fees = $transaction['data']['totalfee'];
                                    $records->created_by = $request->user;
                                    $records->sand_response = json_encode($transaction);
                                    $records->save();
                                    if($records->save()){
                                        $transction_res = array("status"=>"success","message"=>$transaction['message']);
                                    }
                                    else{
                                        $transction_res = array("status"=>"failed","message"=>"Something went wrong from transaction records");
                                    }
                                }
                                else if($transaction['data']['tx_status'] != 1){
                                    $record = new sand;
                                    $record->sandt_id = $transaction['data']['bank_ref_num'];
                                    $record->sand_name = $transaction['data']['recipient_name'];
                                    $record->sand_status = $transaction['data']['tx_status'];
                                    $record->sand_account = $transaction['data']['account'];
                                    $record->sand_amount = $transaction['data']['amount'];
                                    $record->sand_fees = $transaction['data']['totalfee'];
                                    $record->created_by = $request->user;
                                    $record->sand_response = json_encode($transaction);
                                    $record->save();
                                    if($record->save()){
                                        $transction_res = array("status"=>"success","message"=>$transaction['message']);
                                    }
                                    else{
                                        $transction_res = array("status"=>"failed","message"=>"Something went wrong from transaction records");
                                    }
                                }
                                else{
                                    $transction_res = array("status"=>"success","message"=>$transaction['message']);
                                }
                            }
                            else{
                                $transction_res = array("status"=>"success","message"=>"test transaction"); 
                            }
                        }
                        else{
                            $transction_res = array("status"=>"failed","message"=>"Something went wrong from transaction");
                        }
                    }
                    else{
                        $transction_res = array("status"=>"failed","message"=>"Something went wrong from account details transaction");
                    }
                }
                else{
                    $transction_res = array("status"=>"failed","message"=>"Something went wrong from account details");
                }
                return $transction_res;
            }else{
                return array("status"=>false,"message"=>"You are noted! Do not try again");
            }
        }catch(\Throwable $e){
            return array("status"=>false,"message"=>$e->getmessage());
        }
    }
  
    public function Activate_Services() { //Completed
        $curl = curl_init();
        $encodedKey = base64_encode($this->Authenticator_Key);
        $secret_key_timestamp = (int)(round(microtime(true) * 1000));
        $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
        $secret_key = base64_encode($signature);
        curl_setopt_array($curl, array(
            CURLOPT_URL =>  $this->Onboarding_URL.'user/service/activate',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => 'service_code=4&initiator_id='.$this->Initiator_ID.'&user_code='.$this->admin_code,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'developer_key: '.$this->Developer_Key,
                'secret-key:'.$secret_key,
                'secret-key-timestamp:'.$secret_key_timestamp
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $Pan_Verify = 'Something went wrong';
        if ($err) {
            $Pan_Verify = "Error - ".$err;
        }else{
            $Pan_Verify = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('Pan_Verify',$Pan_Verify);
    }

    public function Pan_Verify() { //Completed
        $curl = curl_init();
        $encodedKey = base64_encode($this->Authenticator_Key);
        $secret_key_timestamp = (int)(round(microtime(true) * 1000));
        $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
        $secret_key = base64_encode($signature);
        curl_setopt_array($curl, array(
            CURLOPT_URL =>  $this->Onboarding_URL.'pan/verify',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'pan_number=CCAPA9739C&purpose=1&initiator_id='.$this->Initiator_ID.'&purpose_desc=onboarding',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'developer_key: '.$this->Developer_Key,
                'secret-key:'.$secret_key,
                'secret-key-timestamp:'.$secret_key_timestamp
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $Pan_Verify = 'Something went wrong';
        if ($err) {
            $Pan_Verify = "Error - ".$err;
        }else{
            $Pan_Verify = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('Pan_Verify',$Pan_Verify);
    }

    public function Get_Services() { //Completed
        $curl = curl_init();
        $encodedKey = base64_encode($this->Authenticator_Key);
        $secret_key_timestamp = (int)(round(microtime(true) * 1000));
        $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
        $secret_key = base64_encode($signature);
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYHOST => env("SSL_VERIFY"),
            CURLOPT_SSL_VERIFYPEER =>  env("SSL_VERIFY"),
            CURLOPT_URL => $this->Onboarding_URL."user/services?initiator_id=".$this->Initiator_ID,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
              "Cache-Control: no-cache",
              'developer_key: '.$this->Developer_Key,
              'secret-key:'.$secret_key,
              'secret-key-timestamp:'.$secret_key_timestamp
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $Get_Services = 'Something went wrong';
        if ($err) {
            $Get_Services = "Error - ".$err;
        }else{
            $Get_Services = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('Get_Services',$Get_Services);
    }

    public function Onboard_User() { //Completed
        $d  = array (
            "line"=> "Somanur Main Road","city"=>"Coimbatore","state"=>"Tamil Nadu","pincode"=>"641668"
        );
        $curl = curl_init();   
        $encodedKey = base64_encode($this->Authenticator_Key);
        $secret_key_timestamp = (int)(round(microtime(true) * 1000));
        $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
        $secret_key = base64_encode($signature); 
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYHOST => env("SSL_VERIFY"),
            CURLOPT_SSL_VERIFYPEER =>  env("SSL_VERIFY"),
            CURLOPT_URL => $this->Onboarding_URL."user/onboard",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => "initiator_id=".$this->Initiator_ID."&pan_number=CCAPA9739C&mobile=6383224535&first_name=ARUNMOZHI&last_name=NATARAJAN&email=arunmozhi52892@gmail.com&residence_address=".json_encode($d)."&dob=1998-12-04&shop_name=NAMV SOFTECH SOLUTIONS PRIVATE LIMITED",
            CURLOPT_HTTPHEADER => array(
              "Cache-Control: no-cache",
              "Content-Type: application/x-www-form-urlencoded",
              'developer_key: '.$this->Developer_Key,
              'secret-key:'.$secret_key,
              'secret-key-timestamp:'.$secret_key_timestamp
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $Onboard_User = 'Something went wrong';
        if ($err) {
            $Onboard_User = "Error - ".$err;
        }else{
            $Onboard_User = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('Onboard_User',$Onboard_User);
    }

    public function Bank_Account_Verification() {
        // return "Hi";
        $curl = curl_init();
        // $key = env("EKO_STAGING_KEY");
        // $encodedKey = base64_encode($key);
        // $secret_key_timestamp = (int)(round(microtime(true) * 1000));
        // $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
        // $secret_key = base64_encode($signature);
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYHOST => env("SSL_VERIFY"),
            CURLOPT_SSL_VERIFYPEER => env("SSL_VERIFY"),
            CURLOPT_URL => 'https://staging.eko.in:25004/ekoapi/v2/banks/ifsc:KKBK0000261/accounts/1711654121',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'initiator_id=9962981729&customer_id=7661109875&client_ref_id=AVS20181123194719311&user_code=20810200',
            CURLOPT_HTTPHEADER => array(
                'developer_key: becbbce45f79c6f5109f848acd540567',
                'secret-key:MC6dKW278tBef+AuqL/5rW2K3WgOegF0ZHLW/FriZQw=',
                'secret-key-timestamp:1516705204593',
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $Bank_Account_Verification = 'Something went wrong';
        if ($err) {
            $Bank_Account_Verification = "Error - ".$err;
        }else{
            $Bank_Account_Verification = "Success - ".$response;
        }
        curl_close($curl);
        return $Bank_Account_Verification;
        // return view('eko')->with('Bank_Account_Verification',$Bank_Account_Verification);
    }

    public function Request_OTP() { //otp not received
        $curl = curl_init();
        $encodedKey = base64_encode($this->Authenticator_Key);
        $secret_key_timestamp = (int)(round(microtime(true) * 1000));
        $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
        $secret_key = base64_encode($signature); 
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYHOST => env("SSL_VERIFY"),
            CURLOPT_SSL_VERIFYPEER => env("SSL_VERIFY"),
            CURLOPT_URL =>  $this->Onboarding_URL."user/request/otp",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => "initiator_id=".$this->Initiator_ID."&mobile=6383224535",
            CURLOPT_HTTPHEADER => array(
              "Cache-Control: no-cache",
              "content-type: application/x-www-form-urlencoded",
              'developer_key: '.$this->Developer_Key,
              'secret-key:'.$secret_key,
              'secret-key-timestamp:'.$secret_key_timestamp
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $Request_OTP = 'Something went wrong';
        if ($err) {
            $Request_OTP = "Error - ".$err;
        }else{
            $Request_OTP = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('Request_OTP',$Request_OTP);
    }

    public function Verify_User_Mobile_Number() { //otp not received
        $curl = curl_init();
        $encodedKey = base64_encode($this->Authenticator_Key);
        $secret_key_timestamp = (int)(round(microtime(true) * 1000));
        $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
        $secret_key = base64_encode($signature); 
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYHOST => env("SSL_VERIFY"),
            CURLOPT_SSL_VERIFYPEER => env("SSL_VERIFY"),
            CURLOPT_URL => $this->Onboarding_URL."user/verify",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => "initiator_id=".$this->Initiator_ID."&mobile=6383224535&otp=447",
            CURLOPT_HTTPHEADER => array(
              "Cache-Control: no-cache",
              "content-type: application/x-www-form-urlencoded",
              'developer_key: '.$this->Developer_Key,
              'secret-key:'.$secret_key,
              'secret-key-timestamp:'.$secret_key_timestamp
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $Verify_User_Mobile_Number = 'Something went wrong';
        if ($err) {
            $Verify_User_Mobile_Number = "Error - ".$err;
        }else{
            $Verify_User_Mobile_Number = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('Verify_User_Mobile_Number',$Verify_User_Mobile_Number);
    }

    public function User_Services_Enquiry() {
        $curl = curl_init();
        $encodedKey = base64_encode($this->Authenticator_Key);
        $secret_key_timestamp = (int)(round(microtime(true) * 1000));
        $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
        $secret_key = base64_encode($signature); 
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYHOST => env("SSL_VERIFY"),
            CURLOPT_SSL_VERIFYPEER => env("SSL_VERIFY"),
            CURLOPT_URL => $this->Onboarding_URL."user/services/user_code:34738002?initiator_id=".$this->Initiator_ID,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
              "Cache-Control: no-cache",
              "content-type: application/x-www-form-urlencoded",
              'developer_key: '.$this->Developer_Key,
              'secret-key:'.$secret_key,
              'secret-key-timestamp:'.$secret_key_timestamp
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $User_Services_Enquiry = 'Something went wrong';
        if ($err) {
            $User_Services_Enquiry = "Error - ".$err;
        }else{
            $User_Services_Enquiry = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('User_Services_Enquiry',$User_Services_Enquiry);
    }
    
    // public function Activate_Payout() { // mismatch request

    //     $curl = curl_init();
    //     $encodedKey = base64_encode($this->Authenticator_Key);
    //     $secret_key_timestamp = (int)(round(microtime(true) * 1000));
    //     $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
    //     $secret_key = base64_encode($signature);

    //     $target_url = $this->Onboarding_URL."user/service/activate";   

    //     $fname1 = "C:\Users\Admin\Desktop\NAMV\Pan.jpeg";
    //     $fname2 = "C:\Users\Admin\Desktop\NAMV\adhar_front";
    //     $fname3 = "C:\Users\Admin\Desktop\NAMV\Aadhar_Back";
        
        
    //     $cfile1 = new \CURLFile(realpath($fname1));
    //     $cfile2 = new \CURLFile(realpath($fname2));
    //     $cfile3 = new \CURLFile(realpath($fname3));

    //     $post = array (
    //         'pan_card' => $cfile1,
    //         'aadhar_front' => $cfile2,
    //         'aadhar_back' => $cfile3,
    //         'form-data' => 'service_code=45&initiator_id='.$this->Initiator_ID.'&user_code=34738002&devicenumber=123234234234234&modelname=Morpho&office_address={"line": "Eko India","city":"Gurgaon","state":"Haryana","pincode":"122002"}&address_as_per_proof={"line": "Eko India","city":"Gurgaon","state":"Haryana","pincode":"122002"}',
    //     ); 
    //     $curl = curl_init();
    //     curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, env("SSL_VERIFY"));
    //     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,  env("SSL_VERIFY"));
    //     curl_setopt($curl, CURLOPT_URL, $target_url);
    //     curl_setopt($curl, CURLOPT_POST, 1);
    //     curl_setopt($curl, CURLOPT_HEADER, 0);
    //     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
    //     // curl_setopt($curl, CURLOPT_HTTPHEADER,array('Content-Type: multipart/form-data','developer_key: becbbce45f79c6f5109f848acd540567', 'secret-key:MC6dKW278tBef+AuqL/5rW2K3WgOegF0ZHLW/FriZQw=','secret-key-timestamp:1516705204593'));
    //     curl_setopt($curl, CURLOPT_HTTPHEADER,array('Content-Type: multipart/form-data','developer_key: becbbce45f79c6f5109f848acd540567', 'secret-key:'.$secret_key,'secret-key-timestamp:'.$secret_key_timestamp));
    //     curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);   
    //     curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);  
    //     curl_setopt($curl, CURLOPT_TIMEOUT, 100);
    //     curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
    //     curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
    //     $response = curl_exec($curl);
    //     $err = curl_error($curl);
    //     $Activate_Payout = 'Something went wrong';
    //     if ($err) {
    //         $Activate_Payout = "Error - ".$err;
    //     }else{
    //         $Activate_Payout = "Success - ".$response;
    //     }
    //     curl_close($curl);
    //     return view('eko')->with('Activate_Payout',$Activate_Payout);
    // }

    public function Activate_Payout() { //Completed
        $curl = curl_init();
        $encodedKey = base64_encode($this->Authenticator_Key);
        $secret_key_timestamp = (int)(round(microtime(true) * 1000));
        $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
        $secret_key = base64_encode($signature);
        curl_setopt_array($curl, array(
            CURLOPT_URL =>  $this->Onboarding_URL.'user/service/activate',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => 'service_code=45&initiator_id='.$this->Initiator_ID.'&user_code=34738002',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'developer_key: '.$this->Developer_Key,
                'secret-key:'.$secret_key,
                'secret-key-timestamp:'.$secret_key_timestamp
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $Activate_Payout = 'Something went wrong';
        if ($err) {
            $Activate_Payout = "Error - ".$err;
        }else{
            $Activate_Payout = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('Activate_Payout',$Activate_Payout);
    }

    public function Payout_Fund_Transfer() {
        $curl = curl_init(); 
        $encodedKey = base64_encode($this->Authenticator_Key);
        $secret_key_timestamp = (int)(round(microtime(true) * 1000));
        $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
        $secret_key = base64_encode($signature);
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYHOST => env("SSL_VERIFY"),
            CURLOPT_SSL_VERIFYPEER => env("SSL_VERIFY"),
            CURLOPT_URL => $this->Onboarding_URL."agent/user_code:34738002/settlement",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "initiator_id=".$this->Initiator_ID."&amount=100&payment_mode=5&client_ref_id=HFPYOT141223051701&recipient_name=Arunmozhi&ifsc=CNRB0003437&account=3437108001565&service_code=45&sender_name=NAMVSOFTECH&source=NEWCONNECT&tag=HIFI_FINTECH&beneficiary_account_type=1",
            CURLOPT_HTTPHEADER => array(
                'developer_key: '.$this->Developer_Key,
                'secret-key:'.$secret_key,
                'secret-key-timestamp:'.$secret_key_timestamp
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $Payout_Fund_Transfer = 'Something went wrong';
        if ($err) {
            $Payout_Fund_Transfer = "Error - ".$err;
        }else{
            $Payout_Fund_Transfer = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('Payout_Fund_Transfer',$Payout_Fund_Transfer);
    }

    public function Payout_Transaction_Status_By_ID() {
        $curl = curl_init(); 
        $encodedKey = base64_encode($this->Authenticator_Key);
        $secret_key_timestamp = (int)(round(microtime(true) * 1000));
        $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
        $secret_key = base64_encode($signature);
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYHOST => env("SSL_VERIFY"),
            CURLOPT_SSL_VERIFYPEER => env("SSL_VERIFY"),
            CURLOPT_URL => $this->Onboarding_URL."transactions/3164559959?initiator_id=".$this->Initiator_ID,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                'developer_key: '.$this->Developer_Key,
                'secret-key:'.$secret_key,
                'secret-key-timestamp:'.$secret_key_timestamp
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $Payout_Transaction_Status_By_ID = 'Something went wrong';
        if ($err) {
            $Payout_Transaction_Status_By_ID = "Error - ".$err;
        }else{
            $Payout_Transaction_Status_By_ID = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('Payout_Transaction_Status_By_ID',$Payout_Transaction_Status_By_ID);
    }

    public function Payout_Transaction_Status () {
        $Payout_Transaction_Status = array(
                "tx_status" => 0,
                "amount" => 120.0,
                "payment_mode" => "5",
                "txstatus_desc" => "SUCCESS",
                "fee" => 5.0,
                "gst" => 0.76,
                "sender_name" => "Flipkarti",
                "tid" => 12971412,
                "beneficiary_account_type" => null,
                "client_ref_id" => "Settlemet7206124423",
                "old_tx_status" => 2,
                "old_tx_status_desc" => "Initiated",
                "bank_ref_num" => "87694239",
                "ifsc" => "SBIN0000001",
                "recipient_name" => "Virender Singh",
                "account" => "234243534",
                "timestamp" => "2019-11-01 18:03:48" 
            );
        return view('eko')->with('Payout_Transaction_Status',$Payout_Transaction_Status);
    }

    public function Aadhar_Consent() {
        $curl = curl_init();
        $key = env("EKO_STAGING_KEY");
        $encodedKey = base64_encode($key);
        $secret_key_timestamp = (int)(round(microtime(true) * 1000));
        $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
        $secret_key = base64_encode($signature);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, env("SSL_VERIFY"));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,  env("SSL_VERIFY"));
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://staging.eko.in/ekoapi/external/getAdhaarConsent",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => 'source=NEWCONNECT&initiator_id=9971771929&is_consent=Y&consent_text=123443211234&name=arun&user_code=20810200&realsourceip=103.89.67.139',
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                'developer_key: becbbce45f79c6f5109f848acd540567',
                'secret-key:'.$secret_key,
                'secret-key-timestamp:'.$secret_key_timestamp,
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $Aadhar_Consent = "Something went wrong";
        if ($err) {
            $Aadhar_Consent = "Error - ".$err;
        }else{
            $Aadhar_Consent = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('Aadhar_Consent',$Aadhar_Consent);
    }

    public function Aadhaar_OTP() {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, env("SSL_VERIFY"));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,  env("SSL_VERIFY"));
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://staging.eko.in/ekoapi/v1/external/getAdhaarOTP",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => 'source=NEWCONNECT&initiator_id=9971771929&is_consent=Y&aadhar=123443211234&caseId=123443211234&access_key=02e46071-c15e-46ab-9787-9a3ce79ac122&user_code=20810200&realsourceip=103.89.67.139',
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                'developer_key: becbbce45f79c6f5109f848acd540567',
                'secret-key:'.$secret_key,
                'secret-key-timestamp:'.$secret_key_timestamp,
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $Aadhaar_OTP = "Something went wrong";
        if ($err) {
            $Aadhaar_OTP = "Error - ".$err;
        }else{
            $Aadhaar_OTP = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('Aadhaar_OTP',$Aadhaar_OTP);
    }

    public function Aadhaar_File() {
        $curl = curl_init();
        $key = env("EKO_STAGING_KEY");
        $encodedKey = base64_encode($key);
        $secret_key_timestamp = (int)(round(microtime(true) * 1000));
        $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
        $secret_key = base64_encode($signature);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, env("SSL_VERIFY"));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,  env("SSL_VERIFY"));
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://staging.eko.in/ekoapi/v1/external/getAdhaarFile",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => 'otp=12321&initiator_id=9971771929&is_consent=Y&aadhar=123443211234&share_code=1221&access_key=02e46071-c15e-46ab-9787-9a3ce79ac122&user_code=20810200&realsourceip=103.89.67.139',
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                'developer_key: becbbce45f79c6f5109f848acd540567',
                'secret-key:'.$secret_key,
                'secret-key-timestamp:'.$secret_key_timestamp,
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $Aadhaar_File = "Something went wrong";
        if ($err) {
            $Aadhaar_File = "Error - ".$err;
        }else{
            $Aadhaar_File = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('Aadhaar_File',$Aadhaar_File);
    }

    public function Activate_QR() {

        $target_url = "https://staging.eko.in:25004/ekoapi/v1/user/service/activate";   
        // $target_url = "https://staging.eko.in/ekoapi/v1/user/service/activate";
        $fname1 = "C:\Users\Admin\Downloads\barber.jpg";
        $fname2 = "C:\Users\Admin\Downloads\barber.jpg";
        $fname3 = "C:\Users\Admin\Downloads\barber.jpg";
        
        
        $cfile1 = new \CURLFile(realpath($fname1));
        $cfile2 = new \CURLFile(realpath($fname2));
        $cfile3 = new \CURLFile(realpath($fname3));

            $post = array (
                    'pan_card' => $cfile1,
                    'aadhar_front' => $cfile2,
                    'aadhar_back' => $cfile3,
                    'form-data' => 'service_code=43&initiator_id=9962981729&user_code=20110002',
                    ); 

        $curl = curl_init();
        $key = env("EKO_STAGING_KEY");
        $encodedKey = base64_encode($key);
        $secret_key_timestamp = (int)(round(microtime(true) * 1000));
        $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
        $secret_key = base64_encode($signature);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, env("SSL_VERIFY"));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,  env("SSL_VERIFY"));
        curl_setopt($curl, CURLOPT_URL, $target_url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($curl, CURLOPT_HTTPHEADER,array('Content-Type: multipart/form-data','developer_key: becbbce45f79c6f5109f848acd540567', 'secret-key:'.$secret_key,'secret-key-timestamp:'.$secret_key_timestamp));
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);   
        curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);  
        curl_setopt($curl, CURLOPT_TIMEOUT, 100);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $Activate_QR = "Something went wrong";
        if ($err) {
            $Activate_QR = "Error - ".$err;
        }else{
            $Activate_QR = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('Activate_QR',$Activate_QR);
    }

    public function Generate_QR() {
        $curl = curl_init();
        $key = env("EKO_STAGING_KEY");
        $encodedKey = base64_encode($key);
        $secret_key_timestamp = (int)(round(microtime(true) * 1000));
        $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
        $secret_key = base64_encode($signature);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, env("SSL_VERIFY"));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,  env("SSL_VERIFY"));
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://staging.eko.in:25004/ekoapi/v1/pan/verify',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'pan_number=VBLPZ6447L&purpose=1&initiator_id=9971771929&purpose_desc=onboarding',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded',
            'developer_key: becbbce45f79c6f5109f848acd540567',
            'secret-key:'.$secret_key,
            'secret-key-timestamp:'.$secret_key_timestamp,
        ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $Generate_QR = "Something went wrong";
        if ($err) {
            $Generate_QR = "Error - ".$err;
        }else{
            $Generate_QR = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('Generate_QR',$Generate_QR);
    }

    public function Get_Customer() {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYHOST => env("SSL_VERIFY"),
            CURLOPT_SSL_VERIFYPEER => env("SSL_VERIFY"),
            CURLOPT_URL => 'https://staging.eko.in:25004/ekoapi/v2/customers/mobile_number:9962981729',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POSTFIELDS =>'initiator_id=9962981729&user_code=20810200',
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'developer_key: becbbce45f79c6f5109f848acd540567',
                'secret-key: MC6dKW278tBef+AuqL/5rW2K3WgOegF0ZHLW/FriZQw=',
                'secret-key-timestamp: 1516705204593',
                'content-type: application/x-www-form-urlencoded'
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $Get_Customer = 'Something went wrong';
        if ($err) {
            $Get_Customer = "Error - ".$err;
        }else{
            $Get_Customer = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('Get_Customer',$Get_Customer);
    }

    public function Create_Customer() {
        $curl = curl_init();
        $key = env("EKO_STAGING_KEY");
        $encodedKey = base64_encode($key);
        $secret_key_timestamp = (int)(round(microtime(true) * 1000));
        $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
        $secret_key = base64_encode($signature);
        $d  = array (
            "line"=> "Main Road","city"=>"Coimbatore","state"=>"Tamil Nadu","pincode"=>"641668","district"=>"Coimbatore","area"=>"Somanur"
        );
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYHOST => env("SSL_VERIFY"),
            CURLOPT_SSL_VERIFYPEER => env("SSL_VERIFY"),
            CURLOPT_URL => 'https://staging.eko.in:25004/ekoapi/v2/customers/mobile_number:8870778821',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => 'initiator_id=9962981729&name=Guddu&user_code=20110002&dob=1991-07-01&residence_address='.json_encode($d).'&skip_verification=true',
            CURLOPT_HTTPHEADER => array(
                'developer_key: becbbce45f79c6f5109f848acd540567',
                'Content-Type: application/x-www-form-urlencoded',
                'secret-key:'.$secret_key,
                'secret-key-timestamp:'.$secret_key_timestamp 
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $Create_Customer = 'Something went wrong';
        if ($err) {
            $Create_Customer = "Error - ".$err;
        }else{
            $Create_Customer = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('Create_Customer',$Create_Customer);
    }

    public function Verify_Customer() {
        $curl = curl_init();
        $key = env("EKO_STAGING_KEY");
        $encodedKey = base64_encode($key);
        $secret_key_timestamp = (int)(round(microtime(true) * 1000));
        $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
        $secret_key = base64_encode($signature);
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYHOST => env("SSL_VERIFY"),
            CURLOPT_SSL_VERIFYPEER => env("SSL_VERIFY"),
            CURLOPT_URL => 'https://staging.eko.in:25004/ekoapi/v2/customers/verification/otp:160613',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => 'initiator_id=9962981729&id_type=mobile_number&id=8870778821&otp_ref_id=d3e00033-ebd1-5492-a631-53f0dbf00d69&user_code=20810200&pipe=9',
            CURLOPT_HTTPHEADER => array(
                'developer_key: becbbce45f79c6f5109f848acd540567',
                'secret-key:'.$secret_key,
                'secret-key-timestamp:'.$secret_key_timestamp,
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $Verify_Customer = 'Something went wrong';
        if ($err) {
            $Verify_Customer = "Error - ".$err;
        }else{
            $Verify_Customer = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('Verify_Customer',$Verify_Customer);
    }

    public function Get_Bank_Details() {
        $curl = curl_init();
        $key = env("EKO_STAGING_KEY");
        $encodedKey = base64_encode($key);
        $secret_key_timestamp = (int)(round(microtime(true) * 1000));
        $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
        $secret_key = base64_encode($signature);
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYHOST => env("SSL_VERIFY"),
            CURLOPT_SSL_VERIFYPEER => env("SSL_VERIFY"),
            CURLOPT_URL => 'https://staging.eko.in:25004/ekoapi/v2/banks?bank_code=IDFB&initiator_id=9962981729&user_code=20810200',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'developer_key: becbbce45f79c6f5109f848acd540567',
                'secret-key:'.$secret_key,
                'secret-key-timestamp:'.$secret_key_timestamp,
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $Get_Bank_Details = 'Something went wrong';
        if ($err) {
            $Get_Bank_Details = "Error - ".$err;
        }else{
            $Get_Bank_Details = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('Get_Bank_Details',$Get_Bank_Details);
    }

    public function Add_Recipient(){
        $curl = curl_init();
        $key = env("EKO_STAGING_KEY");
        $encodedKey = base64_encode($key);
        $secret_key_timestamp = (int)(round(microtime(true) * 1000));
        $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
        $secret_key = base64_encode($signature);
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYHOST => env("SSL_VERIFY"),
            CURLOPT_SSL_VERIFYPEER => env("SSL_VERIFY"),
            CURLOPT_URL => 'https://staging.eko.in:25004/ekoapi/v2/customers/mobile_number:7661109875/recipients/acc_ifsc:1711890657_KKBK0000731',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => 'initiator_id=9962981729&recipient_mobile=9115597890&bank_id=56&recipient_type=3&recipient_name=Aditya&user_code=20810200',
            CURLOPT_HTTPHEADER => array(
                'developer_key: becbbce45f79c6f5109f848acd540567',
                'secret-key:'.$secret_key,
                'secret-key-timestamp:'.$secret_key_timestamp,
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $Add_Recipient = 'Something went wrong';
        if ($err) {
            $Add_Recipient = "Error - ".$err;
        }else{
            $Add_Recipient = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('Add_Recipient',$Add_Recipient);
    }

    public function Get_Recipient() {
        $curl = curl_init();
        $key = env("EKO_STAGING_KEY");
        $encodedKey = base64_encode($key);
        $secret_key_timestamp = (int)(round(microtime(true) * 1000));
        $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
        $secret_key = base64_encode($signature);
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYHOST => env("SSL_VERIFY"),
            CURLOPT_SSL_VERIFYPEER => env("SSL_VERIFY"),
            CURLOPT_URL => 'https://staging.eko.in:25004/ekoapi/v2/customers/mobile_number:8800990087/recipients/recipient_id:10019064?initiator_id=9962981729&user_code=20810200',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'developer_key: becbbce45f79c6f5109f848acd540567',
                'secret-key:'.$secret_key,
                'secret-key-timestamp:'.$secret_key_timestamp
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $Get_Recipient = 'Something went wrong';
        if ($err) {
            $Get_Recipient = "Error - ".$err;
        }else{
            $Get_Recipient = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('Get_Recipient',$Get_Recipient);
    }

    public function Get_List_of_Recipients() {
        $curl = curl_init();
        $key = env("EKO_STAGING_KEY");
        $encodedKey = base64_encode($key);
        $secret_key_timestamp = (int)(round(microtime(true) * 1000));
        $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
        $secret_key = base64_encode($signature);
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYHOST => env("SSL_VERIFY"),
            CURLOPT_SSL_VERIFYPEER => env("SSL_VERIFY"),
            CURLOPT_URL => 'https://staging.eko.in:25004/ekoapi/v2/customers/mobile_number:8800990087/recipients?initiator_id=9962981729&user_code=20810200',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'developer_key: becbbce45f79c6f5109f848acd540567',
                'secret-key:'.$secret_key,
                'secret-key-timestamp:'.$secret_key_timestamp
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $Get_List_of_Recipients = 'Something went wrong';
        if ($err) {
            $Get_List_of_Recipients = "Error - ".$err;
        }else{
            $Get_List_of_Recipients = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('Get_List_of_Recipients',$Get_List_of_Recipients);
    }

    public function Initiate_Transaction() {
        $curl = curl_init();
        $key = env("EKO_STAGING_KEY");
        $encodedKey = base64_encode($key);
        $secret_key_timestamp = (int)(round(microtime(true) * 1000));
        $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
        $secret_key = base64_encode($signature);
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYHOST => env("SSL_VERIFY"),
            CURLOPT_SSL_VERIFYPEER => env("SSL_VERIFY"),
            CURLOPT_URL => 'https://staging.eko.in:25004/ekoapi/v2/transactions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'initiator_id=9962981729&customer_id=8800990087&recipient_id=10019064&amount=200&channel=2&state=1&timestamp=2018-09-21%2006%3A11%3A55&currency=INR&latlong=26.8863786%2C75.7393589&client_ref_id=RIM10011909045679290&user_code=20810200',
            CURLOPT_HTTPHEADER => array(
                'developer_key: becbbce45f79c6f5109f848acd540567',
                'secret-key:'.$secret_key,
                'secret-key-timestamp:'.$secret_key_timestamp,
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $Initiate_Transaction = 'Something went wrong';
        if ($err) {
            $Initiate_Transaction = "Error - ".$err;
        }else{
            $Initiate_Transaction = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('Initiate_Transaction',$Initiate_Transaction);
    }

    public function Transaction_Inquiry() {
        $curl = curl_init();
        $key = env("EKO_STAGING_KEY");
        $encodedKey = base64_encode($key);
        $secret_key_timestamp = (int)(round(microtime(true) * 1000));
        $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
        $secret_key = base64_encode($signature);
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYHOST => env("SSL_VERIFY"),
            CURLOPT_SSL_VERIFYPEER => env("SSL_VERIFY"),
            CURLOPT_URL => 'https://staging.eko.in:25004/ekoapi/v2/transactions/2157011338?initiator_id=9962981729&user_code=20810200',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'secret-key:'.$secret_key,
                'secret-key-timestamp:'.$secret_key_timestamp,
                'developer_key: becbbce45f79c6f5109f848acd540567'
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $Transaction_Inquiry = 'Something went wrong';
        if ($err) {
            $Transaction_Inquiry = "Error - ".$err;
        }else{
            $Transaction_Inquiry = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('Transaction_Inquiry',$Transaction_Inquiry);
    }

    public function Refund() {
        $curl = curl_init();
        $key = env("EKO_STAGING_KEY");
        $encodedKey = base64_encode($key);
        $secret_key_timestamp = (int)(round(microtime(true) * 1000));
        $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
        $secret_key = base64_encode($signature);
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYHOST => env("SSL_VERIFY"),
            CURLOPT_SSL_VERIFYPEER => env("SSL_VERIFY"),
            CURLOPT_URL => 'https://staging.eko.in:25004/ekoapi/v2/transactions/2147591637/refund',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'initiator_id=9962981729&otp=3682953466&state=1&user_code=20810200',
            CURLOPT_HTTPHEADER => array(
                'developer_key: becbbce45f79c6f5109f848acd540567',
                'secret-key:'.$secret_key,
                'secret-key-timestamp:'.$secret_key_timestamp,
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $Refund = 'Something went wrong';
        if ($err) {
            $Refund = "Error - ".$err;
        }else{
            $Refund = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('Refund',$Refund);
    }

    public function Activate_AePS(){
        $target_url = "https://staging.eko.in:25004/ekoapi/v1/user/service/activate";   

        $fname1 = "C:\Users\Admin\Downloads\barber.jpg";
        $fname2 = "C:\Users\Admin\Downloads\barber.jpg";
        $fname3 = "C:\Users\Admin\Downloads\barber.jpg";
 
 
        $cfile1 = new \CURLFile(realpath($fname1));
        $cfile2 = new \CURLFile(realpath($fname2));
        $cfile3 = new \CURLFile(realpath($fname3));

        $post = array (
            'pan_card' => $cfile1,
            'aadhar_front' => $cfile2,
            'aadhar_back' => $cfile3,
            'form-data' => 'service_code=43&initiator_id=9962981729&user_code=20110002&devicenumber=123234234234234&modelname=Morpho&office_address={"line": "Eko India","city":"Gurgaon","state":"Haryana","pincode":"122002"}&address_as_per_proof={"line": "Eko India","city":"Gurgaon","state":"Haryana","pincode":"122002"}',
        ); 
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, env("SSL_VERIFY"));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,  env("SSL_VERIFY"));
        curl_setopt($curl, CURLOPT_URL, $target_url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($curl, CURLOPT_HTTPHEADER,array('Content-Type: multipart/form-data','developer_key: becbbce45f79c6f5109f848acd540567', 'secret-key:MC6dKW278tBef+AuqL/5rW2K3WgOegF0ZHLW/FriZQw=','secret-key-timestamp:1516705204593'));
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);   
        curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);  
        curl_setopt($curl, CURLOPT_TIMEOUT, 100);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $Activate_AePS = 'Something went wrong';
        if ($err) {
            $Activate_AePS = "Error - ".$err;
        }else{
            $Activate_AePS = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('Activate_AePS',$Activate_AePS);  			  
    }

    public function AePS_KYC_OTP_Request () {
        $curl = curl_init();
        curl_setopt_array($curl, [
        CURLOPT_SSL_VERIFYHOST => env("SSL_VERIFY"),
        CURLOPT_SSL_VERIFYPEER =>  env("SSL_VERIFY"),
        CURLOPT_URL => "https://staging.eko.in/ekoapi/v2/aeps/otp",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_POSTFIELDS => "initiator_id=9962981729&user_code=20110002&customer_id=9123354235&aadhar=122112212211&latlong=28.78123,72.808912",
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "accept: text/plain",
            "content-type: application/x-www-form-urlencoded"
        ],
        ]);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $AePS_KYC_OTP_Request = 'Something went wrong';
        if ($err) {
            $AePS_KYC_OTP_Request = "Error - ".$err;
        }else{
            $AePS_KYC_OTP_Request = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('AePS_KYC_OTP_Request',$AePS_KYC_OTP_Request); 
    }

    public function AePS_KYC_OTP_Verify() {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_SSL_VERIFYHOST => env("SSL_VERIFY"),
            CURLOPT_SSL_VERIFYPEER =>  env("SSL_VERIFY"),
            CURLOPT_URL => "https://staging.eko.in/ekoapi/v2/aeps/otp/verify",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => [
                "accept: text/plain",
                "content-type: application/x-www-form-urlencoded"
            ],
        ]);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $AePS_KYC_OTP_Verify = 'Something went wrong';
        if ($err) {
            $AePS_KYC_OTP_Verify = "Error - ".$err;
        }else{
            $AePS_KYC_OTP_Verify = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('AePS_KYC_OTP_Verify',$AePS_KYC_OTP_Verify);
    }

    public function AePS_KYC_Biometric () {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_SSL_VERIFYHOST => env("SSL_VERIFY"),
            CURLOPT_SSL_VERIFYPEER =>  env("SSL_VERIFY"),
            CURLOPT_URL => "https://staging.eko.in/ekoapi/v2/aeps/kyc",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => [
                "accept: text/plain",
                "content-type: application/x-www-form-urlencoded"
            ],
        ]);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $AePS_KYC_Biometric = 'Something went wrong';
        if ($err) {
            $AePS_KYC_Biometric = "Error - ".$err;
        }else{
            $AePS_KYC_Biometric = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('AePS_KYC_Biometric',$AePS_KYC_Biometric);
    }

    public function AePS_Daily_Auth() {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_SSL_VERIFYHOST => env("SSL_VERIFY"),
            CURLOPT_SSL_VERIFYPEER =>  env("SSL_VERIFY"),
            CURLOPT_URL => "https://staging.eko.in/ekoapi/v2/aeps/dailyKyc",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => [
                "accept: text/plain",
                "content-type: application/x-www-form-urlencoded"
            ],
        ]);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $AePS_Daily_Auth = 'Something went wrong';
        if ($err) {
            $AePS_Daily_Auth = "Error - ".$err;
        }else{
            $AePS_Daily_Auth = "Success - ".$response;
        }
        curl_close($curl);
        return view('eko')->with('AePS_Daily_Auth',$AePS_Daily_Auth);
    }
}
