@extends('layouts.master-layout')
@section('title','Login')
@section('extra-css')
@endsection

@section('content')
    <div class="page main-signin-wrapper">
        <!-- Row -->
        <div class="row text-center pl-0 pr-0 ml-0 mr-0">
            <div class="col-lg-3 d-block mx-auto">
                <div class="text-center mb-2">
                    <img src="{{asset('backend/assets/img/logo.png')}}" class="header-brand-img" alt="logo">
                    <img src="{{asset('backend/assets/img/logo.png')}}" class="header-brand-img theme-logos" alt="logo">
                </div>
                <br>
                <div class="card custom-card">
                    <div class="card-body">
                        <h4 class="text-center">Sign In to Your Account</h4>


                        @include('backend.admin.messages.message-jquery-confirm')

                        <form method="POST" action="{{ route('logged_in') }}">
                            @csrf
                            <div class="form-group text-left">
                                <label>Email</label>
                                <input name="email" :value="old('email')" class="form-control"
                                       placeholder="Enter Your Email " type="email" required autofocus
                                       autocomplete="off" oninvalid="this.setCustomValidity('Please Enter valid email')" oninput="setCustomValidity('')">
                            </div>
                            <div class="form-group text-left">
                                <label>Password</label>
                                <input name="password" class="form-control" placeholder="Enter your password"
                                       type="password" required autofocus oninvalid="this.setCustomValidity('Please Enter your password')" oninput="setCustomValidity('')">
                            </div>

                            <div class="form-group text-left">
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox" name="remember">
                                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                                </label>
                            </div>

                            <button type="submit" class="btn ripple btn-main-primary btn-block">Sign In</button>
                        </form>
                        @if (Route::has('password.request'))
                            <div class="mt-3 text-center">
                                <p class="mb-1"><a href="{{ route('password.request') }}">Forgot password?</a></p>
                            </div>
                        @endif
                    </div>
                </div>
                <footer>
                    <small><img src="{{asset('backend/assets/img/fav_icon.ico')}}" alt="Techie Squad"
                                style="width:25px; float: none; vertical-align: middle;"> Powered By <a
                            href="https://techiesquad.com" target="_blank" class="white-text">Techie
                            Squad<sup>®</sup></a></small>
                </footer>
            </div>
        </div>
        <!-- End Row -->
    </div>
@endsection
@section('extra-js')
@endsection
