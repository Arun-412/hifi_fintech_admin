@extends('layouts.master')
@section('content')
<section style="margin-top: 110px;margin-bottom: 40px;padding: 0px 30px;">
    <div class="container-fluid">
<h2>Settings</h2>
<hr>
<h5>Change Password</h5>
<input type="password" name="old_password" placeholder="Old Password" id="">
<input type="password" name="new_password" placeholder="New Password" id="">
<input type="password" name="confirm_password" placeholder="Confirm Password" id="">
<input type="button" value="Change Password">
<hr>
<h5>Password</h5> (need to show in model)
<small>(You need to enter your password to process this request)</small>
<input type="password" name="confirm_password" placeholder="Password" id="">
<input type="button" value="Proceed">
<hr>
<h5>Master Logout</h5>
<small>(if you use logout from all devices all logined access of your will be logout from all devices (highly recommended twise in a week))</small>
<input type="button" value="logout from all devices">
<hr>
<h5>Access Report</h5>
<p>payout report</p>
<input type="checkbox" name="" id="">
<p>profit report</p>
<input type="checkbox" name="" id="">
<hr>
</div>
</section>
@endsection