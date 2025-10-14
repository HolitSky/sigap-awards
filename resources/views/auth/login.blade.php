@extends('auth.layout-app')

@section('content')
<style>
    .login-wrapper {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #1a1a1a 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .glassmorphism-card {
        background: rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
    }

    .glassmorphism-header {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 16px 16px 0 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .form-control {
        background: rgba(255, 255, 255, 0.1) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        color: white !important;
        -webkit-text-fill-color: white !important;
    }

    .form-control:focus {
        background: rgba(255, 255, 255, 0.15) !important;
        border-color: rgba(255, 255, 255, 0.5) !important;
        color: white !important;
        -webkit-text-fill-color: white !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25) !important;
    }

    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.7) !important;
    }

    /* Ensure all input types have same styling */
    input.form-control[type="email"],
    input.form-control[type="password"],
    input.form-control[type="text"],
    input.form-control[type="number"] {
        background: rgba(255, 255, 255, 0.1) !important;
        color: white !important;
        -webkit-text-fill-color: white !important;
    }

    /* Autofill styling - dark text on light background */
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    input:-webkit-autofill:active {
        -webkit-text-fill-color: #2d3748 !important;
        -webkit-box-shadow: 0 0 0 30px #e2e8f0 inset !important;
        box-shadow: 0 0 0 30px #e2e8f0 inset !important;
        transition: background-color 5000s ease-in-out 0s;
    }

    .form-label {
        color: rgba(255, 255, 255, 0.9);
        font-weight: 500;
    }

    .btn-light {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
    }

    .btn-light:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
    }

    .loading-spinner {
        width: 16px;
        height: 16px;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-right: 8px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .copyright-text {
        color: rgba(255, 255, 255, 0.8);
    }
</style>

<div class="login-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card overflow-hidden glassmorphism-card">
                    <div class="glassmorphism-header">
                        <div class="row">
                            <div class="col-7">
                                <div class="text-white p-4">
                                    <h5 class="text-white">Selamat Datang!</h5>
                                    <p class="text-white-50">Silahkan Masuk ke Dashboard</p>
                                </div>
                            </div>
                            <div class="col-5 align-self-end">
                                <img src="{{ asset('dashboard-assets/images/profile-img.png') }}" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                <div class="card-body pt-0">
                    <div class="auth-logo">
                        <a href="{{ route('home') }}" class="auth-logo-light">
                            <div class="avatar-lg profile-user-wid mb-4">
                                <span class="avatar-title rounded-circle bg-light" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                                    <img src="{{ asset('sigap-assets/images/favicon.ico') }}" alt="" class="rounded-circle" height="60" width="60" style="object-fit: contain;">
                                </span>
                            </div>
                        </a>

                        <a href="{{ route('home') }}" class="auth-logo-dark">
                            <div class="avatar-lg profile-user-wid mb-4">
                                <span class="avatar-title rounded-circle bg-light" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                                    <img src="{{ asset('sigap-assets/images/favicon.ico') }}" alt="" class="rounded-circle" height="60" width="60" style="object-fit: contain;">
                                </span>
                            </div>
                        </a>
                    </div>
                    <div class="p-2">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form class="form-horizontal" method="POST" action="{{ route('login') }}" id="loginForm">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}"
                                       placeholder="Masukkan email" required>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group auth-pass-inputgroup">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           name="password" placeholder="Masukkan password"
                                           autocomplete="current-password" required>
                                    <button class="btn btn-light" type="button" id="togglePasswordBtn">
                                        <i class="mdi mdi-eye-outline"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">CAPTCHA</label>
                                <div class="d-flex align-items-center mb-2">
                                    <img src="{{ session('captcha_image') }}" alt="CAPTCHA" id="captchaImage" class="border rounded" style="height: 60px;">
                                    <button type="button" class="btn btn-light ms-2" onclick="refreshCaptcha()" title="Refresh CAPTCHA">
                                        <i class="mdi mdi-refresh"></i>
                                    </button>
                                </div>
                                <input type="number" class="form-control @error('captcha') is-invalid @enderror"
                                       id="captcha" name="captcha" placeholder="Masukkan hasil perhitungan"
                                       required>
                                @error('captcha')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mt-3 d-grid">
                                <button class="btn btn-primary waves-effect waves-light" type="submit" id="loginButton">
                                    <span id="buttonText">Masuk</span>
                                    <span id="loadingSpinner" class="loading-spinner d-none"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="mt-5 text-center">
                <div>
                    <p class="copyright-text">© <script>document.write(new Date().getFullYear())</script> IPSDH - Kementerian Kehutanan | All Rights Reserved</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.querySelector('input[name="password"]');
        const icon = toggleBtn.querySelector('i');

        toggleBtn.addEventListener('click', function(e) {
            e.preventDefault();

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('mdi-eye-outline');
                icon.classList.add('mdi-eye-off-outline');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('mdi-eye-off-outline');
                icon.classList.add('mdi-eye-outline');
            }
        });
    });

    function refreshCaptcha() {
        const captchaImage = document.getElementById('captchaImage');
        const captchaInput = document.getElementById('captcha');

        // Show loading state
        captchaImage.style.opacity = '0.5';

        fetch('{{ route("refresh.captcha") }}')
            .then(response => response.json())
            .then(data => {
                captchaImage.src = data.captcha;
                captchaImage.style.opacity = '1';
                captchaInput.value = '';
                captchaInput.focus();
            })
            .catch(error => {
                console.error('Error refreshing CAPTCHA:', error);
                captchaImage.style.opacity = '1';
            });
    }

    // Check for success message from session
    @if(session('success') && session('auth_action') === 'logout')
        Swal.fire({
            icon: 'success',
            title: 'Logout Berhasil',
            text: '{{ session('success') }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    @endif

    // Handle form submission for login success
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        // Show loading state
        const loginButton = document.getElementById('loginButton');
        const buttonText = document.getElementById('buttonText');
        const loadingSpinner = document.getElementById('loadingSpinner');

        // Disable button and show loading
        loginButton.disabled = true;
        buttonText.textContent = 'Loading...';
        loadingSpinner.classList.remove('d-none');

        // Let the form submit normally, success will be handled on redirect
    });
</script>
@endpush
