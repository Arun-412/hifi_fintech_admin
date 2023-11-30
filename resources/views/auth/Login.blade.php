@extends('layouts.master')
@section('content')
<section class="login">
        <div class="container">
            <div class="row login-box">
                <div class="col-sm-6 col-md-6 col-xs-12 login-image">
                </div>
                <div class="col-sm-6 col-md-6 col-xs-12">
                    <div class="form-box text-center">
                        <img class="logo" src="{{asset('assets/images/logo.PNG')}}"> 
                        <h4 class="welcome-text">Welcome Back</h2>
                    
                    @if(session('failed'))
                        <div class="alert alert-danger"> {{ session('failed') }}</div>
					@endif
                        <form action="loggedin" method="post"> @csrf
                        <div class="mb-3 form-inputs">
                            <label for="exampleFormControlInput1" class="form-label">Username</label>
                            <input type="text" name="username" autofocus required maxlength="20" placeholder="Username" class="form-control @error('username') is-invalid @enderror" id="exampleFormControlInput1" autocomplete="off">
                            <i class="bi bi-phone-fill"></i>
                        </div>
                        @error('username') <p class="text-danger">{{ $message }}</p> @enderror
                        <div class="mb-3 form-inputs">
                            <label for="exampleFormControlInput1" class="form-label">Password</label>
                            <input type="password" id="password" pattern=".{0}|.{1,40}" title="Password must need to login" required name="password" placeholder="Password" class="form-control" autocomplete="off">
                            <i class="bi bi-lock-fill"></i>
                            <i style="right: 8px;left: unset;" id="show_password" class="bi bi-eye-fill"></i>
                            <i style="right: 8px;left: unset;" id="hide_password" class="bi bi-eye-slash-fill"></i>
                        </div>
                        
                        <button class="btn login-btn" type="submit">Login</button></form>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection