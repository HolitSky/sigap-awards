@extends('peserta-auth.layout-peserta')

@section('content')
<style>
    .login-wrapper {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #1a1a1a 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 0;
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

    /* Styling untuk select dropdown */
    select.form-control {
        background: rgba(255, 255, 255, 0.1) !important;
        color: white !important;
    }

    select.form-control option {
        background: #2d3748 !important;
        color: white !important;
        padding: 10px;
    }

    select.form-control option:hover {
        background: #4a5568 !important;
    }

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

    .copyright-text {
        color: rgba(255, 255, 255, 0.8);
    }

    /* Custom file input styling */
    .custom-file-upload {
        display: inline-block;
        padding: 8px 16px;
        cursor: pointer;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 4px;
        color: white;
        transition: all 0.3s;
    }

    .custom-file-upload:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.5);
    }

    .file-input-wrapper {
        position: relative;
    }

    .file-input-wrapper input[type="file"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .file-name-display {
        color: rgba(255, 255, 255, 0.8);
        font-size: 14px;
        margin-top: 8px;
    }

    .preview-container {
        margin-top: 10px;
        display: none;
    }

    .preview-container img {
        max-width: 200px;
        max-height: 200px;
        border-radius: 8px;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .remove-preview {
        margin-top: 5px;
        padding: 4px 12px;
        background: rgba(220, 53, 69, 0.8);
        border: none;
        border-radius: 4px;
        color: white;
        cursor: pointer;
        font-size: 12px;
    }

    .remove-preview:hover {
        background: rgba(220, 53, 69, 1);
    }
</style>

<div class="login-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card overflow-hidden glassmorphism-card">
                    <div class="glassmorphism-header">
                        <div class="row">
                            <div class="col-7">
                                <div class="text-white p-4">
                                    <h5 class="text-white">Daftar Akun Peserta</h5>
                                    <p class="text-white-50">Silahkan lengkapi data untuk mendaftar</p>
                                </div>
                            </div>
                            <div class="col-5 align-self-end">
                                <img src="{{ url('dashboard-assets/images/profile-img.png') }}" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="auth-logo">
                            <a href="{{ route('home') }}" class="auth-logo-light">
                                <div class="avatar-lg profile-user-wid mb-4">
                                    <span class="avatar-title rounded-circle bg-light" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                                        <img src="{{ url('sigap-assets/images/favicon.ico') }}" alt="" class="rounded-circle" height="60" width="60" style="object-fit: contain;">
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

                        <form class="form-horizontal" method="POST" action="{{ route('peserta.register.post') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               id="name" name="name" value="{{ old('name') }}"
                                               placeholder="Masukkan nama lengkap" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                               id="email" name="email" value="{{ old('email') }}"
                                               placeholder="Masukkan email" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Nomor WhatsApp</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                               id="phone" name="phone" value="{{ old('phone') }}"
                                               placeholder="Contoh: 081234567890">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="foto" class="form-label">Foto Profil <span class="text-danger">*</span></label>
                                        <div class="file-input-wrapper">
                                            <label for="foto" class="custom-file-upload">
                                                <i class="mdi mdi-upload"></i> Pilih Foto
                                            </label>
                                            <input type="file" id="foto" name="foto" 
                                                   accept="image/jpeg,image/jpg,image/png" 
                                                   required
                                                   onchange="previewImage(event)">
                                            <div class="file-name-display" id="fileName">Belum ada file dipilih</div>
                                        </div>
                                        <small class="text-white-50">Format: JPG, JPEG, PNG. Maksimal 1MB</small>
                                        @error('foto')
                                            <div class="text-danger d-block mt-1" style="font-size: 14px;">{{ $message }}</div>
                                        @enderror
                                        <div class="preview-container" id="previewContainer">
                                            <img id="imagePreview" src="" alt="Preview">
                                            <br>
                                            <button type="button" class="remove-preview" onclick="removeImage()">
                                                <i class="mdi mdi-close"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                                        <select class="form-control @error('kategori') is-invalid @enderror" 
                                                id="kategori" name="kategori" required>
                                            <option value="">-- Pilih Kategori --</option>
                                            <option value="bpkh" {{ old('kategori') == 'bpkh' ? 'selected' : '' }}>BPKH</option>
                                            <option value="produsen" {{ old('kategori') == 'produsen' ? 'selected' : '' }}>Produsen</option>
                                        </select>
                                        @error('kategori')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3" id="bpkh-select" style="display: none;">
                                        <label for="bpkh_id" class="form-label">Wilayah BPKH <span class="text-danger">*</span></label>
                                        <select class="form-control @error('bpkh_id') is-invalid @enderror" 
                                                id="bpkh_id" name="bpkh_id">
                                            <option value="">-- Pilih Wilayah BPKH --</option>
                                            @foreach($bpkhList as $bpkh)
                                                <option value="{{ $bpkh->id }}" {{ old('bpkh_id') == $bpkh->id ? 'selected' : '' }}>
                                                    {{ $bpkh->nama_wilayah }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('bpkh_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3" id="produsen-select" style="display: none;">
                                        <label for="produsen_id" class="form-label">Unit Produsen <span class="text-danger">*</span></label>
                                        <select class="form-control @error('produsen_id') is-invalid @enderror" 
                                                id="produsen_id" name="produsen_id">
                                            <option value="">-- Pilih Unit Produsen --</option>
                                            @foreach($produsenList as $produsen)
                                                <option value="{{ $produsen->id }}" {{ old('produsen_id') == $produsen->id ? 'selected' : '' }}>
                                                    {{ $produsen->nama_unit }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('produsen_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                                   id="password" name="password" placeholder="Masukkan password" required>
                                            <button class="btn btn-light" type="button" id="togglePassword">
                                                <i class="mdi mdi-eye-outline"></i>
                                            </button>
                                        </div>
                                        <small class="text-white-50">Minimal 6 karakter</small>
                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control"
                                                   id="password_confirmation" name="password_confirmation" 
                                                   placeholder="Konfirmasi password" required>
                                            <button class="btn btn-light" type="button" id="togglePasswordConfirm">
                                                <i class="mdi mdi-eye-outline"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- CAPTCHA -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">CAPTCHA <span class="text-danger">*</span></label>
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <img src="{{ session('peserta_captcha_image') }}" alt="CAPTCHA" id="captchaImage" 
                                                 style="border: 1px solid rgba(255,255,255,0.3); border-radius: 4px;">
                                            <button type="button" class="btn btn-light btn-sm" onclick="refreshCaptcha()" title="Refresh CAPTCHA">
                                                <i class="mdi mdi-refresh"></i>
                                            </button>
                                        </div>
                                        <input type="text" class="form-control @error('captcha') is-invalid @enderror" 
                                               name="captcha" placeholder="Masukkan hasil perhitungan" 
                                               autocomplete="off" required>
                                        @error('captcha')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 d-grid">
                                <button class="btn btn-primary waves-effect waves-light" type="submit">
                                    Daftar Sekarang
                                </button>
                            </div>

                            <div class="mt-4 text-center">
                                <p class="text-white-50">Sudah punya akun? <a href="{{ route('peserta.login') }}" class="fw-medium text-white">Login Disini</a></p>
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

@push('scripts')
<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const password = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.remove('mdi-eye-outline');
            icon.classList.add('mdi-eye-off-outline');
        } else {
            password.type = 'password';
            icon.classList.remove('mdi-eye-off-outline');
            icon.classList.add('mdi-eye-outline');
        }
    });

    document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
        const password = document.getElementById('password_confirmation');
        const icon = this.querySelector('i');
        
        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.remove('mdi-eye-outline');
            icon.classList.add('mdi-eye-off-outline');
        } else {
            password.type = 'password';
            icon.classList.remove('mdi-eye-off-outline');
            icon.classList.add('mdi-eye-outline');
        }
    });

    // Toggle kategori dropdown
    const kategoriSelect = document.getElementById('kategori');
    const bpkhSelect = document.getElementById('bpkh-select');
    const produsenSelect = document.getElementById('produsen-select');
    const bpkhInput = document.getElementById('bpkh_id');
    const produsenInput = document.getElementById('produsen_id');

    // Check on page load (for old input)
    if (kategoriSelect.value) {
        toggleKategori(kategoriSelect.value);
    }

    kategoriSelect.addEventListener('change', function() {
        toggleKategori(this.value);
    });

    function toggleKategori(value) {
        if (value === 'bpkh') {
            bpkhSelect.style.display = 'block';
            produsenSelect.style.display = 'none';
            bpkhInput.required = true;
            produsenInput.required = false;
            produsenInput.value = '';
        } else if (value === 'produsen') {
            bpkhSelect.style.display = 'none';
            produsenSelect.style.display = 'block';
            bpkhInput.required = false;
            produsenInput.required = true;
            bpkhInput.value = '';
        } else {
            bpkhSelect.style.display = 'none';
            produsenSelect.style.display = 'none';
            bpkhInput.required = false;
            produsenInput.required = false;
        }
    }

    // Preview image function
    function previewImage(event) {
        const file = event.target.files[0];
        const fileName = document.getElementById('fileName');
        const previewContainer = document.getElementById('previewContainer');
        const imagePreview = document.getElementById('imagePreview');
        
        if (file) {
            // Validasi ukuran file (1.5MB = 1572864 bytes)
            const maxSize = 1572864; // 1.5MB
            if (file.size > maxSize) {
                alert('Ukuran file terlalu besar! Maksimal 1.5MB');
                event.target.value = '';
                fileName.textContent = 'Belum ada file dipilih';
                previewContainer.style.display = 'none';
                return;
            }

            // Validasi tipe file
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!allowedTypes.includes(file.type)) {
                alert('Format file tidak didukung! Gunakan JPG, JPEG, atau PNG');
                event.target.value = '';
                fileName.textContent = 'Belum ada file dipilih';
                previewContainer.style.display = 'none';
                return;
            }

            // Tampilkan nama file
            fileName.textContent = file.name;
            
            // Preview image
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                previewContainer.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            fileName.textContent = 'Belum ada file dipilih';
            previewContainer.style.display = 'none';
        }
    }

    // Remove image function
    function removeImage() {
        const fileInput = document.getElementById('foto');
        const fileName = document.getElementById('fileName');
        const previewContainer = document.getElementById('previewContainer');
        
        fileInput.value = '';
        fileName.textContent = 'Belum ada file dipilih';
        previewContainer.style.display = 'none';
    }

    // Refresh CAPTCHA function
    function refreshCaptcha() {
        fetch('{{ route("peserta.refresh-captcha") }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('captchaImage').src = data.captcha;
            })
            .catch(error => {
                console.error('Error refreshing CAPTCHA:', error);
            });
    }
</script>
@endpush
@endsection
