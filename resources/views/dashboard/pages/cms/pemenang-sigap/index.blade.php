@extends('dashboard.layouts.app')

@section('title', 'Manajemen Pemenang SIGAP Award 2025')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Manajemen Pemenang SIGAP Award 2025</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">CMS</li>
                        <li class="breadcrumb-item active">Pemenang SIGAP Award</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="card-title">Daftar Pemenang</h4>
                            <p class="card-title-desc">Kelola data pemenang SIGAP Award 2025</p>
                        </div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPemenangModal">
                            <i class="mdi mdi-plus me-1"></i> Tambah Pemenang
                        </button>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-alert-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">No</th>
                                    <th>Kategori</th>
                                    <th>Tipe</th>
                                    <th>Nama Pemenang</th>
                                    <th>Juara</th>
                                    <th>Urutan</th>
                                    <th>Status</th>
                                    <th width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pemenang as $index => $item)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $item->kategori_label }}</strong>
                                        </td>
                                        <td>
                                            @if($item->tipe_peserta === 'bpkh')
                                                <span class="badge bg-primary">BPKH</span>
                                            @else
                                                <span class="badge bg-info">Produsen</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->nama_pemenang }}</td>
                                        <td>
                                            @if($item->juara === 'juara_1')
                                                <span class="badge bg-warning text-dark"><i class="mdi mdi-trophy me-1"></i>Juara 1</span>
                                            @elseif($item->juara === 'juara_2')
                                                <span class="badge bg-secondary"><i class="mdi mdi-medal me-1"></i>Juara 2</span>
                                            @elseif($item->juara === 'juara_3')
                                                <span class="badge bg-secondary"><i class="mdi mdi-medal me-1"></i>Juara 3</span>
                                            @else
                                                <span class="badge bg-light text-dark"><i class="mdi mdi-star me-1"></i>Juara Harapan</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">{{ $item->urutan }}</span>
                                        </td>
                                        <td>
                                            @if($item->is_active)
                                                <span class="badge bg-success"><i class="mdi mdi-check-circle me-1"></i>Aktif</span>
                                            @else
                                                <span class="badge bg-danger"><i class="mdi mdi-close-circle me-1"></i>Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning btn-edit"
                                                    data-id="{{ $item->id }}"
                                                    data-kategori="{{ $item->kategori }}"
                                                    data-tipe-peserta="{{ $item->tipe_peserta }}"
                                                    data-nama-pemenang="{{ $item->nama_pemenang }}"
                                                    data-nama-petugas="{{ $item->nama_petugas }}"
                                                    data-juara="{{ $item->juara }}"
                                                    data-deskripsi="{{ $item->deskripsi }}"
                                                    data-foto-path="{{ $item->foto_path }}"
                                                    data-urutan="{{ $item->urutan }}"
                                                    data-is-active="{{ $item->is_active ? 1 : 0 }}">
                                                <i class="mdi mdi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger btn-delete"
                                                    data-id="{{ $item->id }}">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">
                                            <i class="mdi mdi-information-outline me-1"></i>Belum ada data pemenang
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('dashboard.pages.cms.pemenang-sigap.modals')
@include('dashboard.pages.cms.pemenang-sigap.scripts')

@endsection
