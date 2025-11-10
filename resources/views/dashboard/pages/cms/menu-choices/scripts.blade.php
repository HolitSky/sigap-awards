@push('scripts')
<script>
$(document).ready(function() {
    // Toggle main menu title field based on mode
    $('input[name="use_main_menu"]').on('change', function() {
        const useMainMenu = $(this).val() === '1';
        const modalId = $(this).closest('.modal').attr('id');
        const prefix = modalId === 'addMenuChoiceModal' ? 'add' : 'edit';
        
        if (useMainMenu) {
            $(`#${prefix}-main-menu-title-group`).show();
            $(`#${prefix}-main-menu-title`).prop('required', true);
        } else {
            $(`#${prefix}-main-menu-title-group`).hide();
            $(`#${prefix}-main-menu-title`).prop('required', false);
        }
    });

    // Toggle menu type fields
    $(document).on('change', '.menu-type', function() {
        const $menuItem = $(this).closest('.menu-item');
        const type = $(this).val();
        
        if (type === 'modal') {
            $menuItem.find('.menu-link-field').hide();
            $menuItem.find('.menu-link').prop('required', false).val('');
            $menuItem.find('.menu-link-required').hide();
            $menuItem.find('.submenu-container').show();
            $menuItem.find('.add-submenu-btn').show();
        } else {
            $menuItem.find('.menu-link-field').show();
            $menuItem.find('.menu-link').prop('required', true);
            $menuItem.find('.menu-link-required').show();
            $menuItem.find('.submenu-container').hide();
            $menuItem.find('.add-submenu-btn').hide();
        }
    });

    // Add menu item
    function addMenuItem(container, data = {}) {
        const template = document.getElementById('menu-item-template');
        const clone = template.content.cloneNode(true);
        
        const $clone = $(clone);
        if (data.title) $clone.find('.menu-title').val(data.title);
        if (data.link) $clone.find('.menu-link').val(data.link);
        if (data.icon) $clone.find('.menu-icon').val(data.icon);
        
        const itemType = data.type || 'link';
        $clone.find('.menu-type').val(itemType);
        
        if (itemType === 'modal') {
            $clone.find('.menu-link-field').hide();
            $clone.find('.menu-link').prop('required', false).val('');
            $clone.find('.menu-link-required').hide();
            $clone.find('.submenu-container').show();
            $clone.find('.add-submenu-btn').show();
            
            // Add sub-menu items
            if (data.submenu && Array.isArray(data.submenu)) {
                const submenuContainer = $clone.find('.submenu-items')[0];
                data.submenu.forEach(subitem => {
                    addSubmenuItem(submenuContainer, subitem);
                });
            }
        } else {
            // Ensure link field is visible for link type
            $clone.find('.menu-link-field').show();
            $clone.find('.menu-link').prop('required', true);
            $clone.find('.menu-link-required').show();
        }
        
        container.appendChild(clone);
    }

    // Add sub-menu item
    function addSubmenuItem(container, data = {}) {
        const template = document.getElementById('submenu-item-template');
        const clone = template.content.cloneNode(true);
        
        if (data.title) clone.querySelector('.submenu-title').value = data.title;
        if (data.link) clone.querySelector('.submenu-link').value = data.link;
        
        container.appendChild(clone);
    }

    // Add sub-menu item button
    $(document).on('click', '.add-submenu-item', function() {
        const container = $(this).siblings('.submenu-items')[0];
        addSubmenuItem(container);
    });

    // Remove menu item
    $(document).on('click', '.remove-menu-item', function() {
        $(this).closest('.menu-item').remove();
    });

    // Remove sub-menu item
    $(document).on('click', '.remove-submenu-item', function() {
        $(this).closest('.submenu-item').remove();
    });

    // Add menu item button - ADD modal
    $('#add-menu-item-btn').on('click', function() {
        const container = document.getElementById('add-menu-items-container');
        addMenuItem(container);
    });

    // Add menu item button - EDIT modal
    $('#edit-menu-item-btn').on('click', function() {
        const container = document.getElementById('edit-menu-items-container');
        addMenuItem(container);
    });

    // Collect menu items and set JSON before submit - ADD
    $('#addMenuChoiceForm').on('submit', function(e) {
        const menuItems = [];
        $('#add-menu-items-container .menu-item').each(function() {
            const $item = $(this);
            const title = $item.find('.menu-title').val();
            const type = $item.find('.menu-type').val();
            const link = $item.find('.menu-link').val();
            const icon = $item.find('.menu-icon').val();
            
            if (title) {
                const menuItem = { 
                    title, 
                    type: type || 'link',
                    icon: icon || null 
                };
                
                if (type === 'modal') {
                    // Collect sub-menu items
                    const submenu = [];
                    $item.find('.submenu-item').each(function() {
                        const subTitle = $(this).find('.submenu-title').val();
                        const subLink = $(this).find('.submenu-link').val();
                        if (subTitle && subLink) {
                            submenu.push({ title: subTitle, link: subLink });
                        }
                    });
                    menuItem.submenu = submenu;
                    menuItem.link = null;
                } else {
                    menuItem.link = link;
                }
                
                menuItems.push(menuItem);
            }
        });
        
        if (menuItems.length === 0) {
            e.preventDefault();
            Swal.fire('Error!', 'Minimal harus ada 1 menu item', 'error');
            return false;
        }
        
        $('#add-menu-items-json').val(JSON.stringify(menuItems));
    });

    // Collect menu items and set JSON before submit - EDIT
    $('#editMenuChoiceForm').on('submit', function(e) {
        const menuItems = [];
        $('#edit-menu-items-container .menu-item').each(function() {
            const $item = $(this);
            const title = $item.find('.menu-title').val();
            const type = $item.find('.menu-type').val();
            const link = $item.find('.menu-link').val();
            const icon = $item.find('.menu-icon').val();
            
            if (title) {
                const menuItem = { 
                    title, 
                    type: type || 'link',
                    icon: icon || null 
                };
                
                if (type === 'modal') {
                    // Collect sub-menu items
                    const submenu = [];
                    $item.find('.submenu-item').each(function() {
                        const subTitle = $(this).find('.submenu-title').val();
                        const subLink = $(this).find('.submenu-link').val();
                        if (subTitle && subLink) {
                            submenu.push({ title: subTitle, link: subLink });
                        }
                    });
                    menuItem.submenu = submenu;
                    menuItem.link = null;
                } else {
                    menuItem.link = link;
                }
                
                menuItems.push(menuItem);
            }
        });
        
        if (menuItems.length === 0) {
            e.preventDefault();
            Swal.fire('Error!', 'Minimal harus ada 1 menu item', 'error');
            return false;
        }
        
        $('#edit-menu-items-json').val(JSON.stringify(menuItems));
    });

    // Initialize add modal with one empty menu item
    $('#addMenuChoiceModal').on('shown.bs.modal', function() {
        const container = document.getElementById('add-menu-items-container');
        container.innerHTML = '';
        addMenuItem(container);
    });

    // Edit button click
    $('.btn-edit').on('click', function() {
        const $btn = $(this);
        const data = $btn.data();
        
        $('#editMenuChoiceForm').attr('action', `/cms/menu-choice/${data.id}`);
        $('#edit-main-menu-title').val(data.mainMenuTitle || '');
        $('#edit-is-active').prop('checked', data.isActive === 1);

        // Set mode radio
        if (data.useMainMenu === 1) {
            $('#edit-mode-modal').prop('checked', true);
            $('#edit-main-menu-title-group').show();
            $('#edit-main-menu-title').prop('required', true);
        } else {
            $('#edit-mode-direct').prop('checked', true);
            $('#edit-main-menu-title-group').hide();
            $('#edit-main-menu-title').prop('required', false);
        }

        // Load menu items
        const container = document.getElementById('edit-menu-items-container');
        container.innerHTML = '';
        
        try {
            const menuItems = typeof data.menuItems === 'string' 
                ? JSON.parse(data.menuItems) 
                : data.menuItems;
            
            if (Array.isArray(menuItems) && menuItems.length > 0) {
                menuItems.forEach(item => {
                    addMenuItem(container, item);
                });
            } else {
                addMenuItem(container);
            }
        } catch (e) {
            console.error('Error parsing menu items:', e);
            addMenuItem(container);
        }

        $('#editMenuChoiceModal').modal('show');
    });

    // Delete button click
    $('.btn-delete').on('click', function() {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Hapus Menu Choice?',
            text: 'Apakah Anda yakin ingin menghapus menu choice ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/cms/menu-choice/${id}`,
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
});
</script>
@endpush
