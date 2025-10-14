@extends('dashboard.layouts.app')
@section('title', 'Synchronize Form Data')

@push('styles')
<style>
    .sync-card {
        transition: transform 0.2s;
        cursor: pointer;
    }
    .sync-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .loading-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }
    .loading-overlay.active {
        display: flex;
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
                    <h4 class="mb-sm-0 font-size-18">Synchronize Form Data</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
                            <li class="breadcrumb-item active">Sync Form</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- Info Alert -->
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-information me-2"></i>
                    <strong>Info:</strong> Fitur ini akan melakukan sinkronisasi data dari Google Sheets ke database. Pastikan koneksi internet stabil sebelum melakukan sync.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>

        <!-- Sync Cards -->
        <div class="row">
            <div class="col-md-6">
                <div class="card mini-stats-wid sync-card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium mb-2">Form BPKH</p>
                                <h4 class="mb-2">{{ $countBpkh ?? 0 }} <small class="text-muted font-size-14">Total Submit</small></h4>
                                <small class="text-muted">
                                    <i class="mdi mdi-clock-outline"></i> Last sync: {{ $lastSyncBpkhText ?? 'Belum pernah sync' }}
                                </small>
                                <div class="mt-3">
                                    <button type="button" class="btn btn-primary btn-sm waves-effect waves-light" id="syncBpkhBtn">
                                        <i class="mdi mdi-sync"></i> Synchronize Data BPKH
                                    </button>
                                </div>
                            </div>

                            <div class="flex-shrink-0 align-self-center">
                                <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-primary">
                                        <i class="bx bx-archive-in font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mini-stats-wid sync-card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium mb-2">Form Produsen DG</p>
                                <h4 class="mb-2">{{ $countProdusen ?? 0 }} <small class="text-muted font-size-14">Total Submit</small></h4>
                                <small class="text-muted">
                                    <i class="mdi mdi-clock-outline"></i> Last sync: {{ $lastSyncProdusenText ?? 'Belum pernah sync' }}
                                </small>
                                <div class="mt-3">
                                    <button type="button" class="btn btn-success btn-sm waves-effect waves-light" id="syncProdusenBtn">
                                        <i class="mdi mdi-sync"></i> Synchronize Data Produsen DG
                                    </button>
                                </div>
                            </div>

                            <div class="flex-shrink-0 align-self-center">
                                <div class="avatar-sm rounded-circle bg-success mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-success">
                                        <i class="bx bx-archive-in font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sync History/Log (Optional) -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="mdi mdi-history"></i> Sync Information
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Form Type</th>
                                        <th>Total Records</th>
                                        <th>Last Sync</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>BPKH</strong></td>
                                        <td>{{ $countBpkh ?? 0 }} records</td>
                                        <td>{{ $lastSyncBpkhText ?? '-' }}</td>
                                        <td>
                                            @if($lastSyncBpkhText)
                                                <span class="badge bg-success">Synced</span>
                                            @else
                                                <span class="badge bg-warning">Not Synced</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Produsen DG</strong></td>
                                        <td>{{ $countProdusen ?? 0 }} records</td>
                                        <td>{{ $lastSyncProdusenText ?? '-' }}</td>
                                        <td>
                                            @if($lastSyncProdusenText)
                                                <span class="badge bg-success">Synced</span>
                                            @else
                                                <span class="badge bg-warning">Not Synced</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="text-center">
        <div class="spinner-border text-light" role="status" style="width: 3rem; height: 3rem;">
            <span class="sr-only">Loading...</span>
        </div>
        <p class="text-white mt-3">Sedang melakukan sinkronisasi data...</p>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Setup CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    // Sync BPKH
    $('#syncBpkhBtn').on('click', function() {
        Swal.fire({
            title: 'Konfirmasi Sync BPKH',
            html: '<p class="text-warning"><i class="mdi mdi-alert"></i> <strong>Peringatan!</strong></p>' +
                  '<p>Proses ini akan melakukan sinkronisasi ulang data BPKH dari Google Sheets.</p>' +
                  '<p>Data yang sudah ada akan di-update. Apakah Anda yakin?</p>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#556ee6',
            cancelButtonColor: '#74788d',
            confirmButtonText: '<i class="mdi mdi-sync"></i> Ya, Lakukan Sync!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                syncData('bpkh');
            }
        });
    });

    // Sync Produsen
    $('#syncProdusenBtn').on('click', function() {
        Swal.fire({
            title: 'Konfirmasi Sync Produsen DG',
            html: '<p class="text-warning"><i class="mdi mdi-alert"></i> <strong>Peringatan!</strong></p>' +
                  '<p>Proses ini akan melakukan sinkronisasi ulang data Produsen DG dari Google Sheets.</p>' +
                  '<p>Data yang sudah ada akan di-update. Apakah Anda yakin?</p>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#34c38f',
            cancelButtonColor: '#74788d',
            confirmButtonText: '<i class="mdi mdi-sync"></i> Ya, Lakukan Sync!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                syncData('produsen');
            }
        });
    });

    function syncData(type) {
        const url = type === 'bpkh' 
            ? '{{ route("sync-form.bpkh") }}' 
            : '{{ route("sync-form.produsen") }}';
        
        const title = type === 'bpkh' ? 'BPKH' : 'Produsen DG';

        // Show loading
        $('#loadingOverlay').addClass('active');

        $.ajax({
            url: url,
            type: 'POST',
            success: function(response) {
                $('#loadingOverlay').removeClass('active');
                
                Swal.fire({
                    icon: 'success',
                    title: 'Sync Berhasil!',
                    html: '<p>' + response.message + '</p>' +
                          '<small class="text-muted">Output: ' + (response.output || 'Success') + '</small>',
                    confirmButtonColor: '#556ee6'
                }).then(() => {
                    // Reload page to update data
                    location.reload();
                });
            },
            error: function(xhr) {
                $('#loadingOverlay').removeClass('active');
                
                const message = xhr.responseJSON?.message || 'Terjadi kesalahan saat melakukan sync';
                
                Swal.fire({
                    icon: 'error',
                    title: 'Sync Gagal!',
                    text: message,
                    confirmButtonColor: '#f46a6a'
                });
            }
        });
    }
});
</script>
@endpush
