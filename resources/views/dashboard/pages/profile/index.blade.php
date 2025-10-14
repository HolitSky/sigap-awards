@extends('dashboard.layouts.app')
@section('title', 'Profile')

@push('styles')
<!-- GLightbox CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />

<style>
    .avatar-xl {
        cursor: pointer;
        transition: transform 0.2s;
    }
    
    .avatar-xl:hover {
        transform: scale(1.05);
    }
</style>
@endpush

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Profile</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Profile</a></li>
                            <li class="breadcrumb-item active">User</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-12">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-check-all me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="text-center">
                                    <div class="mb-4">
                                        @php
                                            $profileImageUrl = $user->profile_image 
                                                ? asset('storage/' . $user->profile_image) 
                                                : asset('dashboard-assets/images/users/user-dummy-img.jpg');
                                        @endphp
                                        <a href="{{ $profileImageUrl }}" class="glightbox" data-glightbox="title: {{ $user->name }}; description: {{ $user->email }}">
                                            <img src="{{ $profileImageUrl }}"
                                                 alt="{{ $user->name }}"
                                                 class="avatar-xl rounded-circle img-thumbnail"
                                                 style="width: 96px; height: 96px; object-fit: cover;">
                                        </a>
                                    </div>
                                    <h5 class="font-size-15 mb-1">{{ $user->name }}</h5>
                                    <p class="text-muted mb-0">
                                        <span class="badge badge-soft-primary font-size-12">
                                            {{ $user->role_display }}
                                        </span>
                                    </p>
                                </div>

                                @if($user->profile_image)
                                    <div class="mt-4 text-center">
                                        <form action="{{ route('profile.delete-image') }}" method="POST" id="deleteImageForm">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm" id="deleteImageBtn">
                                                <i class="mdi mdi-delete"></i> Delete Image
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>

                            <div class="col-lg-8">
                                <h5 class="font-size-15 mb-3">Edit Profile</h5>
                                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Full Name</label>
                                        <input type="text"
                                               class="form-control @error('name') is-invalid @enderror"
                                               id="name"
                                               name="name"
                                               value="{{ old('name', $user->name) }}"
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               id="email"
                                               name="email"
                                               value="{{ old('email', $user->email) }}"
                                               required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="profile_image" class="form-label">Profile Image</label>
                                        <input type="file"
                                               class="form-control @error('profile_image') is-invalid @enderror"
                                               id="profile_image"
                                               name="profile_image"
                                               accept="image/*"
                                               onchange="previewImage(event)">
                                        <small class="text-muted">Max file size: 2MB. Allowed formats: jpeg, png, jpg</small>
                                        @error('profile_image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                        <!-- Image Preview -->
                                        <div id="imagePreviewContainer" class="mt-3" style="display: none;">
                                            <label class="form-label text-muted">Preview:</label>
                                            <div>
                                                <img id="imagePreview" src="" alt="Preview"
                                                     class="img-thumbnail rounded-circle"
                                                     style="width: 150px; height: 150px; object-fit: cover;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Role</label>
                                        <input type="text" class="form-control" value="{{ $user->role_display }}" disabled>
                                    </div>

                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-primary w-md">
                                            <i class="mdi mdi-content-save"></i> Update Profile
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->

@endsection

@push('scripts')
<script>
function previewImage(event) {
    const input = event.target;
    const previewContainer = document.getElementById('imagePreviewContainer');
    const previewImg = document.getElementById('imagePreview');

    if (input.files && input.files[0]) {
        const file = input.files[0];

        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'File Too Large',
                text: 'File size exceeds 2MB. Please choose a smaller file.',
                confirmButtonColor: '#f46a6a'
            });
            input.value = '';
            previewContainer.style.display = 'none';
            return;
        }

        // Validate file type
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid File Type',
                text: 'Please choose jpeg, png, jpg, or gif format.',
                confirmButtonColor: '#f46a6a'
            });
            input.value = '';
            previewContainer.style.display = 'none';
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewContainer.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        previewContainer.style.display = 'none';
    }
}

// Delete Image Confirmation
document.addEventListener('DOMContentLoaded', function() {
    const deleteBtn = document.getElementById('deleteImageBtn');

    if (deleteBtn) {
        deleteBtn.addEventListener('click', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Delete Profile Image?',
                html: '<p class="text-danger"><i class="mdi mdi-alert"></i> <strong>Warning!</strong></p>' +
                      '<p>Are you sure you want to delete your profile image?</p>' +
                      '<p class="text-muted">This action cannot be undone.</p>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f46a6a',
                cancelButtonColor: '#74788d',
                confirmButtonText: '<i class="mdi mdi-delete"></i> Yes, Delete It!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteImageForm').submit();
                }
            });
        });
    }
});

// Initialize GLightbox
document.addEventListener('DOMContentLoaded', function() {
    if (typeof GLightbox !== 'undefined') {
        GLightbox({
            selector: '.glightbox',
            touchNavigation: true,
            loop: true,
            autoplayVideos: true
        });
    }
});
</script>

<!-- GLightbox JS -->
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>

@endpush

