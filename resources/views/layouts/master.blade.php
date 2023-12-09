@include('layouts.header')
@if(Auth::check())
    @include('layouts.menubar')
@endif
@yield('content')
@include('layouts.footer')