@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%',
        dropdownParent: $('.modal.show')
    });

    // Handle kategori change for ADD modal - show/hide nama petugas
    $('#add-kategori').on('change', function() {
        const kategori = $(this).val();
        if (kategori === 'pengelola_igt_terbaik') {
            $('#add-nama-petugas-group').show();
            $('#add-nama-petugas').prop('required', true);
        } else {
            $('#add-nama-petugas-group').hide();
            $('#add-nama-petugas').prop('required', false).val('');
        }
    });

    // Handle kategori change for EDIT modal - show/hide nama petugas
    $('#edit-kategori').on('change', function() {
        const kategori = $(this).val();
        if (kategori === 'pengelola_igt_terbaik') {
            $('#edit-nama-petugas-group').show();
            $('#edit-nama-petugas').prop('required', true);
        } else {
            $('#edit-nama-petugas-group').hide();
            $('#edit-nama-petugas').prop('required', false).val('');
        }
    });

    // Handle tipe peserta change for ADD modal
    $('#add-tipe-peserta').on('change', function() {
        const tipe = $(this).val();
        const $select = $('#add-nama-pemenang');

        $select.html('<option value="">Loading...</option>');

        if (tipe) {
            $.ajax({
                url: '{{ route("dashboard.cms.pemenang-sigap.peserta-list") }}',
                type: 'GET',
                data: { tipe: tipe },
                success: function(data) {
                    $select.html('<option value="">-- Pilih Nama --</option>');
                    data.forEach(function(item) {
                        $select.append(`<option value="${item.id}">${item.text}</option>`);
                    });
                    $select.select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        dropdownParent: $('#addPemenangModal')
                    });
                },
                error: function() {
                    $select.html('<option value="">Error loading data</option>');
                }
            });
        } else {
            $select.html('<option value="">-- Pilih Tipe Peserta Terlebih Dahulu --</option>');
        }
    });

    // Handle tipe peserta change for EDIT modal
    $('#edit-tipe-peserta').on('change', function() {
        const tipe = $(this).val();
        const $select = $('#edit-nama-pemenang');
        const currentValue = $select.data('current-value');

        $select.html('<option value="">Loading...</option>');

        if (tipe) {
            $.ajax({
                url: '{{ route("dashboard.cms.pemenang-sigap.peserta-list") }}',
                type: 'GET',
                data: { tipe: tipe },
                success: function(data) {
                    $select.html('<option value="">-- Pilih Nama --</option>');
                    data.forEach(function(item) {
                        const selected = item.id === currentValue ? 'selected' : '';
                        $select.append(`<option value="${item.id}" ${selected}>${item.text}</option>`);
                    });
                    $select.select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        dropdownParent: $('#editPemenangModal')
                    });
                },
                error: function() {
                    $select.html('<option value="">Error loading data</option>');
                }
            });
        } else {
            $select.html('<option value="">-- Pilih Tipe Peserta Terlebih Dahulu --</option>');
        }
    });

    // Preview image on file select - ADD
    $('#add-foto').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#add-preview').show().find('img').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        } else {
            $('#add-preview').hide();
        }
    });

    // Preview image on file select - EDIT
    $('#edit-foto').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#edit-preview').show().find('img').attr('src', e.target.result);
                $('#edit-current-foto').hide();
            };
            reader.readAsDataURL(file);
        } else {
            $('#edit-preview').hide();
            $('#edit-current-foto').show();
        }
    });

    // Handle form submit - ADD
    $('#addPemenangForm').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitBtn = $(this).find('button[type="submit"]');

        submitBtn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin me-1"></i>Menyimpan...');

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: response.message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                let message = 'Terjadi kesalahan saat menyimpan data';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                // Show validation errors if exists
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    let errorList = '<ul class="text-start">';
                    $.each(xhr.responseJSON.errors, function(field, errors) {
                        $.each(errors, function(index, error) {
                            errorList += '<li>' + error + '</li>';
                        });
                    });
                    errorList += '</ul>';
                    message = errorList;
                }

                Swal.fire({
                    title: 'Error!',
                    html: message,
                    icon: 'error'
                });
                submitBtn.prop('disabled', false).html('Simpan');
            }
        });
    });

    // Handle form submit - EDIT
    $('#editPemenangForm').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitBtn = $(this).find('button[type="submit"]');

        submitBtn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin me-1"></i>Mengupdate...');

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: response.message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                let message = 'Terjadi kesalahan saat mengupdate data';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                // Show validation errors if exists
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    let errorList = '<ul class="text-start">';
                    $.each(xhr.responseJSON.errors, function(field, errors) {
                        $.each(errors, function(index, error) {
                            errorList += '<li>' + error + '</li>';
                        });
                    });
                    errorList += '</ul>';
                    message = errorList;
                }

                Swal.fire({
                    title: 'Error!',
                    html: message,
                    icon: 'error'
                });
                submitBtn.prop('disabled', false).html('Update');
            }
        });
    });

    // Edit button click
    $('.btn-edit').on('click', function() {
        const $btn = $(this);
        const data = $btn.data();

        $('#editPemenangForm').attr('action', `/cms/pemenang-sigap/${data.id}`);
        $('#edit-kategori').val(data.kategori);
        $('#edit-tipe-peserta').val(data.tipePeserta);
        $('#edit-juara').val(data.juara);
        $('#edit-urutan').val(data.urutan);
        $('#edit-deskripsi').val(data.deskripsi);
        $('#edit-is-active').prop('checked', data.isActive === 1);

        // Handle nama petugas
        $('#edit-nama-petugas').val(data.namaPetugas || '');

        // Trigger kategori change to show/hide nama petugas field
        $('#edit-kategori').trigger('change');

        // Store current nama pemenang for later selection
        $('#edit-nama-pemenang').data('current-value', data.namaPemenang);

        // Trigger tipe peserta change to load nama pemenang options
        $('#edit-tipe-peserta').trigger('change');

        // Show current photo if exists
        if (data.fotoPath) {
            $('#edit-current-foto').show().find('img').attr('src', `/storage/${data.fotoPath}`);
        } else {
            $('#edit-current-foto').hide();
        }
        $('#edit-preview').hide();

        $('#editPemenangModal').modal('show');
    });

    // Delete button click
    $('.btn-delete').on('click', function() {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Hapus Data Pemenang?',
            text: 'Apakah Anda yakin ingin menghapus data pemenang ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/cms/pemenang-sigap/${id}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    },
                    error: function() {
                        Swal.fire('Error!', 'Terjadi kesalahan saat menghapus data', 'error');
                    }
                });
            }
        });
    });

    // Reset form when modal is closed
    $('#addPemenangModal').on('hidden.bs.modal', function() {
        $('#addPemenangForm')[0].reset();
        $('#add-preview').hide();
        $('#add-nama-pemenang').html('<option value="">-- Pilih Tipe Peserta Terlebih Dahulu --</option>');
    });

    $('#editPemenangModal').on('hidden.bs.modal', function() {
        $('#editPemenangForm')[0].reset();
        $('#edit-preview').hide();
        $('#edit-current-foto').hide();
    });
});
</script>
@endpush
