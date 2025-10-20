@extends('dashboard.layouts.app')
@section('title', 'Manajemen Sesi Presentasi')
@section('content')

<div class="page-content">
    <div class="container-fluid">
        
        @include('dashboard.pages.form.components.sub-head')


        <!-- BPKH Sessions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-3">
                            <i class="mdi mdi-calendar-clock text-primary me-2"></i>Sesi Presentasi BPKH
                        </h4>
                        
                        <!-- Add Participant Form -->
                        <form method="POST" action="{{ route('dashboard.presentation-session.bpkh.store') }}" class="mb-4">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Nama Sesi</label>
                                    <select name="session_name" class="form-select" required>
                                        <option value="">Pilih Sesi</option>
                                        <option value="Sesi 1">Sesi 1</option>
                                        <option value="Sesi 3">Sesi 3</option>
                                        <option value="Sesi 5">Sesi 5</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Pilih BPKH</label>
                                    <select name="respondent_id" class="form-select select2-bpkh" required>
                                        <option value="">Pilih BPKH</option>
                                        @foreach($availableBpkh as $bpkh)
                                            <option value="{{ $bpkh->respondent_id }}">{{ $bpkh->nama_bpkh }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="mdi mdi-plus me-1"></i>Tambah
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Sessions Display -->
                        <div class="row">
                            @foreach(['Sesi 1', 'Sesi 3', 'Sesi 5'] as $sessionName)
                                <div class="col-md-4 mb-3">
                                    <div class="card border">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0"><i class="mdi mdi-calendar-text me-2"></i>{{ $sessionName }}</h5>
                                        </div>
                                        <div class="card-body">
                                            @if(isset($bpkhSessions[$sessionName]) && $bpkhSessions[$sessionName]->count() > 0)
                                                <ul class="list-group list-group-flush">
                                                    @foreach($bpkhSessions[$sessionName] as $participant)
                                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                            <span>
                                                                <i class="mdi mdi-account-circle text-info me-2"></i>
                                                                {{ $participant->nama_bpkh }}
                                                            </span>
                                                            <button type="button" class="btn btn-sm btn-danger btn-delete-bpkh" 
                                                                    data-id="{{ $participant->id }}"
                                                                    data-name="{{ $participant->nama_bpkh }}">
                                                                <i class="mdi mdi-delete"></i>
                                                            </button>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-muted text-center mb-0">
                                                    <i class="mdi mdi-information-outline me-1"></i>Belum ada peserta
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Produsen Sessions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-3">
                            <i class="mdi mdi-calendar-clock text-success me-2"></i>Sesi Presentasi Produsen DG
                        </h4>
                        
                        <!-- Add Participant Form -->
                        <form method="POST" action="{{ route('dashboard.presentation-session.produsen.store') }}" class="mb-4">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Nama Sesi</label>
                                    <select name="session_name" class="form-select" required>
                                        <option value="">Pilih Sesi</option>
                                        <option value="Sesi 2">Sesi 2</option>
                                        <option value="Sesi 4">Sesi 4</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Pilih Produsen DG</label>
                                    <select name="respondent_id" class="form-select select2-produsen" required>
                                        <option value="">Pilih Produsen DG</option>
                                        @foreach($availableProdusen as $produsen)
                                            <option value="{{ $produsen->respondent_id }}">{{ $produsen->nama_instansi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="mdi mdi-plus me-1"></i>Tambah
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Sessions Display -->
                        <div class="row">
                            @foreach(['Sesi 2', 'Sesi 4'] as $sessionName)
                                <div class="col-md-6 mb-3">
                                    <div class="card border">
                                        <div class="card-header bg-success text-white">
                                            <h5 class="mb-0"><i class="mdi mdi-calendar-text me-2"></i>{{ $sessionName }}</h5>
                                        </div>
                                        <div class="card-body">
                                            @if(isset($produsenSessions[$sessionName]) && $produsenSessions[$sessionName]->count() > 0)
                                                <ul class="list-group list-group-flush">
                                                    @foreach($produsenSessions[$sessionName] as $participant)
                                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                            <span>
                                                                <i class="mdi mdi-office-building text-success me-2"></i>
                                                                {{ $participant->nama_instansi }}
                                                            </span>
                                                            <button type="button" class="btn btn-sm btn-danger btn-delete-produsen" 
                                                                    data-id="{{ $participant->id }}"
                                                                    data-name="{{ $participant->nama_instansi }}">
                                                                <i class="mdi mdi-delete"></i>
                                                            </button>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-muted text-center mb-0">
                                                    <i class="mdi mdi-information-outline me-1"></i>Belum ada peserta
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('styles')
<link href="{{ asset('dashboard-assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<style>
    .card-header {
        font-weight: 600;
    }
    .list-group-item {
        border-left: 0;
        border-right: 0;
    }
    .list-group-item:first-child {
        border-top: 0;
    }
    .list-group-item:last-child {
        border-bottom: 0;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('dashboard-assets/libs/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('dashboard-assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2-bpkh').select2({
        placeholder: 'Pilih BPKH',
        allowClear: true,
        width: '100%'
    });
    
    $('.select2-produsen').select2({
        placeholder: 'Pilih Produsen DG',
        allowClear: true,
        width: '100%'
    });
    
    // Show success/error messages from session
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        });
    @endif
    
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            confirmButtonText: 'OK'
        });
    @endif
    
    // Delete BPKH participant
    $('.btn-delete-bpkh').on('click', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        
        Swal.fire({
            title: 'Hapus Peserta?',
            html: `Apakah Anda yakin ingin menghapus<br><strong>${name}</strong><br>dari sesi ini?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="mdi mdi-delete me-1"></i> Ya, Hapus!',
            cancelButtonText: '<i class="mdi mdi-close me-1"></i> Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Create form and submit
                const form = $('<form>', {
                    'method': 'POST',
                    'action': '{{ route('dashboard.presentation-session.bpkh.destroy', ':id') }}'.replace(':id', id)
                });
                
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_token',
                    'value': '{{ csrf_token() }}'
                }));
                
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_method',
                    'value': 'DELETE'
                }));
                
                $('body').append(form);
                form.submit();
            }
        });
    });
    
    // Delete Produsen participant
    $('.btn-delete-produsen').on('click', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        
        Swal.fire({
            title: 'Hapus Peserta?',
            html: `Apakah Anda yakin ingin menghapus<br><strong>${name}</strong><br>dari sesi ini?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="mdi mdi-delete me-1"></i> Ya, Hapus!',
            cancelButtonText: '<i class="mdi mdi-close me-1"></i> Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Create form and submit
                const form = $('<form>', {
                    'method': 'POST',
                    'action': '{{ route('dashboard.presentation-session.produsen.destroy', ':id') }}'.replace(':id', id)
                });
                
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_token',
                    'value': '{{ csrf_token() }}'
                }));
                
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_method',
                    'value': 'DELETE'
                }));
                
                $('body').append(form);
                form.submit();
            }
        });
    });
});
</script>
@endpush
