<!-- <form >
    
    <input type="text"  id="shop_name" placeholder="Enter Shop Name">
   
    <input type="tel"  id="mobile_number" placeholder="Enter mobile number">

    <input type="email" id="email" placeholder="enter email">

    <input type="password"  id="password" placeholder="enter password">

    <input type="password" id="password_confirmation" placeholder="enter confirm password">

    <input type="checkbox" name="agree" id="agree">

    <input type="submit" value="Login">
    <a href="/">Already have account?</a>
</form> -->

@extends('layouts.master')
@section('content')
<section style="height: unset;" class="login">
        <div class="container">
            <div  class="row login-box">
                <div class="col-sm-6 col-md-6 col-xs-12 login-image">
                </div>
                <div class="col-sm-6 col-md-6 col-xs-12">
                    <div class="form-box text-center">
                    <img class="logo" src="{{asset('assets/images/logo.PNG')}}"> 
                        <h4 style="text-align: left;" class="welcome-text">Register Here!</h2>
                        @if(session('failed'))
						<div style="background-color:red;color:white;font-weight:800;padding:10px;border-radius:5px;">
							{{ session('failed') }}
						</div>
					@endif
                        <form action="register" method="post">@csrf 
                            <div class="mb-3 form-inputs">
                                <label for="exampleFormControlInput1" class="form-label">Shop Name                                </label>
                                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Shop Name
                                " name="shop_name" minlength="3" pattern=".{0}|.{3,40}" title="Shop Name need atleast 3 characters" required autofocus maxlength="40" value="{{ old('shop_name') }}">
                                <i class="bi bi-bag-fill"></i>
                            
                            </div>
                            @error('shop_name')
    <span class="text-danger">{{$message}}</span>
    @enderror
                            <div class="mb-3 form-inputs">
                                <label for="exampleFormControlInput1" class="form-label">Mobile Number                                </label>
                                <input type="text" required pattern=".{0}|.{10,10}" title="Mobile number must be 10 digit" class="form-control" id="exampleFormControlInput1" placeholder="Mobile Number"   
                                name="mobile_number" oninput="this.value = this.value.replace(/[^0-9]/g, '')" minlength="10" maxlength="10"  value="{{ old('mobile_number') }}">
                                <i class="bi bi-phone-fill"></i></div>
                                @error('mobile_number')
    <span class="text-danger">{{$message}}</span>
    @enderror
                            
                            <div class="mb-3 form-inputs">
                                <label for="exampleFormControlInput1" class="form-label">Email</label>
                                <input type="email" class="form-control" required id="exampleFormControlInput1" placeholder="Email"
                                name="email" minlength="10" pattern=".{0}|.{5,40}" title="Email need atleast 3 characters" maxlength="40" value="{{ old('email') }}">
                                <i class="bi bi-envelope-fill"></i></div>
                                @error('email')
    <span class="text-danger">{{$message}}</span>
    @enderror
                            
                        <div class="mb-3 form-inputs">
                            <label for="exampleFormControlInput1" class="form-label">Password</label>
                            <input type="password" id="password" class="form-control" placeholder="Password"
                            name="password" minlength="8" maxlength="40" required pattern=".{0}|.{8,40}" title="Password need atleast 8 characters">
                            <i class="bi bi-lock-fill"></i>
                            <i style="right: 8px;left: unset;" id="show_password" class="bi bi-eye-fill"></i>
                            <i style="right: 8px;left: unset;"  id="hide_password" class="bi bi-eye-slash-fill"></i> </div>
                            @error('password')
    <span class="text-danger">{{$message}}</span>
    @enderror
                       
                        <div class="mb-3 form-inputs">
                            <label for="exampleFormControlInput1" class="form-label">Confirm Password</label>
                            <input type="password" id="c_password" class="form-control" placeholder="Confirm Password"
                            name="password_confirmation" minlength="8" maxlength="40" required pattern=".{0}|.{8,40}" title="Confirm Password need atleast 8 characters">
                            <i class="bi bi-lock-fill"></i>
                            <i style="right: 8px;left: unset;" id="c_show_password" class="bi bi-eye-fill"></i>
                            <i style="right: 8px;left: unset;"  id="c_hide_password" class="bi bi-eye-slash-fill"></i>
                        </div>
                        <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="agree" required id="flexCheckDefault">
                        <label style="    font-size: 15px; " class="form-check-label" for="flexCheckDefault">
                            I agree to the <a href="#" class="signup-link">Terms of Service</a> and <a href="#" class="signup-link">Privacy Policy</a>
                        </label>    </div>
                        @error('agree')
    <span class="text-danger">{{$message}}</span>
    @enderror
                    
                        
                        <button class="btn login-btn" type="submit">Login</button>
                        </form>
                        <p class="signup-text">Already registered? <a href="/" class="signup-link">Login</a></p>

                        </div>
                       
                    </div>
                </div>
            </div>
    </section>
@endsection