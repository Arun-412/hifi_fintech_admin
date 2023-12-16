<hr><hr><h3 style="background-color:green;color:white;">Onboarding</h3>
<form action="{{route('Onboard_User')}}" method="post">
    @csrf
    @if(!empty($Onboard_User))
        <p>{{ $Onboard_User }}</p>
    @endif
    <input type="submit" value="Onboard User">
</form>
<form action="{{route('Get_Services')}}" method="post">
    @csrf
    @if(!empty($Get_Services))
        <p>{{ $Get_Services }}</p>
    @endif
    <input type="submit" value="Get Services">
</form>
<form action="{{route('Request_OTP')}}" method="post">
    @csrf
    @if(!empty($Request_OTP))
        <p>{{ $Request_OTP }}</p>
    @endif
    <input type="submit" value="Request OTP">
</form>
<form action="{{route('Verify_User_Mobile_Number')}}" method="post">
    @csrf
    @if(!empty($Verify_User_Mobile_Number))
        <p>{{ $Verify_User_Mobile_Number }}</p>
    @endif
    <input type="submit" value="Verify User Mobile Number">
</form>
<form action="{{route('User_Services_Enquiry')}}" method="post">
    @csrf
    @if(!empty($User_Services_Enquiry))
        <p>{{ $User_Services_Enquiry }}</p>
    @endif
    <input type="submit" value="User Services Enquiry">
</form>
<hr><hr><h3>Payout</h3>
<form action="{{route('Activate_Payout')}}" method="post">
    @csrf
    @if(!empty($Activate_Payout))
        <p>{{ $Activate_Payout }}</p>
    @endif
    <input style="background-color:red;color:white;" type="submit" value="Activate Payout">
</form>
<form action="{{route('Payout_Fund_Transfer')}}" method="post">
    @csrf
    @if(!empty($Payout_Fund_Transfer))
        <p>{{ $Payout_Fund_Transfer }}</p>
    @endif
    <input type="submit" value="Payout Fund Transfer">
</form>
<form action="{{route('Payout_Transaction_Status_By_ID')}}" method="post">
    @csrf
    @if(!empty($Payout_Transaction_Status_By_ID))
        <p>{{ $Payout_Transaction_Status_By_ID }}</p>
    @endif 
    <input style="background-color:blue;color:white;" type="submit" value="Payout Transaction Status By ID">
</form>
<form action="{{route('Payout_Transaction_Status')}}" method="post">
    @csrf
    @if(!empty($Payout_Transaction_Status))
        <p>Status Update Automatically using webhook <br>
        @foreach($Payout_Transaction_Status as $d=>$p)
        {{$d}} : {{$p}}<br>
        @endforeach
        </p>
    @endif 
    <input style="background-color:blue;color:white;" type="submit" value="Payout Transaction Status">
</form>
<hr><hr><h3 style="background-color:green;color:white;">Money Transfer</h3>
<form action="{{route('Get_Customer')}}" method="post">
    @csrf
    @if(!empty($Get_Customer))
        <p>{{ $Get_Customer }}</p>
    @endif
    <input type="submit" value="Get Customer">
</form>
<form action="{{route('Create_Customer')}}" method="post">
    @csrf
    @if(!empty($Create_Customer))
        <p>{{ $Create_Customer }}</p>
    @endif
    <input type="submit" value="Create Customer">
</form>
<form action="{{route('Verify_Customer')}}" method="post">
    @csrf
    @if(!empty($Verify_Customer))
        <p>{{ $Verify_Customer }}</p>
    @endif
    <input type="submit" value="Verify Customer">
</form>
<form action="{{route('Get_Bank_Details')}}" method="post">
    @csrf
    @if(!empty($Get_Bank_Details))
        <p>{{ $Get_Bank_Details }}</p>
    @endif
    <input type="submit" value="Get Bank Details">
</form>
<form action="{{route('Bank_Account_Verification')}}" method="post">
    @csrf
    @if(!empty($Bank_Account_Verification))
        <p>{{ $Bank_Account_Verification }}</p>
    @endif
    <input type="submit" value="Bank Account Verification">
</form>
<form action="{{route('Add_Recipient')}}" method="post">
    @csrf
    @if(!empty($Add_Recipient))
        <p>{{ $Add_Recipient }}</p>
    @endif
    <input type="submit" value="Add Recipient">
</form>
<form action="{{route('Get_Recipient')}}" method="post">
    @csrf
    @if(!empty($Get_Recipient))
        <p>{{ $Get_Recipient }}</p>
    @endif
    <input type="submit" value="Get Recipient">
