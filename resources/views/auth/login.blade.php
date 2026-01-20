<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Pojok IMS - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Tailwind CDN for quick test, ganti dengan build Tailwind kalau di proyek beneran -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        .login-bg {
            background-image: url("{{ asset('assets/images/login.png') }}");
            background-size: cover;
            background-position: top center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translate3d(-50px, 0, 0);
            }
            to {
                opacity: 1;
                transform: none;
            }
        }
        
        .animate-fadeInLeft {
            animation: fadeInLeft 0.8s ease forwards;
        }
        
        .input-modern {
            background: transparent;
            border: none;
            border-bottom: 2px solid #e5e7eb;
            transition: all 0.3s ease;
        }
        
        .input-modern:focus {
            border-bottom-color: #16a34a;
            box-shadow: 0 4px 8px -4px rgba(22, 163, 74, 0.3);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -4px rgba(22, 163, 74, 0.5);
        }
    </style>
</head>
<body class="login-bg min-h-screen flex items-center font-sans">

    <!-- Container dengan form di kiri, dengan padding atas agar logo Danantara & PLN terlihat -->
    <div class="w-full min-h-screen flex items-center pt-16 pb-8">
        <div class="ml-8 md:ml-16 lg:ml-24 xl:ml-32">
            <div class="glass-card rounded-2xl shadow-2xl p-10 animate-fadeInLeft border border-white/20" style="width: 450px; max-width: 90vw;">
                <!-- Logo/Header -->
                <div class="text-center mb-8">
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">
                        <span class="text-red-500">POJOK</span> <span class="text-gray-700">IMS</span>
                    </h3>
                    <p class="text-gray-500 text-sm">Inventory Management System</p>
                </div>

                <h2 class="text-2xl font-semibold text-green-600 mb-6 text-center">Selamat Datang</h2>

                <!-- Alert Messages -->
                @if(session('success'))
                    <div class="bg-green-100/80 text-green-800 rounded-lg p-4 mb-4 flex items-center space-x-2 backdrop-blur">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100/80 text-red-800 rounded-lg p-4 mb-4 flex items-center space-x-2 backdrop-blur">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-100/80 text-red-800 rounded-lg p-4 mb-4 backdrop-blur">
                        <div class="flex items-center space-x-2 mb-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium">Terjadi kesalahan</span>
                        </div>
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6" novalidate>
                    @csrf
                    <div class="relative">
                        <input 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            placeholder="Email"
                            required
                            class="input-modern w-full py-3 text-gray-900 placeholder-gray-400 outline-none"
                        />
                    </div>

                    <div class="relative">
                        <input 
                            type="password" 
                            name="password" 
                            placeholder="Password" 
                            required
                            class="input-modern w-full py-3 text-gray-900 placeholder-gray-400 outline-none"
                        />
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <label class="inline-flex items-center space-x-2 text-gray-600 text-sm cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500" />
                            <span>Ingat saya</span>
                        </label>
                    </div>

                    <button type="submit" class="btn-login w-full text-white font-semibold py-3 px-6 rounded-lg">
                        Masuk
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-center text-gray-500 text-xs">
                        &copy; {{ date('Y') }} POJOK IMS - PT PLN (Persero)
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>
        if (!window.jQuery) {
            var script = document.createElement('script');
            script.src = "{{ asset('assets/js/vendor/jquery/jquery-1.11.2.min.js') }}";
            document.head.appendChild(script);
        }
    </script>

    <script src="{{ asset('assets/js/vendor/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
        $(window).on('load', function(){
            setTimeout(() => $('.alert').fadeOut('slow'), 5000);
            $('input[name="email"]').focus();
        });
    </script>
</body>
</html>
