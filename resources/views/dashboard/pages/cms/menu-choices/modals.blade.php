<!-- Modal: Add Menu Choice -->
<div class="modal fade" id="addMenuChoiceModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="addMenuChoiceForm" method="POST" action="{{ route('dashboard.cms.menu-choice.store') }}">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="mdi mdi-plus me-2"></i>Tambah Menu Choice</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Mode Tampilan <span class="text-danger">*</span></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="use_main_menu" id="add-mode-modal" value="1" checked>
                            <label class="form-check-label" for="add-mode-modal">
                                <strong>Dengan Main Menu Modal</strong> - Tampilkan tombol main menu yang membuka modal berisi pilihan menu
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="use_main_menu" id="add-mode-direct" value="0">
                            <label class="form-check-label" for="add-mode-direct">
                                <strong>Langsung Tampil</strong> - Tampilkan semua menu langsung tanpa modal
                            </label>
                        </div>
                    </div>

                    <div class="mb-3" id="add-main-menu-title-group">
                        <label class="form-label">Judul Main Menu <span class="text-danger">*</span></label>
                        <input type="text" name="main_menu_title" id="add-main-menu-title" class="form-control" placeholder="Menu SIGAP Award 2025" maxlength="100">
                        <small class="text-muted">Judul yang ditampilkan pada tombol main menu modal</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Menu Items <span class="text-danger">*</span></label>
                        <div id="add-menu-items-container">
                            <!-- Menu items will be added here dynamically -->
                        </div>
                        <button type="button" class="btn btn-sm btn-success mt-2" id="add-menu-item-btn">
                            <i class="mdi mdi-plus me-1"></i>Tambah Menu Item
                        </button>
                        <input type="hidden" name="menu_items" id="add-menu-items-json">
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="add-is-active" checked>
                        <label class="form-check-label" for="add-is-active">Aktif (yang lain otomatis non-aktif)</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Edit Menu Choice -->
<div class="modal fade" id="editMenuChoiceModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="editMenuChoiceForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title"><i class="mdi mdi-pencil me-2"></i>Edit Menu Choice</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Mode Tampilan <span class="text-danger">*</span></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="use_main_menu" id="edit-mode-modal" value="1">
                            <label class="form-check-label" for="edit-mode-modal">
                                <strong>Dengan Main Menu Modal</strong> - Tampilkan tombol main menu yang membuka modal berisi pilihan menu
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="use_main_menu" id="edit-mode-direct" value="0">
                            <label class="form-check-label" for="edit-mode-direct">
                                <strong>Langsung Tampil</strong> - Tampilkan semua menu langsung tanpa modal
                            </label>
                        </div>
                    </div>

                    <div class="mb-3" id="edit-main-menu-title-group">
                        <label class="form-label">Judul Main Menu <span class="text-danger">*</span></label>
                        <input type="text" name="main_menu_title" id="edit-main-menu-title" class="form-control" maxlength="100">
                        <small class="text-muted">Judul yang ditampilkan pada tombol main menu modal</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Menu Items <span class="text-danger">*</span></label>
                        <div id="edit-menu-items-container">
                            <!-- Menu items will be added here dynamically -->
                        </div>
                        <button type="button" class="btn btn-sm btn-success mt-2" id="edit-menu-item-btn">
                            <i class="mdi mdi-plus me-1"></i>Tambah Menu Item
                        </button>
                        <input type="hidden" name="menu_items" id="edit-menu-items-json">
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="edit-is-active">
                        <label class="form-check-label" for="edit-is-active">Aktif (yang lain otomatis non-aktif)</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Template for menu item -->
<template id="menu-item-template">
    <div class="card mb-2 menu-item">
        <div class="card-body p-3">
            <div class="row align-items-start">
                <div class="col-md-3">
                    <label class="form-label mb-1">Judul Menu <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm menu-title" placeholder="Upload Poster" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label mb-1">Tipe <span class="text-danger">*</span></label>
                    <select class="form-select form-select-sm menu-type" required>
                        <option value="link">Direct Link</option>
                        <option value="modal">Modal (Sub-menu)</option>
                    </select>
                </div>
                <div class="col-md-3 menu-link-field">
                    <label class="form-label mb-1">Link URL <span class="text-danger menu-link-required">*</span></label>
                    <input type="text" class="form-control form-control-sm menu-link" placeholder="https://example.com atau /poster-criteria">
                </div>
                <div class="col-md-2">
                    <label class="form-label mb-1">Icon</label>
                    <input type="text" class="form-control form-control-sm menu-icon" placeholder="🖼️">
                </div>
                <div class="col-md-1 text-end">
                    <label class="form-label mb-1 d-block">&nbsp;</label>
                    <button type="button" class="btn btn-sm btn-danger remove-menu-item">
                        <i class="mdi mdi-delete"></i>
                    </button>
                </div>
                <div class="col-md-1 text-end">
                    <label class="form-label mb-1 d-block">&nbsp;</label>
                    <button type="button" class="btn btn-sm btn-info add-submenu-btn" style="display: none;">
                        <i class="mdi mdi-plus"></i>
                    </button>
                </div>
            </div>
            <!-- Sub-menu container (for modal type) -->
            <div class="submenu-container mt-2" style="display: none;">
                <div class="ps-4 border-start border-3 border-info">
                    <label class="form-label mb-2"><strong>Sub-Menu Items:</strong></label>
                    <div class="submenu-items">
                        <!-- Sub-menu items will be added here -->
                    </div>
                    <button type="button" class="btn btn-sm btn-success add-submenu-item mt-1">
                        <i class="mdi mdi-plus me-1"></i>Tambah Sub-Menu
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Template for sub-menu item -->
<template id="submenu-item-template">
    <div class="row mb-2 submenu-item align-items-center">
        <div class="col-md-5">
            <input type="text" class="form-control form-control-sm submenu-title" placeholder="Upload Poster BPKH" required>
        </div>
        <div class="col-md-6">
            <input type="url" class="form-control form-control-sm submenu-link" placeholder="https://example.com/bpkh" required>
        </div>
        <div class="col-md-1 text-end">
            <button type="button" class="btn btn-sm btn-danger remove-submenu-item">
                <i class="mdi mdi-close"></i>
            </button>
        </div>
    </div>
</template>
