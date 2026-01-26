<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'Si Eneng')</title>
    <link rel="icon" type="image/ico" href="{{ asset('assets/images/favicon.ico') }}" />
    <meta name="description" content="POJOK Inventory Management System">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts - Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <!-- Tailwind CSS (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire Styles -->
    @livewireStyles

    @stack('styles')
</head>
<body class="antialiased">
    <div class="app-wrapper">
        <!-- ====================================
             SIDEBAR
        ===================================== -->
        <aside class="sidebar">
            <div class="sidebar-inner">
                <!-- Logo -->
                <div class="sidebar-logo">
                    <div class="sidebar-logo-icon">
                        <i class="fas fa-warehouse text-teal-500"></i>
                    </div>
                    <span class="sidebar-logo-text">Si Eneng</span>
                </div>

                <!-- Navigation -->
                <nav class="sidebar-nav">
                    @php
                        $showInspectionGroup = in_array(auth()->user()->role, ['admin', 'petugas']);
                        $inspectionGroupOpen = request()->routeIs('material.pemeriksaanFisik')
                            || request()->routeIs('berita-acara.*')
                            || request()->routeIs('sap-check.*');
                    @endphp
                    {{-- Dashboard --}}
                    @if(auth()->user()->role !== 'security')
                    <div class="nav-item">
                        <a href="{{ route('dashboard') }}" wire:navigate class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <span class="nav-icon">
                                <i class="fas fa-home"></i>
                            </span>
                            <span>Dashboard</span>
                        </a>
                    </div>
                    @endif

                    {{-- Data Material --}}
                    @if(auth()->user()->role !== 'security')
                    <div class="nav-item">
                        <a href="{{ route('material.index') }}" wire:navigate class="nav-link {{ request()->routeIs('material.index') ? 'active' : '' }}">
                            <span class="nav-icon">
                                <i class="fas fa-cubes"></i>
                            </span>
                            <span>Data Material</span>
                        </a>
                    </div>
                    @endif

                    {{-- Material Masuk --}}
                    @if(in_array(auth()->user()->role, ['admin', 'petugas', 'guest']))
                    <div class="nav-item">
                        <a href="{{ route('material-masuk.index') }}" wire:navigate class="nav-link {{ request()->routeIs('material-masuk.*') ? 'active' : '' }}">
                            <span class="nav-icon">
                                <i class="fas fa-arrow-down"></i>
                            </span>
                            <span>Material Masuk</span>
                        </a>
                    </div>
                    @endif

                    {{-- Surat Jalan --}}
                    @if(in_array(auth()->user()->role, ['admin', 'guest', 'petugas', 'security']))
                    <div class="nav-item">
                        <a href="{{ route('surat-jalan.index') }}" wire:navigate class="nav-link {{ request()->routeIs('surat-jalan.*') ? 'active' : '' }}">
                            <span class="nav-icon">
                                <i class="fas fa-truck"></i>
                            </span>
                            <span>Surat Jalan</span>
                        </a>
                    </div>
                    @endif

                    {{-- Additional Menu (Admin & Petugas) --}}
                    @if(in_array(auth()->user()->role, ['admin', 'petugas']))
                    <div class="nav-item">
                        <a href="{{ route('surat.masa', ['jenis' => 'Peminjaman']) }}" wire:navigate class="nav-link {{ request()->is('surat-jalan/peminjaman/masa') ? 'active' : '' }}">
                            <span class="nav-icon">
                                <i class="fas fa-clock"></i>
                            </span>
                            <span>Garansi &amp; Perbaikan</span>
                        </a>
                    </div>

                    <div class="nav-item">
                        <a href="{{ route('material.history') }}" wire:navigate class="nav-link {{ request()->routeIs('material.history') ? 'active' : '' }}">
                            <span class="nav-icon">
                                <i class="fas fa-history"></i>
                            </span>
                            <span>Material Histories</span>
                        </a>
                    </div>

                    @if($showInspectionGroup)
                    <div class="nav-item nav-group {{ $inspectionGroupOpen ? 'open' : '' }}">
                        <button type="button"
                            class="nav-link nav-group-toggle {{ $inspectionGroupOpen ? 'active' : '' }}"
                            aria-expanded="{{ $inspectionGroupOpen ? 'true' : 'false' }}">
                            <span class="nav-icon">
                                <i class="fas fa-clipboard-check"></i>
                            </span>
                            <span>Pemeriksaan Fisik</span>
                            <span class="nav-group-caret">
                                <i class="fas fa-chevron-down"></i>
                            </span>
                        </button>
                        <div class="nav-submenu">
                            @if(auth()->user()->role === 'admin')
                            <a href="{{ route('sap-check.index') }}" wire:navigate class="nav-link nav-sublink {{ request()->routeIs('sap-check.*') ? 'active' : '' }}">
                                <span class="nav-icon">
                                    <i class="fas fa-check-double"></i>
                                </span>
                                <span>Cek Kesesuaian SAP</span>
                            </a>
                            @endif
                            <a href="{{ route('material.pemeriksaanFisik') }}" wire:navigate class="nav-link nav-sublink {{ request()->routeIs('material.pemeriksaanFisik') ? 'active' : '' }}">
                                <span class="nav-icon">
                                    <i class="fas fa-clipboard-check"></i>
                                </span>
                                <span>Pemeriksaan Fisik</span>
                            </a>
                            <a href="{{ route('berita-acara.index') }}" wire:navigate class="nav-link nav-sublink {{ request()->routeIs('berita-acara.*') ? 'active' : '' }}">
                                <span class="nav-icon">
                                    <i class="fas fa-file-alt"></i>
                                </span>
                                <span>Berita Acara</span>
                            </a>
                        </div>
                    </div>
                    @endif
                    @endif

                    {{-- Admin Only --}}
                    @if(auth()->user()->role === 'admin')

                    @endif
                </nav>
                <div class="sidebar-footer">
                    <button type="button" id="sidebarToggle" class="sidebar-toggle" aria-label="Toggle sidebar">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                </div>
            </div>
        </aside>
        <!--/ SIDEBAR -->

        <!-- ====================================
             MAIN CONTENT
        ===================================== -->
        <main class="main-content">
            <!-- Header -->
            <header class="page-header">
                <div class="header-actions ml-auto">
                    @auth
                    <div class="user-dropdown" id="userDropdown">
                        <i class="fas fa-user"></i>
                        <span>{{ auth()->user()->nama }}</span>
                        <i class="fas fa-cog ml-2"></i>
                    </div>
                    @endauth
                </div>
            </header>
            <!--/ Header -->

            <!-- Alert Messages -->
            @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
            @endif
            
            @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
            @endif
            
            @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Terjadi kesalahan:</strong>
                <ul class="mt-2 ml-4 list-disc">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Page Content -->
            <div class="page-content">
                @yield('content')
            </div>
        </main>
        <!--/ MAIN CONTENT -->
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- User Dropdown Menu -->
    <div id="userDropdownMenu" class="hidden fixed bg-white rounded-xl shadow-lg border border-gray-100 py-2 w-48 z-50">
        <a href="{{ route('auth.change-password') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
            <i class="fas fa-key mr-2"></i> Ganti Password
        </a>
        @if(auth()->user()->role === 'admin' && auth()->user()->unit && auth()->user()->unit->is_induk)
        <a href="{{ route('settings.index') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
            <i class="fas fa-cog mr-2"></i> Pengaturan
        </a>
        @endif
        <a href="#" id="logout-link" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Livewire Scripts -->
    @livewireScripts

    <script>
    function initAppScripts() {
        const appWrapper = document.querySelector('.app-wrapper');

        if (appWrapper) {
            const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
            appWrapper.classList.toggle('sidebar-collapsed', isCollapsed);
        }

        // CSRF Token Setup (JQuery)
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        // User Dropdown Toggle
        const userDropdown = document.getElementById('userDropdown');
        const userDropdownMenu = document.getElementById('userDropdownMenu');

        if (userDropdown && userDropdownMenu) {
            // Remove any existing click listeners to prevent duplicates (though element replacement usually handles this, onclick is safer)
            userDropdown.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Toggle hidden class
                if (userDropdownMenu.classList.contains('hidden')) {
                    // Show menu
                    userDropdownMenu.classList.remove('hidden');
                    
                    // Position it
                    const rect = userDropdown.getBoundingClientRect();
                    userDropdownMenu.style.top = (rect.bottom + 8) + 'px';
                    userDropdownMenu.style.right = (window.innerWidth - rect.right) + 'px';
                } else {
                    // Hide menu
                    userDropdownMenu.classList.add('hidden');
                }
            };

            // Close when clicking anywhere else
            document.addEventListener('click', function(e) {
                if (!userDropdown.contains(e.target) && !userDropdownMenu.contains(e.target)) {
                    userDropdownMenu.classList.add('hidden');
                }
            });
        }

        // Logout Handler
        const logoutLink = document.getElementById('logout-link');
        const logoutForm = document.getElementById('logout-form');

        if (logoutLink && logoutForm) {
            logoutLink.onclick = function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Logout?',
                    text: 'Apakah Anda yakin ingin keluar?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#4FD1C5',
                    cancelButtonColor: '#718096',
                    confirmButtonText: 'Ya, Logout',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        logoutForm.submit();
                    }
                });
            };
        }

        // Mobile Sidebar Toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.querySelector('.sidebar');

        if (mobileMenuBtn && sidebar) {
            mobileMenuBtn.onclick = function() {
                sidebar.classList.toggle('open');
            };
        }

        // Sidebar collapse toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        if (sidebarToggle && appWrapper) {
            sidebarToggle.onclick = function() {
                appWrapper.classList.toggle('sidebar-collapsed');
                localStorage.setItem('sidebar-collapsed', appWrapper.classList.contains('sidebar-collapsed'));
            };
        }

        // Sidebar group toggle
        document.querySelectorAll('.nav-group-toggle').forEach((toggle) => {
            if (toggle.dataset.bound === 'true') {
                return;
            }

            toggle.dataset.bound = 'true';
            toggle.onclick = function() {
                const group = toggle.closest('.nav-group');
                if (!group) {
                    return;
                }

                group.classList.toggle('open');
                toggle.setAttribute('aria-expanded', group.classList.contains('open') ? 'true' : 'false');
            };
        });
    }

    // Initialize on first load
    document.addEventListener('DOMContentLoaded', initAppScripts);
    
    // Initialize after every Livewire navigation
    document.addEventListener('livewire:navigated', function() {
        console.log('Livewire Navigated - Re-initializing Scripts');
        initAppScripts();
    });
    </script>

    @stack('scripts')
</body>
</html>
