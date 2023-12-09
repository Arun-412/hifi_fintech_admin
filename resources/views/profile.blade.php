@extends('layouts.master')
@section('content')
<section style="margin-top: 110px;margin-bottom: 40px;padding: 0px 30px;">
    <div class="container-fluid">
<h2>Profile</h2>
<hr>
<p>Shop Name : <b>{{Auth::user()->shop_name}}</b></p>
<p>User Code : <b>{{Auth::user()->door_code}}</b></p>
<p>Mobile Number : <b>{{Auth::user()->mobile_number}}</b></p>
<p>Email : <b>{{Auth::user()->email}}</b></p>
<p>Distributer Code : <b>{{Auth::user()->door_opened_by}}</b></p>
<p>KYC Status : @if(Auth::user()->kyc_status == 'HFY')<b>Completed</b>@elseif((Auth::user()->kyc_status == 'HFI'))<b>Incomplete</b>@else<b>Pending</b>@endif</p>
<hr>
<h5>Charge Details</h5>
<style>
    table, th, td {
  border: 1px solid black;
}
</style>
<table>
        <tr>
            <th>S.No</th>
            <th>Services</th>
            <th>Charges</th>
        </tr>
        <tr>
            <td>1</td>
            <td>Payout</td>
            <td>5.5+GST</td>
        </tr>
</table>

</div>
</section>
@endsection