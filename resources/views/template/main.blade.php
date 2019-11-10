<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <!-- HTML5 Shim and Respond.js IE10 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 10]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!-- Meta -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="description" content="Gradient Able Bootstrap admin template made using Bootstrap 4 and it has huge amount of ready made feature, UI components, pages which completely fulfills any dashboard needs." />
        <meta name="keywords" content="Admin , Responsive, Landing, Bootstrap, App, Template, Mobile, iOS, Android, apple, creative app">
        <meta name="author" content="Phoenixcoded" />
        <title class="hidden-print">{{ __('TelcoGes') }}</title>
        <!-- Favicon icon -->
        <link rel="icon" href="{{ asset('mega-able-template/assets/images/favicon.ico') }}" type="image/x-icon">
        <!-- Google font-->
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,500" rel="stylesheet">
        <!-- Required Fremwork -->
        <link rel="stylesheet" type="text/css" href="{{ asset('mega-able-template/bower_components/bootstrap/css/bootstrap.min.css') }}">
        <!-- waves.css -->
        <link rel="stylesheet" href="{{ asset('mega-able-template/assets/pages/waves/css/waves.min.css') }}" type="text/css" media="all">
        <!-- themify-icons line icon -->
        <link rel="stylesheet" type="text/css" href="{{ asset('mega-able-template/assets/icon/themify-icons/themify-icons.css') }}">
        <!-- ico font -->
        <link rel="stylesheet" type="text/css" href="{{ asset('mega-able-template/assets/icon/icofont/css/icofont.css') }}">
        <!-- Font Awesome -->
        <link rel="stylesheet" type="text/css" href="{{ asset('mega-able-template/assets/icon/font-awesome/css/font-awesome.min.css') }}">
        <!-- Syntax highlighter Prism css -->
        <link rel="stylesheet" type="text/css" href="{{ asset('mega-able-template/assets/pages/prism/prism.css') }}">
        <!-- Style.css -->
        <link rel="stylesheet" type="text/css" href="{{ asset('mega-able-template/assets/css/style.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('mega-able-template/assets/css/jquery.mCustomScrollbar.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('mega-able-template/assets/css/pcoded-horizontal.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/printinvoice.css') }}" media="print" />
        @section('inlinecss')
        @show
    </head>
    <!-- Menu horizontal fixed layout -->

    <body>
        @include('template.preloader')

        @auth()
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @endauth
        
        <div id="pcoded" class="pcoded">
            <div class="pcoded-container">
                <nav class="navbar header-navbar pcoded-header hidden-print">
                    <div class="navbar-wrapper">
                        @include('template.navbar')
                    </div>
                </nav>
                
                {{-- @include('template.sidebarchat') --}}
                {{-- @include('template.sidebarchat_inner') --}}
                
                <div class="pcoded-main-container print-margin">
                    <nav class="pcoded-navbar hidden-print">
                        <div class="pcoded-inner-navbar">
                            @include('template.navbarmenu')
                        </div>
                    </nav>

                    <div class="pcoded-wrapper print-margin">
                        <div class="pcoded-content">
                            <div class="pcoded-inner-content"> 
                                <!-- Main-body start -->
                                <div class="main-body">
                                    <div class="page-wrapper">
                                        <!-- Page body start -->
                                        <div class="page-body print-margin">
                                            <div class="row m-t-40">
                                                <div class="col-lg-12">
                                                    @if( Session::has('flash_message') )
                                                      <div class="alert {{ Session::get('flash_type') }} alert-dismissible fade show" role="alert">
                                                        {{ Session::get('flash_message') }}
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                          <span aria-hidden="true">&times;</span>
                                                        </button>
                                                      </div>
                                                    @endif
                                                    <div class="card print-margin">
                                                        <div class="card-header hidden-print">
                                                            <h5>{{ $titlePage ?? '' }}</h5>
                                                        </div>
                                                        <div class="card-block print-margin">
                                                            @yield('content')
                                                        </div>
                                                    </div>
                                                    <!-- Default card end -->
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Page body end -->
                                    </div>
                                </div>
                                <!-- Main-body end -->
                                <div id="styleSelector">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Warning Section Starts -->
        <!-- Older IE warning message -->

        <!--[if lt IE 10]>
        <div class="ie-warning">
            <h1>Warning!!</h1>
            <p>You are using an outdated version of Internet Explorer, please upgrade <br/>to any of the following web browsers
                to access this website.</p>
            <div class="iew-container">
                <ul class="iew-download">
                    <li>
                        <a href="http://www.google.com/chrome/">
                            <img src="{{ asset('mega-able-template/assets/images/browser/chrome.png') }}" alt="Chrome">
                            <div>Chrome</div>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.mozilla.org/en-US/firefox/new/">
                            <img src="{{ asset('mega-able-template/assets/images/browser/firefox.png') }}" alt="Firefox">
                            <div>Firefox</div>
                        </a>
                    </li>
                    <li>
                        <a href="http://www.opera.com">
                            <img src="{{ asset('mega-able-template/assets/images/browser/opera.png') }}" alt="Opera">
                            <div>Opera</div>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.apple.com/safari/">
                            <img src="{{ asset('mega-able-template/assets/images/browser/safari.png') }}" alt="Safari">
                            <div>Safari</div>
                        </a>
                    </li>
                    <li>
                        <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                            <img src="{{ asset('mega-able-template/assets/images/browser/ie.png') }}" alt="">
                            <div>IE (9 & above)</div>
                        </a>
                    </li>
                </ul>
            </div>
            <p>Sorry for the inconvenience!</p>
        </div>
        <![endif]-->

        <!-- Warning Section Ends -->
        <!-- Required Jquery -->
        <script type="text/javascript" src="{{ asset('mega-able-template/bower_components/jquery/js/jquery.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('mega-able-template/bower_components/jquery-ui/js/jquery-ui.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('mega-able-template/bower_components/popper.js/js/popper.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('mega-able-template/bower_components/bootstrap/js/bootstrap.min.js') }}"></script>
        <!-- waves js -->
        <script src="{{ asset('mega-able-template/assets/pages/waves/js/waves.min.js') }}"></script>
        <!-- jquery slimscroll js -->
        <script type="text/javascript" src="{{ asset('mega-able-template/bower_components/jquery-slimscroll/js/jquery.slimscroll.js') }}"></script>
        <!-- modernizr js -->
        <script type="text/javascript" src="{{ asset('mega-able-template/bower_components/modernizr/js/modernizr.js') }}"></script>
        <script type="text/javascript" src="{{ asset('mega-able-template/bower_components/modernizr/js/css-scrollbars.js') }}"></script>

        <!-- Syntax highlighter prism js -->
        <!-- <script type="text/javascript" src="{{ asset('mega-able-template/assets/pages/prism/Custom-prism.js') }}"></script> -->
        <!-- i18next.min.js -->
        <script type="text/javascript" src="{{ asset('mega-able-template/bower_components/i18next/js/i18next.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('mega-able-template/bower_components/i18next-xhr-backend/js/i18nextXHRBackend.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('mega-able-template/bower_components/i18next-browser-languagedetector/js/i18nextBrowserLanguageDetector.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('mega-able-template/bower_components/jquery-i18next/js/jquery-i18next.min.js') }}"></script>
        <!-- Custom js -->

        <script src="{{ asset('js/custom.js') }}"></script>
        <script src="{{ asset('mega-able-template/assets/js/pcoded.min.js') }}"></script>
        <script src="{{ asset('mega-able-template/assets/js/vertical/menu/menu-hori-fixed.js') }}"></script>
        <script src="{{ asset('mega-able-template/assets/js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('mega-able-template/assets/js/script.js') }}"></script>
        <script>
            $(document).ready(function(){
                $('#styleSelector').css('display', 'none');
            });
        </script>
        @section('inlinejs')
        @show
    </body>
</html>