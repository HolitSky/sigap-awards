<!-- Modal: Add Pemenang -->
<div class="modal fade" id="addPemenangModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="addPemenangForm" method="POST" action="{{ route('dashboard.cms.pemenang-sigap.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="mdi mdi-plus me-2"></i>Tambah Pemenang</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori" id="add-kategori" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="poster_terbaik">Poster Terbaik</option>
                                <option value="poster_favorit">Poster Favorit</option>
                                <option value="pengelola_igt_terbaik">Pengelola IGT Terbaik</option>
                                <option value="inovasi_bpkh_terbaik">Inovasi BPKH Terbaik</option>
                                <option value="inovasi_produsen_terbaik">Inovasi Produsen DG Terbaik</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipe Peserta <span class="text-danger">*</span></label>
                            <select name="tipe_peserta" id="add-tipe-peserta" class="form-select" required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="bpkh">BPKH</option>
                                <option value="produsen">Produsen</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Nama Pemenang <span class="text-danger">*</span></label>
                            <select name="nama_pemenang" id="add-nama-pemenang" class="form-select select2" required>
                                <option value="">-- Pilih Tipe Peserta Terlebih Dahulu --</option>
                            </select>
                            <small class="text-muted">Pilih tipe peserta terlebih dahulu</small>
                        </div>

                        <div class="col-md-12 mb-3" id="add-nama-petugas-group" style="display: none;">
                            <label class="form-label">Nama Petugas <span class="text-danger">*</span></label>
                            <input type="text" name="nama_petugas" id="add-nama-petugas" class="form-control" placeholder="Masukkan nama petugas">
                            <small class="text-muted">Khusus untuk kategori Pengelola IGT Terbaik</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Juara <span class="text-danger">*</span></label>
                            <select name="juara" id="add-juara" class="form-select" required>
                                <option value="">-- Pilih Juara --</option>
                                <option value="juara_1">Juara 1</option>
                                <option value="juara_2">Juara 2</option>
                                <option value="juara_3">Juara 3</option>
                                <option value="juara_harapan">Juara Harapan</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Urutan Tampil</label>
                            <input type="number" name="urutan" id="add-urutan" class="form-control" value="0" min="0">
                            <small class="text-muted">Urutan tampil di halaman pemenang</small>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="add-deskripsi" class="form-control" rows="3" placeholder="Deskripsi singkat tentang pencapaian..."></textarea>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Foto Pemenang</label>
                            <input type="file" name="foto" id="add-foto" class="form-control" accept="image/jpeg,image/png,image/jpg">
                            <small class="text-muted">Format: JPG, PNG. Max: 2MB</small>
                            <div id="add-preview" class="mt-2" style="display: none;">
                                <img src="" alt="Preview" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="add-is-active" value="1" checked>
                                <label class="form-check-label" for="add-is-active">Aktif</label>
                            </div>
                        </div>
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

<!-- Modal: Edit Pemenang -->
<div class="modal fade" id="editPemenangModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editPemenangForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title"><i class="mdi mdi-pencil me-2"></i>Edit Pemenang</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori" id="edit-kategori" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="poster_terbaik">Poster Terbaik</option>
                                <option value="poster_favorit">Poster Favorit</option>
                                <option value="pengelola_igt_terbaik">Pengelola IGT Terbaik</option>
                                <option value="inovasi_bpkh_terbaik">Inovasi BPKH Terbaik</option>
                                <option value="inovasi_produsen_terbaik">Inovasi Produsen DG Terbaik</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipe Peserta <span class="text-danger">*</span></label>
                            <select name="tipe_peserta" id="edit-tipe-peserta" class="form-select" required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="bpkh">BPKH</option>
                                <option value="produsen">Produsen</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Nama Pemenang <span class="text-danger">*</span></label>
                            <select name="nama_pemenang" id="edit-nama-pemenang" class="form-select select2" required>
                                <option value="">-- Pilih Tipe Peserta Terlebih Dahulu --</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3" id="edit-nama-petugas-group" style="display: none;">
                            <label class="form-label">Nama Petugas <span class="text-danger">*</span></label>
                            <input type="text" name="nama_petugas" id="edit-nama-petugas" class="form-control" placeholder="Masukkan nama petugas">
                            <small class="text-muted">Khusus untuk kategori Pengelola IGT Terbaik</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Juara <span class="text-danger">*</span></label>
                            <select name="juara" id="edit-juara" class="form-select" required>
                                <option value="">-- Pilih Juara --</option>
                                <option value="juara_1">Juara 1</option>
                                <option value="juara_2">Juara 2</option>
                                <option value="juara_3">Juara 3</option>
                                <option value="juara_harapan">Juara Harapan</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Urutan Tampil</label>
                            <input type="number" name="urutan" id="edit-urutan" class="form-control" value="0" min="0">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="edit-deskripsi" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Foto Pemenang</label>
                            <input type="file" name="foto" id="edit-foto" class="form-control" accept="image/jpeg,image/png,image/jpg">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
                            <div id="edit-preview" class="mt-2" style="display: none;">
                                <img src="" alt="Preview" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                            <div id="edit-current-foto" class="mt-2" style="display: none;">
                                <p class="mb-1"><strong>Foto Saat Ini:</strong></p>
                                <img src="" alt="Current Photo" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="edit-is-active" value="1">
                                <label class="form-check-label" for="edit-is-active">Aktif</label>
                            </div>
                        </div>
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
