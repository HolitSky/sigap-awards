@extends('dashboard.layouts.app')
@section('title', 'Manajemen Launch Date')

@push('styles')
<style>
    .launch-date-item {
        transition: all 0.3s ease;
        cursor: move;
    }
    .launch-date-item:hover {
        background-color: #f8f9fa;
    }
    .drag-handle {
        cursor: grab;
        color: #6c757d;
    }
    .drag-handle:active {
        cursor: grabbing;
    }
    .sortable-ghost {
        opacity: 0.4;
        background: #f8f9fa;
    }
</style>
@endpush

@section('content')

<div class="page-content">
    <div class="container-fluid">

        @include('dashboard.pages.form.components.sub-head')

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="mdi mdi-alert-circle me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Launch Date Management -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">
                                <i class="mdi mdi-calendar-range text-primary me-2"></i>Manajemen Launch Date
                            </h4>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addLaunchDateModal">
                                <i class="mdi mdi-plus-circle me-1"></i>Tambah Launch Date
                            </button>
                        </div>

                        <!-- Info Disclaimer -->
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-information-outline me-2"></i>
                            <strong>Info:</strong> Launch Date dengan <strong>urutan nomor 1</strong> dan status <strong>Aktif</strong> yang akan ditampilkan di halaman landing. Anda dapat mengubah urutan dengan drag & drop.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>

                        <!-- Launch Dates List -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50"><i class="mdi mdi-drag-vertical"></i></th>
                                        <th width="80">No. Urut</th>
                                        <th>Judul</th>
                                        <th>Tipe Tanggal</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th width="150">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="sortable-launch-dates">
                                    @forelse($launchDates as $index => $launchDate)
                                        <tr class="launch-date-item" data-id="{{ $launchDate->id }}">
                                            <td class="text-center">
                                                <i class="mdi mdi-drag-vertical drag-handle"></i>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-soft-primary font-size-14">{{ $index + 1 }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ $launchDate->title }}</strong>
                                            </td>
                                            <td>
                                                @if($launchDate->is_range_date)
                                                    <span class="badge bg-info">Range Date</span>
                                                @else
                                                    <span class="badge bg-secondary">Single Date</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($launchDate->is_range_date)
                                                    {{ $launchDate->start_date ? $launchDate->start_date->format('d M Y') : '-' }} 
                                                    s/d 
                                                    {{ $launchDate->end_date ? $launchDate->end_date->format('d M Y') : '-' }}
                                                @else
                                                    {{ $launchDate->single_date ? $launchDate->single_date->format('d M Y') : '-' }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($launchDate->is_active)
                                                    <span class="badge bg-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-danger">Tidak Aktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-warning btn-edit" 
                                                        data-id="{{ $launchDate->id }}"
                                                        data-title="{{ $launchDate->title }}"
                                                        data-is-range="{{ $launchDate->is_range_date ? 1 : 0 }}"
                                                        data-single-date="{{ $launchDate->single_date ? $launchDate->single_date->format('Y-m-d') : '' }}"
                                                        data-start-date="{{ $launchDate->start_date ? $launchDate->start_date->format('Y-m-d') : '' }}"
                                                        data-end-date="{{ $launchDate->end_date ? $launchDate->end_date->format('Y-m-d') : '' }}"
                                                        data-is-active="{{ $launchDate->is_active ? 1 : 0 }}"
                                                        data-order="{{ $launchDate->order }}">
                                                    <i class="mdi mdi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger btn-delete" 
                                                        data-id="{{ $launchDate->id }}"
                                                        data-title="{{ $launchDate->title }}">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">
                                                <i class="mdi mdi-information-outline me-1"></i>Belum ada data launch date
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
</div>

<!-- Modal: Add Launch Date -->
<div class="modal fade" id="addLaunchDateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('dashboard.cms.launch-date.store') }}">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="mdi mdi-plus-circle me-2"></i>Tambah Launch Date</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required minlength="4" maxlength="50" placeholder="Contoh: Penganugerahan Sigap Award">
                        <small class="text-muted">Minimal 4 karakter, maksimal 50 karakter</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tipe Tanggal <span class="text-danger">*</span></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="is_range_date" id="singleDateAdd" value="0" checked>
                            <label class="form-check-label" for="singleDateAdd">
                                Single Date (1 tanggal)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="is_range_date" id="rangeDateAdd" value="1">
                            <label class="form-check-label" for="rangeDateAdd">
                                Range Date (rentang tanggal)
                            </label>
                        </div>
                    </div>

                    <div id="singleDateFieldAdd" class="mb-3">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="single_date" class="form-control">
                    </div>

                    <div id="rangeDateFieldsAdd" class="mb-3" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" class="form-control">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" name="end_date" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" name="order" class="form-control" placeholder="Auto (urutan terakhir)" min="1">
                        <small class="text-muted">Nomor urut tampilan (1 = paling atas, 2 = kedua, dst). Kosongkan untuk otomatis di urutan terakhir.</small>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActiveAdd" checked>
                            <label class="form-check-label" for="isActiveAdd">
                                Aktifkan Launch Date
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-check me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Edit Launch Date -->
<div class="modal fade" id="editLaunchDateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="editLaunchDateForm">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title"><i class="mdi mdi-pencil me-2"></i>Edit Launch Date</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="editTitle" class="form-control" required minlength="4" maxlength="50">
                        <small class="text-muted">Minimal 4 karakter, maksimal 50 karakter</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tipe Tanggal <span class="text-danger">*</span></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="is_range_date" id="singleDateEdit" value="0">
                            <label class="form-check-label" for="singleDateEdit">
                                Single Date (1 tanggal)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="is_range_date" id="rangeDateEdit" value="1">
                            <label class="form-check-label" for="rangeDateEdit">
                                Range Date (rentang tanggal)
                            </label>
                        </div>
                    </div>

                    <div id="singleDateFieldEdit" class="mb-3">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="single_date" id="editSingleDate" class="form-control">
                    </div>

                    <div id="rangeDateFieldsEdit" class="mb-3" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" id="editStartDate" class="form-control">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" name="end_date" id="editEndDate" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" name="order" id="editOrder" class="form-control" min="1">
                        <small class="text-muted">Nomor urut tampilan (1 = paling atas, 2 = kedua, dst)</small>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActiveEdit">
                            <label class="form-check-label" for="isActiveEdit">
                                Aktifkan Launch Date
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="mdi mdi-check me-1"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('dashboard-assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
$(document).ready(function() {
    // Strict validation for title input (min 4, max 50)
    $('input[name="title"]').on('input', function() {
        let value = $(this).val();
        
        // Enforce max length
        if (value.length > 50) {
            $(this).val(value.substring(0, 50));
        }
        
        // Show validation feedback
        const length = $(this).val().length;
        const parent = $(this).parent();
        
        // Remove existing feedback
        parent.find('.validation-feedback').remove();
        
        if (length < 4 && length > 0) {
            parent.append('<div class="validation-feedback text-danger small mt-1">Minimal 4 karakter (saat ini: ' + length + ')</div>');
        } else if (length >= 4) {
            parent.append('<div class="validation-feedback text-success small mt-1">✓ Valid (' + length + '/50 karakter)</div>');
        }
    });

    // Toggle date fields on Add Modal
    $('input[name="is_range_date"]').on('change', function() {
        const isRange = $(this).val() == '1';
        const modalId = $(this).closest('.modal').attr('id');
        
        if (modalId === 'addLaunchDateModal') {
            if (isRange) {
                $('#singleDateFieldAdd').hide();
                $('#rangeDateFieldsAdd').show();
            } else {
                $('#singleDateFieldAdd').show();
                $('#rangeDateFieldsAdd').hide();
            }
        } else if (modalId === 'editLaunchDateModal') {
            if (isRange) {
                $('#singleDateFieldEdit').hide();
                $('#rangeDateFieldsEdit').show();
            } else {
                $('#singleDateFieldEdit').show();
                $('#rangeDateFieldsEdit').hide();
            }
        }
    });

    // Edit button click
    $('.btn-edit').on('click', function() {
        const id = $(this).data('id');
        const title = $(this).data('title');
        const isRange = $(this).data('is-range');
        const singleDate = $(this).data('single-date');
        const startDate = $(this).data('start-date');
        const endDate = $(this).data('end-date');
        const isActive = $(this).data('is-active');
        const order = $(this).data('order');

        $('#editTitle').val(title);
        // Display order as visual number (order + 1) for user-friendly display
        $('#editOrder').val(parseInt(order) + 1);
        $('#editSingleDate').val(singleDate);
        $('#editStartDate').val(startDate);
        $('#editEndDate').val(endDate);
        $('#isActiveEdit').prop('checked', isActive == 1);

        if (isRange == 1) {
            $('#rangeDateEdit').prop('checked', true);
            $('#singleDateFieldEdit').hide();
            $('#rangeDateFieldsEdit').show();
        } else {
            $('#singleDateEdit').prop('checked', true);
            $('#singleDateFieldEdit').show();
            $('#rangeDateFieldsEdit').hide();
        }

        $('#editLaunchDateForm').attr('action', '{{ route("dashboard.cms.launch-date.update", ":id") }}'.replace(':id', id));
        $('#editLaunchDateModal').modal('show');
    });

    // Delete button click
    $('.btn-delete').on('click', function() {
        const id = $(this).data('id');
        const title = $(this).data('title');

        Swal.fire({
            title: 'Hapus Launch Date?',
            text: `Apakah Anda yakin ingin menghapus "${title}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("dashboard.cms.launch-date.destroy", ":id") }}'.replace(':id', id),
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire('Berhasil!', response.message, 'success').then(() => {
                            location.reload();
                        });
                    },
                    error: function() {
                        Swal.fire('Error!', 'Terjadi kesalahan saat menghapus data', 'error');
                    }
                });
            }
        });
    });

    // Function to update row numbers and order data attributes
    function updateRowNumbers() {
        $('#sortable-launch-dates tr.launch-date-item').each(function(index) {
            // Update visual number
            $(this).find('td:eq(1) .badge').text(index + 1);
            
            // Update data-order attribute on edit button
            $(this).find('.btn-edit').attr('data-order', index);
        });
    }

    // Sortable for launch dates
    const sortable = new Sortable(document.getElementById('sortable-launch-dates'), {
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'sortable-ghost',
        onEnd: function(evt) {
            // Update row numbers immediately after drag
            updateRowNumbers();
            
            const orders = [];
            $('#sortable-launch-dates tr.launch-date-item').each(function(index) {
                orders.push({
                    id: $(this).data('id'),
                    order: index
                });
            });

            $.ajax({
                url: '{{ route("dashboard.cms.launch-date.update-order") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    orders: orders
                },
                success: function(response) {
                    // Show success toast notification
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message || 'Urutan berhasil diupdate',
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end',
                        timerProgressBar: true
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Gagal mengupdate urutan',
                        toast: true,
                        position: 'top-end',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            });
        }
    });
});
</script>
@endpush
