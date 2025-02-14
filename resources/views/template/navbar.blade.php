<div class="navbar-logo hidden-print">
    <a class="mobile-menu waves-effect waves-light" id="mobile-collapse" href="#!">
        <i class="ti-menu"></i>
    </a>
    <div class="mobile-search waves-effect waves-light">
        <div class="header-search">
            <div class="main-search morphsearch-search">
                <div class="input-group">
                    <span class="input-group-prepend search-close"><i class="ti-close input-group-text"></i></span>
                    <input type="text" class="form-control" placeholder="Enter Keyword">
                    <span class="input-group-append search-btn"><i class="ti-search input-group-text"></i></span>
                </div>
            </div>
        </div>
    </div>
    <a href="#">
        <img class="img-fluid" src="{{ asset('images/logo.png') }}" alt="Theme-Logo" />
    </a>
    <a class="mobile-options waves-effect waves-light">
        <i class="ti-more"></i>
    </a>
</div>
<div class="navbar-container container-fluid hidden-print">
    <ul class="nav-left">
        <li>
            <div class="sidebar_toggle"><a href="javascript:void(0)"><i class="ti-menu"></i></a></div>
        </li>
    {{--
        <li class="header-search">
            <div class="main-search morphsearch-search">
                <div class="input-group">
                    <span class="input-group-prepend search-close"><i class="ti-close input-group-text"></i></span>
                    <input type="text" class="form-control" placeholder="Enter Keyword">
                    <span class="input-group-append search-btn"><i class="ti-search input-group-text"></i></span>
                </div>
            </div>
        </li>
    --}}
        <li>
            <a href="#!" onclick="javascript:toggleFullScreen()" class="waves-effect waves-light">
                <i class="ti-fullscreen"></i>
            </a>
        </li>
    </ul>
    <ul class="nav-right">
    {{--
        <!-- NOTIFICATION ICON -->
        <li class="header-notification">
            <a href="#!" class="waves-effect waves-light">
                <i class="ti-bell"></i>
                <span class="badge bg-c-red"></span>
            </a>
            <ul class="show-notification">
                <li>
                    <h6>Notifications</h6>
                    <label class="label label-danger">New</label>
                </li>
                <li class="waves-effect waves-light">
                    <div class="media">
                        <img class="d-flex align-self-center img-radius" src="{{ asset('mega-able-template/assets/images/avatar-2.jpg') }}" alt="Generic placeholder image">
                        <div class="media-body">
                            <h5 class="notification-user">John Doe</h5>
                            <p class="notification-msg">Lorem ipsum dolor sit amet, consectetuer elit.</p>
                            <span class="notification-time">30 minutes ago</span>
                        </div>
                    </div>
                </li>
                <li class="waves-effect waves-light">
                    <div class="media">
                        <img class="d-flex align-self-center img-radius" src="{{ asset('mega-able-template/assets/images/avatar-4.jpg') }}" alt="Generic placeholder image">
                        <div class="media-body">
                            <h5 class="notification-user">Joseph William</h5>
                            <p class="notification-msg">Lorem ipsum dolor sit amet, consectetuer elit.</p>
                            <span class="notification-time">30 minutes ago</span>
                        </div>
                    </div>
                </li>
                <li class="waves-effect waves-light">
                    <div class="media">
                        <img class="d-flex align-self-center img-radius" src="{{ asset('mega-able-template/assets/images/avatar-3.jpg') }}" alt="Generic placeholder image">
                        <div class="media-body">
                            <h5 class="notification-user">Sara Soudein</h5>
                            <p class="notification-msg">Lorem ipsum dolor sit amet, consectetuer elit.</p>
                            <span class="notification-time">30 minutes ago</span>
                        </div>
                    </div>
                </li>
            </ul>
        </li>
    --}}
    {{--
        <!-- MESSAGES ICON -->
        <li class="">
            <a href="#!" class="displayChatbox waves-effect waves-light">
                <i class="ti-comments"></i>
                <span class="badge bg-c-green"></span>
            </a>
        </li>
    --}}
        <li class="user-profile header-notification">
            <a href="#!" class="waves-effect waves-light">
                <img src="{{ asset('mega-able-template/assets/images/avatar-blank.jpg') }}" class="img-radius" alt="User-Profile-Image">
                <span> {{ auth()->user()->name ?? '' }} </span>
                <i class="ti-angle-down"></i>
            </a>
            <ul class="show-notification profile-notification">
                @if ( auth()->user()->hasAnyRole(['admin', 'superadmin']) )
                <li class="waves-effect waves-light">
                    <a href="{{ route('organizations.show', ['id' => auth()->user()->getOrganization()->id]) }}">
                        <i class="ti-settings"></i> {{ __('Perfil empresa') }}
                    </a>
                </li>
                @endif
                <li class="waves-effect waves-light">
                    <a href="{{ route('profile.edit') }}">
                        <i class="ti-id-badge"></i> {{ __('Perfil usuario') }}
                    </a>
                </li>
                @if ( auth()->user()->hasAnyRole(['admin', 'superadmin']) )
                <li class="waves-effect waves-light">
                    <a href="{{ route('user.index') }}">
                        <i class="ti-user"></i> {{ __('Mis Usuarios') }}
                    </a>
                </li>
                @endif
                <li class="waves-effect waves-light">
                    <a href="{{ route('invoice.series.index') }}">
                        <i class="ti-pencil-alt"></i> {{ __('Series facturación') }}
                    </a>
                </li>
                <li class="waves-effect waves-light">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        <i class="ti-layout-sidebar-left"></i> {{ __('Salir') }}
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</div>