@extends('dashboard.layouts.app')

@section('title', 'Manajemen Menu Choices')

@section('content')
<style>
    .menu-choice-item {
        cursor: move;
    }
    .sortable-ghost {
        opacity: 0.4;
        background: #f8f9fa;
    }
    /* Ensure menu item inputs are clickable and editable */
    .menu-item input,
    .menu-item select,
    .menu-item button {
        pointer-events: auto !important;
        user-select: auto !important;
    }
    .menu-item .form-control,
    .menu-item .form-select {
        position: relative;
        z-index: 1;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Manajemen Menu Choices</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">CMS</li>
                        <li class="breadcrumb-item active">Menu Choices</li>
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
                            <h4 class="card-title">Daftar Menu Choices</h4>
                            <p class="card-title-desc">Kelola menu choices untuk landing page</p>
                        </div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMenuChoiceModal">
                            <i class="mdi mdi-plus me-1"></i> Tambah Menu Choice
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

                    <div class="alert alert-info">
                        <i class="mdi mdi-information-outline me-2"></i>
                        <strong>Info:</strong> Hanya 1 menu choice yang boleh aktif. Saat mengaktifkan menu choice baru, yang lain otomatis non-aktif.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="80">ID</th>
                                    <th>Judul Main Menu</th>
                                    <th>Mode</th>
                                    <th>Jumlah Menu</th>
                                    <th>Status</th>
                                    <th width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($menuChoices as $menuChoice)
                                    <tr class="menu-choice-item">
                                        <td class="text-center">
                                            <span class="badge badge-soft-primary font-size-14">{{ $menuChoice->id }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $menuChoice->main_menu_title ?: '-' }}</strong>
                                        </td>
                                        <td>
                                            @if($menuChoice->use_main_menu)
                                                <span class="badge bg-info"><i class="mdi mdi-window-maximize me-1"></i>Dengan Main Menu Modal</span>
                                            @else
                                                <span class="badge bg-secondary"><i class="mdi mdi-view-grid me-1"></i>Langsung Tampil</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ count($menuChoice->menu_items) }} Menu</span>
                                        </td>
                                        <td>
                                            @if($menuChoice->is_active)
                                                <span class="badge bg-success"><i class="mdi mdi-check-circle me-1"></i>Aktif</span>
                                            @else
                                                <span class="badge bg-danger"><i class="mdi mdi-close-circle me-1"></i>Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning btn-edit"
                                                    data-id="{{ $menuChoice->id }}"
                                                    data-main-menu-title="{{ $menuChoice->main_menu_title }}"
                                                    data-use-main-menu="{{ $menuChoice->use_main_menu ? 1 : 0 }}"
                                                    data-menu-items='@json($menuChoice->menu_items)'
                                                    data-is-active="{{ $menuChoice->is_active ? 1 : 0 }}">
                                                <i class="mdi mdi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger btn-delete"
                                                    data-id="{{ $menuChoice->id }}">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            <i class="mdi mdi-information-outline me-1"></i>Belum ada data menu choice
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

@include('dashboard.pages.cms.menu-choices.modals')
@include('dashboard.pages.cms.menu-choices.scripts')

@endsection
