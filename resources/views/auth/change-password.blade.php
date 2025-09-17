@extends('layouts.app')

@section('title', 'Ganti Password - ASI System')
@section('page-title', 'Ganti Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-key me-2"></i>
                    Ganti Password
                </h5>
            </div>
            
            <div class="card-body">
                <form method="POST" action="{{ route('auth.change-password') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label fw-semibold">
                            <i class="fas fa-lock me-1"></i>
                            Password Saat Ini
                        </label>
                        <input type="password" 
                               class="form-control @error('current_password') is-invalid @enderror" 
                               id="current_password" 
                               name="current_password" 
                               placeholder="Masukkan password saat ini"
                               required>
                        @error('current_password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password" class="form-label fw-semibold">
                            <i class="fas fa-key me-1"></i>
                            Password Baru
                        </label>
                        <input type="password" 
                               class="form-control @error('new_password') is-invalid @enderror" 
                               id="new_password" 
                               name="new_password" 
                               placeholder="Masukkan password baru (minimal 6 karakter)"
                               required>
                        @error('new_password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Password minimal 6 karakter
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="new_password_confirmation" class="form-label fw-semibold">
                            <i class="fas fa-check-double me-1"></i>
                            Konfirmasi Password Baru
                        </label>
                        <input type="password" 
                               class="form-control" 
                               id="new_password_confirmation" 
                               name="new_password_confirmation" 
                               placeholder="Ulangi password baru"
                               required>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            Simpan Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Tips Keamanan -->
        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="fas fa-shield-alt me-2"></i>
                    Tips Keamanan Password
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Minimal 8 karakter untuk keamanan yang lebih baik
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Jangan gunakan informasi pribadi yang mudah ditebak
                    </li>
                    <li class="mb-0">
                        <i class="fas fa-check text-success me-2"></i>
                        Ganti password secara berkala
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Password strength indicator
    document.getElementById('new_password').addEventListener('input', function() {
        const password = this.value;
        const strengthBar = document.getElementById('password-strength');
        
        if (password.length === 0) {
            return;
        }
        
        let strength = 0;
        
        // Length check
        if (password.length >= 8) strength++;
        
        // Lowercase check
        if (/[a-z]/.test(password)) strength++;
        
        // Uppercase check
        if (/[A-Z]/.test(password)) strength++;
        
        // Number check
        if (/[0-9]/.test(password)) strength++;
        
        // Special character check
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        // Update UI based on strength
        const strengthText = ['Sangat Lemah', 'Lemah', 'Sedang', 'Kuat', 'Sangat Kuat'];
        const strengthColor = ['danger', 'warning', 'info', 'success', 'success'];
        
        if (!strengthBar) {
            // Create strength indicator if not exists
            const indicator = document.createElement('div');
            indicator.id = 'password-strength';
            indicator.className = 'mt-2';
            this.parentNode.appendChild(indicator);
        }
        
        document.getElementById('password-strength').innerHTML = 
            `<div class="progress" style="height: 5px;">
                <div class="progress-bar bg-${strengthColor[strength-1]}" style="width: ${(strength/5)*100}%"></div>
            </div>
            <small class="text-${strengthColor[strength-1]}">Kekuatan: ${strengthText[strength-1]}</small>`;
    });
    
    // Confirm password validation
    document.getElementById('new_password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('new_password').value;
        const confirmPassword = this.value;
        
        if (confirmPassword.length === 0) {
            this.setCustomValidity('');
            return;
        }
        
        if (password !== confirmPassword) {
            this.setCustomValidity('Password tidak cocok');
        } else {
            this.setCustomValidity('');
        }
    });
</script>
@endpush