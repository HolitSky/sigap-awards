@extends('dashboard.layouts.app')
@section('title', 'Manajemen Card Box')

@push('styles')
<style>
    .card-box-item { transition: all 0.3s ease; cursor: move; }
    .card-box-item:hover { background-color: #f8f9fa; }
    .drag-handle { cursor: grab; color: #6c757d; }
    .drag-handle:active { cursor: grabbing; }
    .sortable-ghost { opacity: 0.4; background: #f8f9fa; }
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

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">
                                <i class="mdi mdi-card-text text-primary me-2"></i>Manajemen Card Box
                            </h4>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addBoxCounterModal">
                                <i class="mdi mdi-plus-circle me-1"></i>Tambah Card Box
                            </button>
                        </div>

                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-information-outline me-2"></i>
                            <strong>Info:</strong> Card Box yang <strong>Aktif</strong> akan ditampilkan di halaman landing. Anda dapat mengubah urutan dengan drag & drop.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50"><i class="mdi mdi-drag-vertical"></i></th>
                                        <th width="80">No. Urut</th>
                                        <th>Judul</th>
                                        <th>Deskripsi</th>
                                        <th>Tipe Konten</th>
                                        <th>Status</th>
                                        <th width="150">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="sortable-card-boxes">
                                    @forelse($cardBoxes as $index => $cardBox)
                                        <tr class="card-box-item" data-id="{{ $cardBox->id }}">
                                            <td class="text-center">
                                                <i class="mdi mdi-drag-vertical drag-handle"></i>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-soft-primary font-size-14">{{ $index + 1 }}</span>
                                            </td>
                                            <td><strong>{{ $cardBox->title }}</strong></td>
                                            <td><small>{{ Str::limit($cardBox->description, 50) }}</small></td>
                                            <td>
                                                @if($cardBox->content_type === 'text_only')
                                                    <span class="badge bg-secondary"><i class="mdi mdi-text me-1"></i>Text Only</span>
                                                @elseif($cardBox->content_type === 'link')
                                                    <span class="badge bg-primary"><i class="mdi mdi-link me-1"></i>Link</span>
                                                @elseif($cardBox->content_type === 'modal')
                                                    <span class="badge bg-info"><i class="mdi mdi-window-maximize me-1"></i>Modal</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($cardBox->is_active)
                                                    <span class="badge bg-success"><i class="mdi mdi-check-circle me-1"></i>Aktif</span>
                                                @else
                                                    <span class="badge bg-danger"><i class="mdi mdi-close-circle me-1"></i>Tidak Aktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-warning btn-edit"
                                                        data-id="{{ $cardBox->id }}"
                                                        data-title="{{ $cardBox->title }}"
                                                        data-description="{{ $cardBox->description }}"
                                                        data-content-type="{{ $cardBox->content_type }}"
                                                        data-button-text="{{ $cardBox->button_text }}"
                                                        data-link-url="{{ $cardBox->link_url }}"
                                                        data-modal-content="{{ $cardBox->modal_content }}"
                                                        data-is-active="{{ $cardBox->is_active ? 1 : 0 }}"
                                                        data-order="{{ $cardBox->order }}">
                                                    <i class="mdi mdi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger btn-delete"
                                                        data-id="{{ $cardBox->id }}"
                                                        data-title="{{ $cardBox->title }}">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">
                                                <i class="mdi mdi-information-outline me-1"></i>Belum ada data card box
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

@include('dashboard.pages.cms.card-box.modals')

@endsection

@push('scripts')
<script src="{{ asset('dashboard-assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
@include('dashboard.pages.cms.card-box.scripts')
@endpush
