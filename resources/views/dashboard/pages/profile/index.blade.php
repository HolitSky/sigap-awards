@extends('dashboard.layouts.app')
@section('title', 'Profile')
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
                                        @if($user->profile_image)
                                            <img src="{{ asset('storage/' . $user->profile_image) }}"
                                                 alt="Profile Image"
                                                 class="avatar-xl rounded-circle img-thumbnail">
                                        @else
                                            <img src="{{ asset('dashboard-assets/images/users/user-dummy-img.jpg') }}"
                                                 alt="Default Avatar"
                                                 class="avatar-xl rounded-circle img-thumbnail">
                                        @endif
                                    </div>
                                    <h5 class="font-size-15 mb-1">{{ $user->name }}</h5>
                                    <p class="text-muted mb-0">
                                        <span class="badge badge-soft-primary font-size-12">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </p>
                                </div>

                                @if($user->profile_image)
                                    <div class="mt-4 text-center">
                                        <form action="{{ route('profile.delete-image') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete your profile image?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
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
                                               accept="image/*">
                                        <small class="text-muted">Max file size: 2MB. Allowed formats: jpeg, png, jpg, gif</small>
                                        @error('profile_image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Role</label>
                                        <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" disabled>
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

