<!-- Modal: Add Box Counter -->
<div class="modal fade" id="addBoxCounterModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('dashboard.cms.card-box.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="mdi mdi-plus-circle me-2"></i>Tambah Card Box</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required minlength="4" maxlength="100" placeholder="Vote Pengelola IGT Terbaik 2025">
                        <small class="text-muted">Minimal 4, maksimal 100 karakter</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="3" required minlength="10" maxlength="500" placeholder="Deskripsi singkat"></textarea>
                        <small class="text-muted">Minimal 10, maksimal 500 karakter</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipe Konten <span class="text-danger">*</span></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="content_type" id="add-type-text" value="text_only" checked>
                            <label class="form-check-label" for="add-type-text">Text Only (Hanya Judul & Deskripsi)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="content_type" id="add-type-link" value="link">
                            <label class="form-check-label" for="add-type-link">Link URL (dengan Tombol)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="content_type" id="add-type-modal" value="modal">
                            <label class="form-check-label" for="add-type-modal">Modal (dengan Tombol)</label>
                        </div>
                    </div>

                    <div class="mb-3" id="add-button-group" style="display: none;">
                        <label for="add-button-text" class="form-label">Teks Tombol <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="add-button-text" name="button_text" value="Lanjut">
                    </div>

                    <div class="mb-3" id="add-link-group" style="display: none;">
                        <label for="add-link-url" class="form-label">Link URL <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" id="add-link-url" name="link_url" placeholder="https://example.com">
                        <small class="text-muted">Link akan dibuka di tab baru</small>
                    </div>

                    <div class="mb-3" id="add-modal-group" style="display: none;">
                        <label for="add-modal-content" class="form-label">Konten Modal <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="add-modal-content" name="modal_content" rows="5" placeholder="Isi konten modal..."></textarea>
                        <small class="text-muted">Isi konten modal yang akan ditampilkan</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" name="order" class="form-control" min="1" placeholder="Kosongkan untuk urutan otomatis">
                        <small class="text-muted">Kosongkan untuk menambahkan di urutan terakhir</small>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" checked>
                        <label class="form-check-label">Aktif</label>
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

<!-- Modal: Edit Box Counter -->
<div class="modal fade" id="editBoxCounterModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editBoxCounterForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title"><i class="mdi mdi-pencil me-2"></i>Edit Card Box</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" id="edit_title" name="title" class="form-control" required minlength="4" maxlength="100">
                        <small class="text-muted">Minimal 4, maksimal 100 karakter</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea id="edit_description" name="description" class="form-control" rows="3" required minlength="10" maxlength="500"></textarea>
                        <small class="text-muted">Minimal 10, maksimal 500 karakter</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipe Konten <span class="text-danger">*</span></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="content_type" id="edit-type-text" value="text_only">
                            <label class="form-check-label" for="edit-type-text">Text Only (Hanya Judul & Deskripsi)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="content_type" id="edit-type-link" value="link">
                            <label class="form-check-label" for="edit-type-link">Link URL (dengan Tombol)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="content_type" id="edit-type-modal" value="modal">
                            <label class="form-check-label" for="edit-type-modal">Modal (dengan Tombol)</label>
                        </div>
                    </div>

                    <div class="mb-3" id="edit-button-group" style="display: none;">
                        <label for="edit_button_text" class="form-label">Teks Tombol <span class="text-danger">*</span></label>
                        <input type="text" id="edit_button_text" name="button_text" class="form-control" maxlength="50">
                    </div>

                    <div class="mb-3" id="edit-link-group" style="display: none;">
                        <label for="edit_link_url" class="form-label">Link URL <span class="text-danger">*</span></label>
                        <input type="url" id="edit_link_url" name="link_url" class="form-control" placeholder="https://example.com">
                        <small class="text-muted">Link akan dibuka di tab baru</small>
                    </div>

                    <div class="mb-3" id="edit-modal-group" style="display: none;">
                        <label for="edit_modal_content" class="form-label">Konten Modal <span class="text-danger">*</span></label>
                        <textarea id="edit_modal_content" name="modal_content" class="form-control" rows="5" placeholder="Isi konten modal..."></textarea>
                        <small class="text-muted">Isi konten modal yang akan ditampilkan</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" id="edit_order" name="order" class="form-control" min="1">
                        <small class="text-muted">Nomor urutan tampilan</small>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active">
                        <label class="form-check-label">Aktif</label>
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
