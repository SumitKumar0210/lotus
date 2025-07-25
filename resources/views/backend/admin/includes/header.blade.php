<!-- Main Header-->
<div class="main-header side-header sticky">
    <div class="container-fluid">
        <div class="main-header-left">
            <a class="main-logo d-lg-none" href="{{route('admin.dashboard')}}">
                <img src="{{asset('backend/assets/img/logo.png')}}" class="header-brand-img desktop-logo" alt="logo">
            </a>
            <a class="main-header-menu-icon" href="" id="mainSidebarToggle"><span></span></a>
        </div>
        <div class="main-header-right">

            <div class="dropdown d-md-flex">
                <a class="nav-link icon full-screen-link">
                    <i class="fe fe-maximize fullscreen-button"></i>
                </a>
            </div>

            <div class="dropdown main-profile-menu">
                @if(!empty(Auth::user()->profile_photo_path))
                    <a class="main-img-user" href=""><img alt="avatar" src="{{asset('uploads/profile-thumbnail/'.Auth::user()->profile_photo_path)}}"></a>
                @else
                    <a class="main-img-user" href=""><img alt="avatar" src="{{asset('backend/assets/img/users/1.jpg')}}"></a>
                @endif

                    <div class="dropdown-menu">
                    <div class="header-navheading">
                        <h6 class="main-notification-title">{{Auth::user()->name}}</h6>
                        <p class="main-notification-text">Admin</p>
                    </div>
                    <a class="dropdown-item border-top" href="{{route('admin.profile')}}">
                        <i class="fe fe-user"></i> My Profile
                    </a>

                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fe fe-power"></i> Sign Out
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </a>
                </div>
            </div>




        </div>
    </div>
</div>
<!-- End Main Header-->
