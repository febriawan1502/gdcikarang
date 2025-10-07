<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Pojok IMS - Login</title>
    <link rel="icon" type="image/ico" href="{{ asset('assets/images/favicon.ico') }}" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- ============================================
    ================= Stylesheets ===================
    ============================================= -->
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/vendor/animsition/css/animsition.min.css') }}">

    <!-- project main css files -->
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <!--/ stylesheets -->

    <!-- ==========================================
    ================= Modernizr ===================
    =========================================== -->
    <script src="{{ asset('assets/js/vendor/modernizr/modernizr-2.8.3-respond-1.4.2.min.js') }}"></script>
    <!--/ modernizr -->
</head>
<body id="minovate" class="appWrapper">
    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <!-- ====================================================
    ================= Application Content ===================
    ===================================================== -->
    <div id="wrap" class="animsition">
        <div class="page page-core page-login">
            <div class="text-center"><h3 class="text-light text-white"><span class="text-lightred">POJOK</span> Inventory Management System</h3></div>

            <div class="container w-420 p-15 bg-white mt-40 text-center">
                <h2 class="text-light text-greensea">Log In</h2>

                <!-- Alert Messages -->
                @if(session('success'))
                    <div class="alert alert-success" role="alert">
                        <i class="fa fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger" role="alert">
                        <i class="fa fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <i class="fa fa-exclamation-triangle"></i>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" name="form" class="form-validation mt-20" novalidate="">
                    @csrf
                    
                    <div class="form-group">
                        <input type="email" 
                               class="form-control underline-input @error('email') is-invalid @enderror" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="Email"
                               required>
                    </div>

                    <div class="form-group">
                        <input type="password" 
                               class="form-control underline-input @error('password') is-invalid @enderror" 
                               name="password" 
                               placeholder="Password"
                               required>
                    </div>

                    <div class="form-group text-left mt-20">
                        <button type="submit" class="btn btn-greensea b-0 br-2 mr-5">Login</button>
                        <label class="checkbox checkbox-custom-alt checkbox-custom-sm inline-block">
                            <input type="checkbox" name="remember"><i></i> Remember me
                        </label>
                    </div>
                </form>

                <hr class="b-3x">

                <div class="bg-slategray lt wrap-reset mt-40">
                    <p class="m-0">
                        <strong>Demo Accounts:</strong><br>
                        Admin: admin@pojokims.com / admin123<br>
                        Petugas: petugas@pojokims.com / petugas123
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!--/ Application Content -->
    
    <!-- ============================================
    ============== Vendor JavaScripts ===============
    ============================================= -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="{{ asset('assets/js/vendor/jquery/jquery-1.11.2.min.js') }}"><\/script>')</script>

    <script src="{{ asset('assets/js/vendor/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/jRespond/jRespond.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/animsition/js/jquery.animsition.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/screenfull/screenfull.min.js') }}"></script>
    <!--/ vendor javascripts -->

    <!-- ============================================
    ============== Custom JavaScripts ===============
    ============================================= -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <!--/ custom javascripts -->

    <!-- ===============================================
    ============== Page Specific Scripts ===============
    ================================================ -->
    <script>
        $(window).load(function(){
            // Auto hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
            
            // Focus on first input
            $('input[name="email"]').focus();
        });
    </script>
    <!--/ Page Specific Scripts -->
</body>
</html>