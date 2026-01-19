<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="id"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>@yield('title', 'POJOK INVENTORY')</title>
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
    <link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/page-transitions.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/vendor/datatables/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/vendor/datatables/datatables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/vendor/chosen/chosen.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/vendor/summernote/summernote.css') }}">

    <!-- project main css files -->
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <!--/ stylesheets -->

    <!-- ==========================================
    ================= Modernizr ===================
    =========================================== -->
    <script src="{{ asset('assets/js/vendor/modernizr/modernizr-2.8.3-respond-1.4.2.min.js') }}"></script>
    <!--/ modernizr -->

    <!-- Custom CSS -->
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 2px 0;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .navbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.07);
            transition: transform 0.2s ease;
        }
        .card:hover {
            transform: translateY(-2px);
        }
        .btn {
            border-radius: 8px;
            font-weight: 500;
        }
        .table {
            border-radius: 8px;
            overflow: hidden;
        }
        .badge {
            font-size: 0.75em;
            padding: 0.5em 0.75em;
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .stats-card .card-body {
            padding: 1.5rem;
        }
        .stats-number {
            font-size: 2rem;
            font-weight: bold;
        }
        .page-content {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }
        .page-header {
            margin-bottom: 10px !important;
            padding-bottom: 5px !important;
            overflow: hidden;
            min-height: auto;
            border-bottom: none;
        }
        .page-title {
            margin: 5px 0;
            line-height: 1.2;
            font-weight: bold;
        }
        /* Override generic content padding */
        #content {
            padding-top: 10px !important;
        }
        
        /* Sidebar Collapse Styles */
        #sidebar {
            transition: width 0.3s ease;
        }
        
        body.sidebar-collapsed #sidebar {
            width: 70px !important;
        }
        
        /* Hide menu text when collapsed */
        body.sidebar-collapsed #sidebar .nav-stacked li a span {
            display: none !important;
        }
        
        body.sidebar-collapsed #sidebar .nav-stacked li a {
            text-align: center;
            padding: 15px 0 !important;
        }
        
        body.sidebar-collapsed #sidebar .nav-stacked li a i {
            margin-right: 0 !important;
            font-size: 20px;
        }
        
        /* Hide panel title text */
        body.sidebar-collapsed #sidebar .panel-title a {
            font-size: 0 !important;
        }
        
        body.sidebar-collapsed #sidebar .panel-title a i {
            font-size: 16px !important;
        }
        
        body.sidebar-collapsed #sidebar .panel-heading {
            padding: 10px 5px;
        }
        
        /* Adjust content margin when sidebar is collapsed */
        body.sidebar-collapsed #controls {
            margin-left: 70px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- =================================================
    ================= Application Content ===================
    ================================================== -->
    <div class="appWrapper" id="wrapper">
        <!-- =========================================
        ================= HEADER Content ===================
        ========================================== -->
        <section id="header">
            <header class="clearfix">
                <!-- Branding -->
                <div class="branding">
                    <a class="brand" href="{{ route('dashboard') }}">
                        <span><strong>POJOK</strong> IMS</span>
                    </a>
                    <a role="button" tabindex="0" class="offcanvas-toggle visible-xs-inline"><i class="fa fa-bars"></i></a>
                </div>
                <!-- Branding end -->

                <!-- Left-side navigation -->
                <ul class="nav-left pull-left list-unstyled list-inline">
                    <li class="sidebar-collapse divided-right">
                        <a role="button" tabindex="0" class="collapse-sidebar">
                            <i class="fa fa-outdent"></i>
                        </a>
                    </li>
                </ul>
                <!-- Left-side navigation end -->

                <!-- Right-side navigation -->
                <ul class="nav-right pull-right list-inline">
                    @auth
                    <li class="dropdown users">
                        <a href class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-user"></i>
                            {{ auth()->user()->nama }}
                        </a>
                        <div class="dropdown-menu pull-right with-arrow panel panel-default" role="menu">
                            <div class="panel-heading">
                                Selamat datang, <strong>{{ auth()->user()->nama }}</strong>
                            </div>
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <a href="{{ route('auth.change-password') }}" class="media">
                                        <div class="media-body">
                                            <i class="fa fa-key" style="margin-left: 8px;"></i> Ganti Password
                                        </div>
                                    </a>
                                </li>
                                    <a href="#" id="logout-link" class="media">
                                        <div class="media-body">
                                            <i class="fa fa-sign-out" style="margin-left: 8px;"></i> Logout
                                        </div>
                                    </a>
                            </ul>
                        </div>
                    </li>
                    @endauth
                </ul>
                <!-- Right-side navigation end -->
            </header>
        </section>
        <!--/ HEADER Content -->

        <!-- =================================================
        ================= CONTROLS Content ===================
        ================================================== -->
        <div id="controls">
            <!-- ================================================
            ================= SIDEBAR Content ===================
            ================================================= -->
            <aside id="sidebar">
                <div id="sidebar-wrap">
                    <div class="panel-group slim-scroll" role="tablist">
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#sidebarNav">
                                        Navigation <i class="fa fa-angle-up"></i>
                                    </a>
                                </h4>
                            </div>
                            <div id="sidebarNav" class="panel-collapse collapse in" role="tabpanel">
                                <div class="panel-body">
