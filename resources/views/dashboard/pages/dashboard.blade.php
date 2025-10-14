@extends('dashboard.layouts.app')
@section('title', 'Dashboard')

@push('styles')
<!-- GLightbox CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />

<style>
    .profile-user-wid img {
        cursor: pointer;
        transition: transform 0.2s;
    }
    
    .profile-user-wid img:hover {
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
                    <h4 class="mb-sm-0 font-size-18">Dashboard</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item active">Beranda</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-4">
                <div class="card overflow-hidden">
                    <div class="bg-primary-subtle">
                        <div class="row">
                            <div class="col-7">
                                <div class="text-primary p-3">
                                    <h5 class="text-primary">Welcome Back !</h5>
                                    <p>Sigap Award Dashboard</p>
                                </div>
                            </div>
                            <div class="col-5 align-self-end">
                                <img src="{{ asset('dashboard-assets/images/profile-img.png') }}" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="avatar-md profile-user-wid mb-4" style="width: 100px; height: 100px;">
                                    @php
                                        $user = Auth::user();
                                        $profileImage = $user?->profile_image
                                            ? asset('storage/'.$user->profile_image)
                                            : asset('dashboard-assets/images/users/user-dummy-img.jpg');
                                    @endphp
                                    <a href="{{ $profileImage }}" class="glightbox" data-glightbox="title: {{ $user?->name }}; description: Dashboard Profile">
                                        <img src="{{ $profileImage }}" alt="{{ $user?->name }}" class="img-thumbnail rounded-circle"
                                             style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                    </a>
                                </div>
                                <h5 class="font-size-15">{{ $user?->name }}</h5>
                                <p class="text-muted mb-0 badge badge-soft-primary p-2 font-size-12 mt-2"><i class="mdi mdi-account"></i> {{ strtoupper($roleDisplay ?? '') }}</p>
                            </div>

                            <div class="col-sm-8">
                                <div class="pt-4">
                                    <div class="mt-4">
                                        <a href="{{ route('profile.index') }}" class="btn btn-primary waves-effect waves-light btn-sm">Update Profile <i class="mdi mdi-arrow-right ms-1"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-xl-8">
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{ route('dashboard.form.bpkh.index') }}" class="text-reset">
                            <div class="card mini-stats-wid">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-muted fw-medium">Form BPKH Total Submit</p>
                                            <h4 class="mb-0">{{ $countBpkh ?? 0 }}</h4>
                                            <small class="text-muted">Last sync: {{ $lastSyncBpkhText ?? '-' }}</small>
                                        </div>

                                        <div class="flex-shrink-0 align-self-center ">
                                            <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                <span class="avatar-title rounded-circle bg-primary">
                                                    <i class="bx bx-archive-in font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('dashboard.form.produsen-dg.index') }}" class="text-reset">
                            <div class="card mini-stats-wid">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-muted fw-medium">Form Produsen DG Total Submit</p>
                                            <h4 class="mb-0">{{ $countProdusen ?? 0 }}</h4>
                                            <small class="text-muted">Last sync: {{ $lastSyncProdusenText ?? '-' }}</small>
                                        </div>

                                        <div class="flex-shrink-0 align-self-center ">
                                            <div class="avatar-sm rounded-circle bg-success mini-stat-icon">
                                                <span class="avatar-title rounded-circle bg-success">
                                                    <i class="bx bx-archive-in font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->


    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->

@include('dashboard.layouts.components.info_modal')
@endsection

@push('scripts')
<script>
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