</form>
<form action="{{route('Get_List_of_Recipients')}}" method="post">
    @csrf
    @if(!empty($Get_List_of_Recipients))
        <p>{{ $Get_List_of_Recipients }}</p>
    @endif
    <input type="submit" value="Get List of Recipients">
</form>
<form action="{{route('Initiate_Transaction')}}" method="post">
    @csrf
    @if(!empty($Initiate_Transaction))
        <p>{{ $Initiate_Transaction }}</p>
    @endif
    <input type="submit" value="Initiate Transaction">
</form>
<form action="{{route('Transaction_Inquiry')}}" method="post">
    @csrf
    @if(!empty($Transaction_Inquiry))
        <p>{{ $Transaction_Inquiry }}</p>
    @endif
    <input type="submit" value="Transaction Inquiry">
</form>
<form action="{{route('Refund')}}" method="post">
    @csrf
    @if(!empty($Refund))
        <p>{{ $Refund }}</p>
    @endif
    <input type="submit" value="Refund">
</form>
<form action="{{route('Generate_QR')}}" method="post">
    @csrf
    @if(!empty($Generate_QR))
        <p>{{ $Generate_QR }}</p>
    @endif
    <input type="submit" value="Generate QR">
</form>
<form action="{{route('Activate_QR')}}" method="post">
    @csrf
    @if(!empty($Activate_QR))
        <p>{{ $Activate_QR }}</p>
    @endif
    <input style="background-color:red;color:white;"  type="submit" value="Activate QR">
</form>
<!-- <hr><hr><h3>AePS</h3>
<form action="{{route('Activate_AePS')}}" method="post">
    @csrf
    @if(!empty($Activate_AePS))
        <p>{{ $Activate_AePS }}</p>
    @endif
    <input style="background-color:red;color:white;" type="submit" value="Activate AePS">
</form>
<h3 style="background-color:red;color:white;">AePS E-KYC</h3>
<form action="{{route('AePS_KYC_OTP_Request')}}" method="post">
    @csrf
    @if(!empty($AePS_KYC_OTP_Request))
        <p>{{ $AePS_KYC_OTP_Request }}</p>
    @endif
    <input type="submit" value="AePS KYC OTP Request">
</form>
<form action="{{route('AePS_KYC_OTP_Verify')}}" method="post">
    @csrf
    @if(!empty($AePS_KYC_OTP_Verify))
        <p>{{ $AePS_KYC_OTP_Verify }}</p>
    @endif
    <input type="submit" value="AePS KYC OTP Verify">
</form>
<form action="{{route('AePS_KYC_Biometric')}}" method="post">
    @csrf
    @if(!empty($AePS_KYC_Biometric))
        <p>{{ $AePS_KYC_Biometric }}</p>
    @endif
    <input type="submit" value="AePS KYC Biometric">
</form>
<form action="{{route('AePS_Daily_Auth')}}" method="post">
    @csrf
    @if(!empty($AePS_Daily_Auth))
        <p>{{ $AePS_Daily_Auth }}</p>
    @endif
    <input type="submit" value="AePS Daily Auth">
</form>
<hr><h3>AePS API</h3>
<form action="{{route('AePS_Daily_Auth')}}" method="post">
    @csrf
    @if(!empty($AePS_Daily_Auth))
        <p>{{ $AePS_Daily_Auth }}</p>
    @endif
    <input type="submit" value="AePS Daily Auth">
</form> -->
<hr> <hr><h3>Pan Verification</h3>
<form action="{{route('Pan_Verify')}}" method="post">
    @csrf
    @if(!empty($Pan_Verify))
        <p>{{ $Pan_Verify }}</p>
    @endif
    <input type="submit" value="Verify PAN">
</form>
<hr><h3>Aadhar Verification</h3>
<form action="{{route('Aadhar_Consent')}}" method="post">
    @csrf
    @if(!empty($Aadhar_Consent))
        <p>{{ $Aadhar_Consent }}</p>
    @endif
    <input type="submit" value="Aadhar Consent">
</form>
<form action="{{route('Aadhaar_OTP')}}" method="post">
    @csrf
    @if(!empty($Aadhaar_OTP))
        <p>{{ $Aadhaar_OTP }}</p>
    @endif
    <input style="background-color:red;color:white;"  type="submit" value="Aadhaar OTP">
</form>
<form action="{{route('Aadhaar_File')}}" method="post">
    @csrf
    @if(!empty($Aadhaar_File))
        <p>{{ $Aadhaar_File }}</p>
    @endif
    <input style="background-color:red;color:white;"  type="submit" value="Aadhaar File">
</form>

