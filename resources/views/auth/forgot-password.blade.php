@extends('layouts.master-layout')
@section('title','Forget Password')
@section('extra-css')
@endsection

@section('content')
    <div class="page main-signin-wrapper">
        <!-- Row -->
        <div class="row text-center pl-0 pr-0 ml-0 mr-0">
            <div class="col-lg-3 d-block mx-auto">
                <div class="text-center mb-2">
                    <img src="{{asset('backend/assets/img/brand2/logo.png')}}" class="header-brand-img" alt="logo">
                    <img src="{{asset('backend/assets/img/brand2/logo-light.png')}}"
                         class="header-brand-img theme-logos" alt="logo">
                </div>
                <div class="card custom-card">
                    <div class="card-body">
                        <h4 class="text-center">Forgot Password</h4>
                        @if(Session::has('status'))
                            <script>
                                $(document).ready(function () {
                                    $.alert({
                                        title: "{{__('Success')}}",
                                        content: "{{Session::get('status')}}",
                                        icon: 'fas fa-smile',
                                        animation: 'scale',
                                        closeAnimation: 'scale',
                                        theme: 'supervan',
                                        autoClose: 'Close|5000',
                                        type: 'green',
                                        buttons: {
                                            Close: {
                                                btnClass: 'btn-green'
                                            }
                                        }
                                    });
                                });
                            </script>
                        @endif


                        @include('backend.admin.messages.message-jquery-confirm')


                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="form-group text-left">
                                <label>Email</label>
                                <input class="form-control" placeholder="Enter your email" type="email" name="email"
                                       :value="old('email')" required autofocus>
                            </div>
                            <button class="btn ripple btn-main-primary btn-block" type="submit">Email Password Reset
                                Link
                            </button>
                        </form>
                    </div>
                    <div class="card-footer border-top-0 text-center">
                        <p>Did you remembered your password?</p>
                        <p class="mb-0"><a href="{{route('login')}}">Try to Signin</a></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Row-->
    </div>
    <!-- End Page -->
@endsection

@section('extra-js')
@endsection
