<script>
$(document).ready(function() {
    // Toggle content type fields for ADD modal
    $('input[name="content_type"]').on('change', function() {
        const contentType = $(this).val();
        const modalId = $(this).closest('.modal').attr('id');
        const prefix = modalId === 'addBoxCounterModal' ? 'add' : 'edit';
        
        // Hide all conditional fields
        $(`#${prefix}-button-group`).hide();
        $(`#${prefix}-link-group`).hide();
        $(`#${prefix}-modal-group`).hide();
        
        // Show relevant fields based on content type
        if (contentType === 'link') {
            $(`#${prefix}-button-group`).show();
            $(`#${prefix}-link-group`).show();
        } else if (contentType === 'modal') {
            $(`#${prefix}-button-group`).show();
            $(`#${prefix}-modal-group`).show();
        }
        // text_only: no additional fields needed
    });

    // Edit button click
    $('.btn-edit').on('click', function() {
        const $btn = $(this);
        const data = $btn.data();
        
        $('#editBoxCounterForm').attr('action', `/cms/card-box/${data.id}`);
        $('#edit_title').val(data.title);
        $('#edit_description').val(data.description);
        $('#edit_button_text').val(data.buttonText || '');
        $('#edit_link_url').val(data.linkUrl || '');
        $('#edit_modal_content').val(data.modalContent || '');
        $('#edit_order').val(data.order + 1);
        $('#edit_is_active').prop('checked', data.isActive === 1);

        // Get content type - jQuery converts data-content-type to contentType
        const contentType = data.contentType || $btn.attr('data-content-type') || 'text_only';
        
        // Uncheck all first
        $('input[name="content_type"]').prop('checked', false);
        
        // Check radio button berdasarkan content type
        if (contentType === 'text_only') {
            $('#edit-type-text').prop('checked', true);
        } else if (contentType === 'link') {
            $('#edit-type-link').prop('checked', true);
        } else if (contentType === 'modal') {
            $('#edit-type-modal').prop('checked', true);
        }
        
        // Trigger change untuk show/hide fields yang sesuai
        $('input[name="content_type"]:checked').trigger('change');

        $('#editBoxCounterModal').modal('show');
    });

    // Delete button click
    $('.btn-delete').on('click', function() {
        const id = $(this).data('id');
        const title = $(this).data('title');

        Swal.fire({
            title: 'Hapus Card Box?',
            html: `Apakah Anda yakin ingin menghapus card box <strong>${title}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/cms/card-box/${id}`,
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

    // Sortable
    const sortable = new Sortable(document.getElementById('sortable-card-boxes'), {
        animation: 150,
        handle: '.drag-handle',
        ghostClass: 'sortable-ghost',
        onEnd: function(evt) {
            const orders = [];
            $('#sortable-card-boxes tr.card-box-item').each(function(index) {
                orders.push({
                    id: $(this).data('id'),
                    order: index
                });
            });

            $.ajax({
                url: '{{ route("dashboard.cms.card-box.update-order") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    orders: orders
                },
                success: function(response) {
                    toastr.success(response.message || 'Urutan berhasil diupdate');
                    setTimeout(() => location.reload(), 1000);
                },
                error: function() {
                    toastr.error('Gagal mengupdate urutan');
                }
            });
        }
    });
});
</script>
