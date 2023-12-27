<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\ChairController;
use App\Http\Controllers\EkoPayoutChargesController;

// Route::get('/', function () { return view('eko'); }); 

// Route::post('/Pan_Verify', [App\Http\Controllers\EkoController::class, 'Pan_Verify'])->name('Pan_Verify');
// Route::post('/Aadhar_Consent', [App\Http\Controllers\EkoController::class, 'Aadhar_Consent'])->name('Aadhar_Consent');
// Route::post('/Aadhaar_OTP', [App\Http\Controllers\EkoController::class, 'Aadhaar_OTP'])->name('Aadhaar_OTP');
// Route::post('/Aadhaar_File', [App\Http\Controllers\EkoController::class, 'Aadhaar_File'])->name('Aadhaar_File');

// Route::post('/Onboard_User', [App\Http\Controllers\EkoController::class, 'Onboard_User'])->name('Onboard_User');
// Route::post('/Get_Services', [App\Http\Controllers\EkoController::class, 'Get_Services'])->name('Get_Services');
// Route::post('/Request_OTP', [App\Http\Controllers\EkoController::class, 'Request_OTP'])->name('Request_OTP');
// Route::post('/Verify_User_Mobile_Number', [App\Http\Controllers\EkoController::class, 'Verify_User_Mobile_Number'])->name('Verify_User_Mobile_Number');
// Route::post('/User_Services_Enquiry', [App\Http\Controllers\EkoController::class, 'User_Services_Enquiry'])->name('User_Services_Enquiry');

// Route::post('/Activate_Payout', [App\Http\Controllers\EkoController::class, 'Activate_Payout'])->name('Activate_Payout');
// Route::post('/Payout_Fund_Transfer', [App\Http\Controllers\EkoController::class, 'Payout_Fund_Transfer'])->name('Payout_Fund_Transfer');
// Route::post('/Payout_Transaction_Status', [App\Http\Controllers\EkoController::class, 'Payout_Transaction_Status'])->name('Payout_Transaction_Status');
// Route::post('/Payout_Transaction_Status_By_ID', [App\Http\Controllers\EkoController::class, 'Payout_Transaction_Status_By_ID'])->name('Payout_Transaction_Status_By_ID');

// Route::post('/Get_Customer', [App\Http\Controllers\EkoController::class, 'Get_Customer'])->name('Get_Customer');
// Route::post('/Create_Customer', [App\Http\Controllers\EkoController::class, 'Create_Customer'])->name('Create_Customer');
// Route::post('/Verify_Customer', [App\Http\Controllers\EkoController::class, 'Verify_Customer'])->name('Verify_Customer');

// Route::post('/Add_Recipient', [App\Http\Controllers\EkoController::class, 'Add_Recipient'])->name('Add_Recipient');
// Route::post('/Get_Recipient', [App\Http\Controllers\EkoController::class, 'Get_Recipient'])->name('Get_Recipient');
// Route::post('/Get_List_of_Recipients', [App\Http\Controllers\EkoController::class, 'Get_List_of_Recipients'])->name('Get_List_of_Recipients');
// Route::post('/Get_Bank_Details', [App\Http\Controllers\EkoController::class, 'Get_Bank_Details'])->name('Get_Bank_Details');
// Route::post('/Bank_Account_Verification', [App\Http\Controllers\EkoController::class, 'Bank_Account_Verification'])->name('Bank_Account_Verification');

// Route::post('/Initiate_Transaction', [App\Http\Controllers\EkoController::class, 'Initiate_Transaction'])->name('Initiate_Transaction');
// Route::post('/Transaction_Inquiry', [App\Http\Controllers\EkoController::class, 'Transaction_Inquiry'])->name('Transaction_Inquiry');
// Route::post('/Refund', [App\Http\Controllers\EkoController::class, 'Refund'])->name('Refund');

// Route::post('/Activate_QR', [App\Http\Controllers\EkoController::class, 'Activate_QR'])->name('Activate_QR');
// Route::post('/Generate_QR', [App\Http\Controllers\EkoController::class, 'Generate_QR'])->name('Generate_QR');

// Route::post('/Activate_AePS', [App\Http\Controllers\EkoController::class, 'Activate_AePS'])->name('Activate_AePS');
// Route::post('/AePS_KYC_OTP_Request', [App\Http\Controllers\EkoController::class, 'AePS_KYC_OTP_Request'])->name('AePS_KYC_OTP_Request');
// Route::post('/AePS_KYC_OTP_Verify', [App\Http\Controllers\EkoController::class, 'AePS_KYC_OTP_Verify'])->name('AePS_KYC_OTP_Verify');
// Route::post('/AePS_KYC_Biometric', [App\Http\Controllers\EkoController::class, 'AePS_KYC_Biometric'])->name('AePS_KYC_Biometric');
// Route::post('/AePS_Daily_Auth', [App\Http\Controllers\EkoController::class, 'AePS_Daily_Auth'])->name('AePS_Daily_Auth');


Route::get('/verify', function () { return view('auth.OTP'); });

Route::middleware(['guest'])->group(function () {
    Route::get('/', function () { return view('auth.Login'); })->name('login');
    // Route::get('/signup', function () { return view('auth.Register'); });
    Route::post('/loggedin', [UserController::class, 'Login'])->name('loggedin');
    // Route::post('/register', [UserController::class, 'Register'])->name('Register');
    Route::post('/signin', [UserController::class, 'Signin'])->name('Signin');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
    Route::get('/distributer', function () { return view('user_management.distributer'); })->name('distributer');
    Route::get('/report', function () { return view('report'); })->name('report');
    Route::get('/logout', [UserController::class, 'Logout'])->name('logout');

    Route::get('/profile', function () { return view('profile'); })->name('profile');
    Route::get('/settings', function () { return view('settings'); })->name('settings');    
    Route::get('/support', function () { return view('support'); })->name('support');

    Route::get('/services', [ServicesController::class, 'services'])->name('services');

    Route::group(['prefix' => 'services'], function () {  
        Route::group(['prefix' => 'payout'], function () {  
            Route::group(['prefix' => 'eko'], function () {  
                // Route::get('/', function () { return view('services.payout.eko'); })->name('payout_eko');
                Route::get('/add_rule', [ChairController::class, 'add_rule'])->name('payout_eko_add_rule'); 
                Route::get('/', [EkoPayoutChargesController::class, 'get_charges'])->name('payout_eko'); 
            }); 
        });  
    }); 

    Route::group(['prefix' => 'print'], function () {   
        Route::get('/transaction', function () { return view('print.transaction'); })->name('print_transaction');
    }); 
});