<ul class="nav nav-pills nav-stacked">

    {{-- Dashboard (TIDAK untuk security) --}}
    @if(auth()->user()->role !== 'security')
    <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}">
            <i class="fa fa-dashboard"></i>
            <span>Dashboard</span>
        </a>
    </li>
    @endif
    
    {{-- Data Material (Semua User kecuali Security) --}}
    @if(auth()->user()->role !== 'security')
    <li class="{{ request()->routeIs('material.index') ? 'active' : '' }}">
        <a href="{{ route('material.index') }}">
            <i class="fa fa-cubes"></i>
            <span>Data Material</span>
        </a>
    </li>
    @endif

    {{-- Material Masuk (TIDAK untuk security) --}}
    @if(auth()->user()->role !== 'security' && 
    (
        auth()->user()->role === 'admin' ||
        auth()->user()->role === 'guest' ||
        auth()->user()->role === 'petugas'
    ))

    <li class="{{ request()->routeIs('material-masuk.*') ? 'active' : '' }}">
        <a href="{{ route('material-masuk.index') }}">
            <i class="fa fa-arrow-down"></i>
            <span>Material Masuk</span>
        </a>
    </li>
    @endif

    {{-- ✅ Surat Jalan (BOLEH untuk security) --}}
    @if(in_array(auth()->user()->role, ['admin', 'guest', 'petugas', 'security']))
    <li class="{{ request()->routeIs('surat-jalan.*') ? 'active' : '' }}">
        <a href="{{ route('surat-jalan.index') }}">
            <i class="fa fa-truck"></i>
            <span>Surat Jalan</span>
        </a>
    </li>
    @endif

    {{-- Menu lain (TIDAK untuk security) --}}
    @if(auth()->user()->role !== 'security')
    {{-- Masa Peminjaman --}}
        <li class="{{ request()->is('surat-jalan/peminjaman/masa') ? 'active' : '' }}">
            <a href="{{ route('surat.masa', ['jenis' => 'Peminjaman']) }}">
                <i class="fa fa-clock-o"></i>
                <span>Masa Pengeluaran</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('material.history') ? 'active' : '' }}">
            <a href="{{ route('material.history') }}">
                <i class="fa fa-history"></i>
                <span>Material Histories</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('material.pemeriksaanFisik') ? 'active' : '' }}">
            <a href="{{ route('material.pemeriksaanFisik') }}">
                <i class="fa fa-file-text-o"></i>
                <span>Pemeriksaan Fisik</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('berita-acara.*') ? 'active' : '' }}">
            <a href="{{ route('berita-acara.index') }}">
                <i class="fa fa-file-text"></i>
                <span>Berita Acara</span>
            </a>
        </li>
    @endif
    
    {{-- ✅ Cek Kesesuaian SAP (Admin Only) --}}
    @if(auth()->user()->role === 'admin')
    <li class="{{ request()->routeIs('sap-check.*') ? 'active' : '' }}">
        <a href="{{ route('sap-check.index') }}">
            <i class="fa fa-check-square-o"></i>
            <span>Cek Kesesuaian SAP</span>
        </a>
    </li>
    @endif

    {{-- ⚙️ Settings (Admin Only) --}}
    @if(auth()->user()->role === 'admin')
    <li class="{{ request()->routeIs('settings.*') ? 'active' : '' }}">
        <a href="{{ route('settings.index') }}">
            <i class="fa fa-cogs"></i>
            <span>Pengaturan</span>
        </a>
    </li>
    @endif

</ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
            <!--/ SIDEBAR Content -->

            <!-- ================================================
            ================= CONTENT ===================
            ================================================= -->
            <section id="content">
                <div class="page-header">
                    <div class="pull-left">
                        <h1 class="page-title">@yield('title', 'Dashboard')</h1>
                    </div>
                </div>

                <div class="page-content fade-in-up">
                    <!-- Alert Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fa fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa fa-exclamation-triangle me-2"></i>
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @yield('content')
                </div>
            </section>
            <!--/ CONTENT -->
        </div>
        <!--/ CONTROLS Content -->
    </div>
    <!--/ Application Content -->

<!-- ✅ Logout Form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<!-- ==========================================
================= JavaScripts ===================
=========================================== -->
<!-- vendor js files -->
<script src="{{ asset('assets/js/vendor/jquery/jquery-1.11.2.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/datatables/extensions/dataTables.bootstrap.js') }}"></script>
<script src="{{ asset('assets/js/vendor/chosen/chosen.jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/summernote/summernote.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/jRespond/jRespond.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/slimscroll/jquery.slimscroll.min.js') }}"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- project main js files -->
<script src="{{ asset('vendor/animsition/js/animsition.min.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>

<!-- ✅ Perbaikan: Logout SweetAlert + CSRF aman -->
<script>
$(document).ready(function () {
    // Tetap setup CSRF untuk Ajax request lainnya
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    });

    // ✅ Logout dengan konfirmasi SweetAlert2 (tanpa trigger error 419)
    const logoutLink = document.getElementById('logout-link');
    const logoutForm = document.getElementById('logout-form');

    if (logoutLink && logoutForm) {
        logoutLink.addEventListener('click', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Logout?',
                text: 'Apakah Anda yakin ingin keluar?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    logoutForm.submit(); // form POST kirim @csrf -> aman dari 419
                }
            });
        });
    }
});
</script>

<script>
// Fallback untuk animsition yang hilang
$(document).ready(function() {
    // Jika animsition tidak tersedia, buat dummy function
    if (typeof $.fn.animsition === 'undefined') {
        $.fn.animsition = function() { return this; };
    }
    
    // Pastikan sidebar collapse berfungsi
    $('.collapse-sidebar').off('click').on('click', function(e) {
        e.preventDefault();
        var $icon = $(this).find('i');
        
        if ($('body').hasClass('sidebar-collapsed')) {
            $('body').removeClass('sidebar-collapsed');
            $icon.removeClass('fa-indent').addClass('fa-outdent');
        } else {
            $('body').addClass('sidebar-collapsed');
            $icon.removeClass('fa-outdent').addClass('fa-indent');
        }
    });
});
</script>

@stack('scripts')
</body>
</html>
