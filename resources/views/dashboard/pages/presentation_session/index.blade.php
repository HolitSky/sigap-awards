@extends('dashboard.layouts.app')
@section('title', 'Manajemen Sesi Presentasi')

@push('styles')
<style>
    .sortable-ghost {
        opacity: 0.4;
        background: #f8f9fa;
    }
    .sortable-drag {
        opacity: 0.8;
        cursor: move;
    }
    .list-group-item {
        cursor: move;
        transition: all 0.3s ease;
    }
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
    .drag-handle {
        cursor: grab;
        color: #6c757d;
    }
    .drag-handle:active {
        cursor: grabbing;
    }
</style>
@endpush

@section('content')

<div class="page-content">
    <div class="container-fluid">
        
        @include('dashboard.pages.form.components.sub-head')


        <!-- BPKH Sessions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">
                                <i class="mdi mdi-calendar-clock text-primary me-2"></i>Sesi Presentasi BPKH
                            </h4>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addBpkhSessionModal">
                                <i class="mdi mdi-plus-circle me-1"></i>Tambah Sesi Baru
                            </button>
                        </div>
                        
                        <!-- Add Participant Form -->
                        <form method="POST" action="{{ route('dashboard.presentation-session.bpkh.store') }}" class="mb-4">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Nama Sesi</label>
                                    <select name="session_name" class="form-select" required>
                                        <option value="">Pilih Sesi</option>
                                        @foreach($bpkhSessionConfigs as $config)
                                            <option value="{{ $config->session_name }}">{{ $config->session_name }}</option>
                                        @endforeach
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
                            @foreach($bpkhSessionConfigs as $config)
                                @php
                                    $sessionName = $config->session_name;
                                @endphp
                                <div class="col-md-4 mb-3">
                                    <div class="card border">
                                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0"><i class="mdi mdi-calendar-text me-2"></i>{{ $sessionName }}</h5>
                                            <button type="button" class="btn btn-sm btn-light btn-delete-session-config" 
                                                    data-id="{{ $config->id }}"
                                                    data-name="{{ $sessionName }}">
                                                <i class="mdi mdi-close"></i>
                                            </button>
                                        </div>
                                        <div class="card-body">
                                            @if(isset($bpkhSessions[$sessionName]) && $bpkhSessions[$sessionName]->count() > 0)
                                                <ul class="list-group list-group-flush sortable-bpkh" data-session="{{ $sessionName }}">
                                                    @foreach($bpkhSessions[$sessionName] as $participant)
                                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0" data-id="{{ $participant->id }}">
                                                            <span>
                                                                <i class="mdi mdi-drag-vertical drag-handle me-2"></i>
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
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">
                                <i class="mdi mdi-calendar-clock text-success me-2"></i>Sesi Presentasi Produsen DG
                            </h4>
                            <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#addProdusenSessionModal">
                                <i class="mdi mdi-plus-circle me-1"></i>Tambah Sesi Baru
                            </button>
                        </div>
                        
                        <!-- Add Participant Form -->
                        <form method="POST" action="{{ route('dashboard.presentation-session.produsen.store') }}" class="mb-4">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Nama Sesi</label>
                                    <select name="session_name" class="form-select" required>
                                        <option value="">Pilih Sesi</option>
                                        @foreach($produsenSessionConfigs as $config)
                                            <option value="{{ $config->session_name }}">{{ $config->session_name }}</option>
                                        @endforeach
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
                            @foreach($produsenSessionConfigs as $config)
                                @php
                                    $sessionName = $config->session_name;
                                @endphp
                                <div class="col-md-6 mb-3">
                                    <div class="card border">
                                        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0"><i class="mdi mdi-calendar-text me-2"></i>{{ $sessionName }}</h5>
                                            <button type="button" class="btn btn-sm btn-light btn-delete-session-config" 
                                                    data-id="{{ $config->id }}"
                                                    data-name="{{ $sessionName }}">
                                                <i class="mdi mdi-close"></i>
                                            </button>
                                        </div>
                                        <div class="card-body">
                                            @if(isset($produsenSessions[$sessionName]) && $produsenSessions[$sessionName]->count() > 0)
                                                <ul class="list-group list-group-flush sortable-produsen" data-session="{{ $sessionName }}">
                                                    @foreach($produsenSessions[$sessionName] as $participant)
                                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0" data-id="{{ $participant->id }}">
                                                            <span>
                                                                <i class="mdi mdi-drag-vertical drag-handle me-2"></i>
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

