@extends('peserta-auth.layout-peserta')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Dashboard Peserta</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Selamat Datang, {{ Auth::guard('peserta')->user()->name }}!</h4>
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-3 text-center mb-4">
                            @if(Auth::guard('peserta')->user()->foto)
                                <img src="{{ asset('storage/' . Auth::guard('peserta')->user()->foto) }}" 
                                     alt="Foto Profil" 
                                     class="rounded-circle img-thumbnail" 
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center" 
                                     style="width: 150px; height: 150px;">
                                    <i class="mdi mdi-account" style="font-size: 80px; color: white;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Email:</label>
                                        <p>{{ Auth::guard('peserta')->user()->email }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Nomor WhatsApp:</label>
                                        <p>{{ Auth::guard('peserta')->user()->phone ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Kategori:</label>
                                        <p>
                                            @if(Auth::guard('peserta')->user()->kategori === 'bpkh')
                                                <span class="badge bg-primary">BPKH</span>
                                            @else
                                                <span class="badge bg-info">Produsen</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Status:</label>
                                        <p>
                                            @if(Auth::guard('peserta')->user()->status === 'active')
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-danger">Tidak Aktif</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">
                                            @if(Auth::guard('peserta')->user()->kategori === 'bpkh')
                                                Wilayah BPKH:
                                            @else
                                                Unit Produsen:
                                            @endif
                                        </label>
                                        <p>{{ Auth::guard('peserta')->user()->kategori_name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <form method="POST" action="{{ route('peserta.logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class="mdi mdi-logout"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
