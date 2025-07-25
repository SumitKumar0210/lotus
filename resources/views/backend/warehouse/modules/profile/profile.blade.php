@extends('backend.warehouse.layouts.master')
@section('title')
    Profile
@endsection
@section('extra-css')
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">Profile</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Profile</li>
                </ol>
            </div>
        </div>
        <!-- End Page Header -->
        <!-- Row -->
        <div class="row">
            <div class="col-lg-4 col-md-12">
                <div class="card custom-card">
                    <div class="card-body text-center">
                        <div class="main-profile-overview widget-user-image text-center">
                            <div class="main-img-user">
                                @if(!empty(auth()->user()->profile_photo_path))
                                    <img alt="avatar"
                                         src="{{asset('uploads/profile-original/'.auth()->user()->profile_photo_path)}}">
                                @else
                                    <img alt="avatar" src="{{asset('uploads/profile-original/dummy-user.png')}}">
                                @endif
                            </div>
                        </div>
                        <div class="item-user pro-user">
                            <h4 class="pro-user-username text-dark mt-2 mb-0">{{auth()->user()->name}}</h4>
                            <p class="pro-user-desc text-muted mb-1">Admin</p>
                            <p class="user-info-rating">
                                <a href="#"><i class="fa fa-star text-warning"> </i></a>
                                <a href="#"><i class="fa fa-star text-warning"> </i></a>
                                <a href="#"><i class="fa fa-star text-warning"> </i></a>
                                <a href="#"><i class="fa fa-star text-warning"> </i></a>
                                <a href="#"><i class="far fa-star text-warning"> </i></a>
                            </p>
                            <div class="contact-info mb-3">
                                <a href="javascript:void(0);" class="contact-icon border text-primary"><i
                                        class="fab fa-facebook-f"></i></a>
                                <a href="javascript:void(0);" class="contact-icon border text-primary"><i
                                        class="fab fa-twitter"></i></a>
                                <a href="javascript:void(0);" class="contact-icon border text-primary"><i
                                        class="fab fa-google"></i></a>
                                <a href="javascript:void(0);" class="contact-icon border text-primary"><i
                                        class="fab fa-dribbble"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer p-0">
                        <div class="row text-center">
                            <div class="col-sm-6 border-right">
                                <div class="description-block">
                                    @php
                                        $users = \App\Models\User::get();
                                        $users_count = $users->count();
                                    @endphp
                                    <h5 class="description-header mb-1">
                                        {{$users_count}}
                                    </h5>
                                    <span class="text-muted">Total Users</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="description-block">
                                    @php
                                        $stock_purchased = \App\Models\Stock::where('type','WAREHOUSE STOCK')
                                         ->where('status','IN STOCK')
                                         ->where('reason','PURCHASE')
                                         ->get();
                                        $stock_purchased_count = $stock_purchased->sum('qty');
                                    @endphp
                                    <h5 class="description-header mb-1">
                                        {{$stock_purchased_count}}
                                    </h5>
                                    <span class="text-muted">Purchase Items</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-lg-8 col-md-12">
                <div class="card custom-card main-content-body-profile">
                    <nav class="nav main-nav-line">
                        <a class="nav-link active" data-toggle="tab" href="#tab1over">Overview</a>
                        <a class="nav-link" data-toggle="tab" href="#tab2rev">Password</a>
                    </nav>
                    <div class="card-body tab-content h-100">
                        <div class="tab-pane active" id="tab1over">
                            <div class="main-content-label tx-13 mg-b-20">
                                Personal Information
                            </div>
                            <div class="table-responsive ">
                                <table class="table row table-borderless">
                                    <tbody class="col-lg-12 col-xl-6 p-0">
                                    <tr>
                                        <td><strong>Name :</strong> {{auth()->user()->name}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Type :</strong> {{auth()->user()->type}}</td>
                                    </tr>
                                    </tbody>

                                    <tbody class="col-lg-12 col-xl-6 p-0">
                                    <tr>
                                        <td><strong>Email :</strong> {{auth()->user()->email}} </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab2rev">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="card custom-card">
                                        @include('backend.warehouse.messages.message-jquery-confirm')
                                        <div class="main-content-label tx-13 mg-b-20"> Password Update</div>
                                        <form method="POST" action="{{route('user-password.update')}}"
                                              enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            <div class="form-group">
                                                <input
                                                    class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                                                    id="current_password" name="current_password"
                                                    placeholder="Current Password"
                                                    type="text" required>
                                                @error('current_password', 'updatePassword')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>


                                            <div class="form-group">
                                                <input id="password"
                                                       class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                                                       name="password"
                                                       placeholder="New Password" type="text" required>
                                                @error('password', 'updatePassword')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <input
                                                    class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                                                    id="password_confirmation" name="password_confirmation"
                                                    placeholder="Conform New Password"
                                                    type="text" required>
                                                @error('password_confirmation', 'updatePassword')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <button class="btn ripple btn-primary" type="submit">
                                                    Update Password
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Row -->
    </div>
@endsection
@section('extra-js')
@endsection