<!-- Modal: Add BPKH Session -->
<div class="modal fade" id="addBpkhSessionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('dashboard.presentation-session.config.store') }}">
                @csrf
                <input type="hidden" name="session_type" value="bpkh">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="mdi mdi-plus-circle me-2"></i>Tambah Sesi BPKH Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nomor Sesi <span class="text-danger">*</span></label>
                        <input type="number" name="session_number" class="form-control" min="1" required placeholder="Contoh: 6, 7, 8, dst">
                        <small class="text-muted">Masukkan nomor sesi yang belum ada</small>
                    </div>
                    <div class="alert alert-info mb-0">
                        <i class="mdi mdi-information me-2"></i>
                        <strong>Info:</strong> Sesi akan dibuat dengan nama "Sesi [Nomor]". Contoh: Sesi 6, Sesi 7, dst.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-check me-1"></i>Tambah Sesi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Add Produsen Session -->
<div class="modal fade" id="addProdusenSessionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('dashboard.presentation-session.config.store') }}">
                @csrf
                <input type="hidden" name="session_type" value="produsen">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="mdi mdi-plus-circle me-2"></i>Tambah Sesi Produsen Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nomor Sesi <span class="text-danger">*</span></label>
                        <input type="number" name="session_number" class="form-control" min="1" required placeholder="Contoh: 6, 7, 8, dst">
                        <small class="text-muted">Masukkan nomor sesi yang belum ada</small>
                    </div>
                    <div class="alert alert-info mb-0">
                        <i class="mdi mdi-information me-2"></i>
                        <strong>Info:</strong> Sesi akan dibuat dengan nama "Sesi [Nomor]". Contoh: Sesi 6, Sesi 7, dst.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="mdi mdi-check me-1"></i>Tambah Sesi
                    </button>
                </div>
            </form>
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
    
    // Delete Session Config
    $('.btn-delete-session-config').on('click', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        
        Swal.fire({
            title: 'Hapus Konfigurasi Sesi?',
            html: `Apakah Anda yakin ingin menghapus<br><strong>${name}</strong>?<br><br><small class="text-danger">Sesi hanya bisa dihapus jika tidak ada peserta di dalamnya.</small>`,
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
                    'action': '{{ route('dashboard.presentation-session.config.destroy', ':id') }}'.replace(':id', id)
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

    // ===== Drag & Drop with SortableJS =====
    // Initialize Sortable for BPKH lists
    document.querySelectorAll('.sortable-bpkh').forEach(function(el) {
        new Sortable(el, {
            group: 'bpkh-sessions', // Allow dragging between BPKH sessions
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            onEnd: function(evt) {
                const sessionName = evt.to.dataset.session;
                const items = [];
                
                // Get all items in the new session
                evt.to.querySelectorAll('li').forEach(function(li, index) {
                    items.push({
                        id: li.dataset.id,
                        order: index + 1
                    });
                });
                
                // Send AJAX request to update order
                fetch('{{ route('dashboard.presentation-session.update-order') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        type: 'bpkh',
                        session_name: sessionName,
                        items: items
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Urutan peserta berhasil diupdate',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat mengupdate urutan'
                    });
                    // Reload page on error
                    setTimeout(() => location.reload(), 1500);
                });
            }
        });
    });

    // Initialize Sortable for Produsen lists
    document.querySelectorAll('.sortable-produsen').forEach(function(el) {
        new Sortable(el, {
            group: 'produsen-sessions', // Allow dragging between Produsen sessions
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            onEnd: function(evt) {
                const sessionName = evt.to.dataset.session;
                const items = [];
                
                // Get all items in the new session
                evt.to.querySelectorAll('li').forEach(function(li, index) {
                    items.push({
                        id: li.dataset.id,
                        order: index + 1
                    });
                });
                
                // Send AJAX request to update order
                fetch('{{ route('dashboard.presentation-session.update-order') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        type: 'produsen',
                        session_name: sessionName,
                        items: items
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Urutan peserta berhasil diupdate',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat mengupdate urutan'
                    });
                    // Reload page on error
                    setTimeout(() => location.reload(), 1500);
                });
            }
        });
    });
});
</script>

<!-- SortableJS Library -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
@endpush
