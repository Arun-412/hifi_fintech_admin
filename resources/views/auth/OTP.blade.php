@extends('layouts.master')
@section('content')
<section class="otp-screen">
        <div class="container">
            <div  class="row otp-box">
                <div class="col-md-12">
                </div>
                <img class="otp-image" src="{{asset('assets/images/otp.png')}}">
                <h4 class="welcome-text">Verification Code</h4>
                @if(!session('otp') && !session('failed') && !session('user'))
                    @error('mobile_number')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    @error('mobile_otp')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    @if($errors->isEmpty())
                    <script>window.location.replace("/");</script>
                    @endif
                @endif
                @if(session('otp'))
					
            <div class="alert alert-danger">Invalid Verification Code</div>
					@endif
          @if(session('failed'))
                        <div class="alert alert-danger"> {{ session('failed') }}</div>
					@endif
          @if(session('otp'))
          <p>We have sent a verification code
                  to your <b>****{{substr(session('otp'), -2)}}</b></p>
                 
          @else
          <p>We have sent a verification code
                  to your <b>****{{substr(session('user'), -2)}}</b></p>
               
          @endif
               
                <form id="otp_form" action="signin" method="post"> @csrf
                    <div class="otp-inputs" id='inputs'>
                    @if(session('otp'))
                    <input type="hidden" name="mobile_number" value="{{session('otp')}}">
          @else <input type="hidden" name="mobile_number" value="{{session('user')}}">
          @endif
                  <input id='input1' autofocus required type='password' maxLength="1" />
                  <input id='input2' required type='password' maxLength="1" />
                  <input id='input3' required type='password' maxLength="1" />
                  <input id='input4' required type='password' maxLength="1" />
                  <input id='input5' required type='password' maxLength="1" />
                  <input id='input6' required type='password' maxLength="1" />
                </div>
                <input type="hidden" id="mobile_otp" name="mobile_otp">
                <button class="btn login-btn" id="otp_submit" type="submit">Submit</button>
                </form>
                </div>
            </div>
    </section>
    <script>
        //  $("#otp_submit").click( function () {
      $("#otp_form").submit( function () {
            let i1 =  $("#input1").val();
            let i2 =  $("#input2").val();
            let i3 =  $("#input3").val();
            let i4 =  $("#input4").val();
            let i5 =  $("#input5").val();
            let i6 =  $("#input6").val();
            let otp = i1.toString()+i2.toString()+i3.toString()+i4.toString()+i5.toString()+i6.toString();
            console.log(otp);
            $("#mobile_otp").val(otp);
      });
        
            const inputs = ["input1", "input2", "input3", "input4", "input5", "input6"];

inputs.map((id) => {
  const input = document.getElementById(id);
  addListener(input);
});

function addListener(input) {
  input.addEventListener("keyup", () => {
    const code = parseInt(input.value);
    if (code >= 0 && code <= 9) {
      const n = input.nextElementSibling;
      if (n) n.focus();
    } else {
      input.value = "";
    }

    const key = event.key; // const {key} = event; ES6+
    if (key === "Backspace" || key === "Delete") {
      const prev = input.previousElementSibling;
      if (prev) prev.focus();
    }
  });
}
        </script>
@endsection