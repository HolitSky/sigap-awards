@extends('dashboard.layouts.app')
@section('title', 'User Management')

@push('styles')
<!-- GLightbox CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />

<style>
    .avatar-sm {
        width: 40px;
        height: 40px;
        object-fit: cover;
        cursor: pointer;
        transition: transform 0.2s;
    }
    
    .avatar-sm:hover {
        transform: scale(1.1);
    }

    .avatar-lg {
        width: 80px;
        height: 80px;
        object-fit: cover;
    }

    .dropdown-menu {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .modal-header.bg-primary .btn-close-white,
    .modal-header.bg-success .btn-close-white {
        filter: brightness(0) invert(1);
    }

    #usersTable_wrapper .dataTables_filter input {
        margin-left: 0.5em;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }

    /* DataTables Length Menu Styling */
    #usersTable_wrapper .dataTables_length select {
        min-width: 70px;
        height: 38px;
        padding: 0.375rem 2rem 0.375rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 400;
        line-height: 1.5;
        color: #212529 !important;
        background-color: #fff !important;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        appearance: auto;
        -webkit-appearance: menulist;
        -moz-appearance: menulist;
        text-align: left;
        text-indent: 0px;
    }

    #usersTable_wrapper .dataTables_length select option {
        color: #212529;
        background-color: #fff;
        padding: 5px;
    }

    #usersTable_wrapper .dataTables_length {
        margin-bottom: 1rem;
    }

    #usersTable_wrapper .dataTables_length label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: #495057;
    }
</style>
@endpush

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">User Management</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
                            <li class="breadcrumb-item active">User Management</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">User List</h4>
                            @if(auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin')
                            <button type="button" class="btn btn-primary btn-sm" id="addUserBtn">
                                <i class="mdi mdi-plus me-1"></i> Add New User
                            </button>
                            @endif
                        </div>

                        <div class="table-responsive">
                            <table id="usersTable" class="table table-bordered dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">No</th>
                                        <th style="width: 60px;">Image</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Joined Date</th>
                                        <th style="width: 100px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="detailModalLabel">
                    <i class="mdi mdi-account-details me-1"></i> User Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <a href="" id="detailProfileImageLink" class="glightbox" data-glightbox="title: User Profile">
                        <img id="detailProfileImage" src="" alt="Profile" class="avatar-lg rounded-circle img-thumbnail" style="cursor: pointer;">
                    </a>
                </div>

                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="fw-medium" style="width: 35%;">ID</td>
                            <td id="detailId">-</td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Name</td>
                            <td id="detailName">-</td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Email</td>
                            <td id="detailEmail">-</td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Role</td>
                            <td>
                                <span id="detailRole" class="badge badge-soft-primary">-</span>
                            </td>
                        </tr>
                        @if(auth()->user()->role === 'superadmin')
                        <tr id="detailPasswordRow">
                            <td class="fw-medium">Password</td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="password" id="detailPassword" class="form-control" readonly value="">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordDetail">
                                        <i class="mdi mdi-eye-outline"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Encrypted password hash</small>
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td class="fw-medium">Joined Date</td>
                            <td id="detailCreatedAt">-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="editModalLabel">
                    <i class="mdi mdi-pencil me-1"></i> Edit User
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUserForm">
                <div class="modal-body">
                    <input type="hidden" id="editUserId">

                    <div class="mb-3">
                        <label for="editName" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editName" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="editEmail" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="editPassword" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="editPassword" placeholder="Leave blank to keep current password">
                            <button class="btn btn-outline-secondary" type="button" id="togglePasswordEdit">
                                <i class="mdi mdi-eye-outline"></i>
                            </button>
                        </div>
                        <small class="text-muted">Min. 8 characters. Leave blank to keep current password.</small>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="editRole" class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-select" id="editRole" required>
                            {{-- <option value="peserta">Peserta</option> --}}
                            <option value="panitia">Juri</option>
                            @if(auth()->user()->role === 'superadmin')
                            <option value="admin">Admin</option>
                            <option value="superadmin">Superadmin</option>
                            @endif
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="mdi mdi-content-save me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addModalLabel">
                    <i class="mdi mdi-account-plus me-1"></i> Add New User
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addUserForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="addName" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="addName" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="addEmail" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="addEmail" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="addPassword" class="form-label">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="addPassword" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePasswordAdd">
                                <i class="mdi mdi-eye-outline"></i>
                            </button>
                        </div>
                        <small class="text-muted">Min. 8 characters</small>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="addRole" class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-select" id="addRole" required>
                            {{-- <option value="peserta">Peserta</option> --}}
                            <option value="panitia">Juri</option>
                            @if(auth()->user()->role === 'superadmin')
                            <option value="admin">Admin</option>
                            <option value="superadmin">Superadmin</option>
                            @endif
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-plus me-1"></i> Add User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Setup CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    const userRole = '{{ auth()->user()->role }}';
    const isSuperAdmin = userRole === 'superadmin';

    // Role display mapping (same as in controller)
    function getRoleDisplay(role) {
        const roleMap = {
            'panitia': 'Juri',
            'peserta': 'Peserta',
            'admin': 'Admin',
            'superadmin': 'Superadmin'
        };
        return roleMap[role] || role.charAt(0).toUpperCase() + role.slice(1);
    }

    // Initialize DataTable
    const table = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("dashboard.user-management.index") }}',
            type: 'GET'
        },
        columns: [
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'profile_image',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    const imgUrl = data
                        ? '{{ asset("storage") }}/' + data
                        : '{{ asset("dashboard-assets/images/users/user-dummy-img.jpg") }}';
                    return `<a href="${imgUrl}" class="glightbox" data-glightbox="title: ${row.name}; description: ${row.email}">
                                <img src="${imgUrl}" alt="${row.name}" class="avatar-sm rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                            </a>`;
                }
            },
            { data: 'name' },
            { data: 'email' },
            {
                data: 'role',
                render: function(data, type, row) {
                    const colors = {
                        'superadmin': 'danger',
                        'admin': 'warning',
                        'panitia': 'info',
                        'peserta': 'success'
                    };
                    const displayText = row.role_display || data.toUpperCase();
                    return `<span class="badge bg-${colors[data] || 'secondary'}">${displayText}</span>`;
                }
            },
            {
                data: 'created_at',
                render: function(data) {
                    return new Date(data).toLocaleDateString('id-ID', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                }
            },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    // Check if user is protected (from backend)
                    const isProtected = row.is_protected === true || row.is_protected === 1;

                    const editButton = isProtected ? '' : `
                        <li>
                            <a class="dropdown-item edit-user" href="javascript:void(0);" data-id="${data}">
                                <i class="mdi mdi-pencil me-1"></i> Edit
                            </a>
                        </li>
                    `;

                    const deleteButton = isProtected ? '' : `
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger delete-user" href="javascript:void(0);" data-id="${data}" data-name="${row.name}">
                                <i class="mdi mdi-delete me-1"></i> Delete
                            </a>
                        </li>
                    `;

                    return `
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-database-cog-outline"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item view-detail" href="javascript:void(0);" data-id="${data}">
                                        <i class="mdi mdi-eye-outline me-1"></i> Detail
                                    </a>
                                </li>
                                ${editButton}
                                ${deleteButton}
                            </ul>
                        </div>
                    `;
                }
            }
        ],
        order: [[5, 'desc']],  // Updated index for created_at column
        pageLength: 15,
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });

    // View Detail
    $('#usersTable').on('click', '.view-detail', function() {
        const userId = $(this).data('id');

        $.ajax({
            url: `/user-management/${userId}`,
            type: 'GET',
            success: function(response) {
                const imgUrl = response.profile_image
                    ? '{{ asset("storage") }}/' + response.profile_image
                    : '{{ asset("dashboard-assets/images/users/user-dummy-img.jpg") }}';

                $('#detailProfileImage').attr('src', imgUrl);
                $('#detailProfileImageLink').attr('href', imgUrl);
                $('#detailProfileImageLink').attr('data-glightbox', `title: ${response.name}; description: ${response.email}`);
                $('#detailId').text(response.id);
                $('#detailName').text(response.name);
                $('#detailEmail').text(response.email);

                // Use role_display if available, otherwise map manually
                const roleDisplay = getRoleDisplay(response.role);
                $('#detailRole').text(roleDisplay);

                if (isSuperAdmin && response.password) {
                    $('#detailPassword').val(response.password);
                    $('#detailPasswordRow').show();
                }

                const createdAt = new Date(response.created_at).toLocaleDateString('id-ID', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                $('#detailCreatedAt').text(createdAt);

                $('#detailModal').modal('show');
                
                // Reinitialize GLightbox for modal
                setTimeout(function() {
                    initGLightbox();
                }, 300);
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load user details'
                });
            }
        });
    });

    // Toggle password visibility in detail modal
    $('#togglePasswordDetail').on('click', function() {
        const input = $('#detailPassword');
        const icon = $(this).find('i');

        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('mdi-eye-outline').addClass('mdi-eye-off-outline');
        } else {
            input.attr('type', 'password');
            icon.removeClass('mdi-eye-off-outline').addClass('mdi-eye-outline');
        }
    });

    // Edit User
    $('#usersTable').on('click', '.edit-user', function() {
        const userId = $(this).data('id');

        $.ajax({
            url: `/user-management/${userId}`,
            type: 'GET',
            success: function(response) {
                $('#editUserId').val(response.id);
                $('#editName').val(response.name);
                $('#editEmail').val(response.email);
                $('#editRole').val(response.role);
                $('#editPassword').val('');

                // Clear previous validation
                $('#editUserForm .form-control, #editUserForm .form-select').removeClass('is-invalid');
                $('#editUserForm .invalid-feedback').text('');

                $('#editModal').modal('show');
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load user data'
                });
            }
        });
    });

    // Toggle password visibility in edit modal
    $('#togglePasswordEdit').on('click', function() {
        const input = $('#editPassword');
        const icon = $(this).find('i');

        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('mdi-eye-outline').addClass('mdi-eye-off-outline');
        } else {
            input.attr('type', 'password');
            icon.removeClass('mdi-eye-off-outline').addClass('mdi-eye-outline');
        }
    });

    // Submit Edit Form
    $('#editUserForm').on('submit', function(e) {
        e.preventDefault();

        const userId = $('#editUserId').val();
        const formData = {
            name: $('#editName').val(),
            email: $('#editEmail').val(),
            role: $('#editRole').val(),
            _method: 'PUT'
        };

        if ($('#editPassword').val()) {
            formData.password = $('#editPassword').val();
        }

        $.ajax({
            url: `/user-management/${userId}`,
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#editModal').modal('hide');
                table.ajax.reload();

                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;

                    // Clear previous errors
                    $('#editUserForm .form-control, #editUserForm .form-select').removeClass('is-invalid');
                    $('#editUserForm .invalid-feedback').text('');

                    // Show errors
                    $.each(errors, function(key, value) {
                        $(`#edit${key.charAt(0).toUpperCase() + key.slice(1)}`).addClass('is-invalid');
                        $(`#edit${key.charAt(0).toUpperCase() + key.slice(1)}`).siblings('.invalid-feedback').text(value[0]);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON.message || 'Failed to update user'
                    });
                }
            }
        });
    });

    // Add User Button
    $('#addUserBtn').on('click', function() {
        $('#addUserForm')[0].reset();
        $('#addUserForm .form-control, #addUserForm .form-select').removeClass('is-invalid');
        $('#addUserForm .invalid-feedback').text('');
        $('#addModal').modal('show');
    });

    // Toggle password visibility in add modal
    $('#togglePasswordAdd').on('click', function() {
        const input = $('#addPassword');
        const icon = $(this).find('i');

        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('mdi-eye-outline').addClass('mdi-eye-off-outline');
        } else {
            input.attr('type', 'password');
            icon.removeClass('mdi-eye-off-outline').addClass('mdi-eye-outline');
        }
    });

    // Submit Add Form
    $('#addUserForm').on('submit', function(e) {
        e.preventDefault();

        const formData = {
            name: $('#addName').val(),
            email: $('#addEmail').val(),
            password: $('#addPassword').val(),
            role: $('#addRole').val()
        };

        $.ajax({
            url: '{{ route("dashboard.user-management.store") }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#addModal').modal('hide');
                table.ajax.reload();

                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'User created successfully',
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;

                    // Clear previous errors
                    $('#addUserForm .form-control, #addUserForm .form-select').removeClass('is-invalid');
                    $('#addUserForm .invalid-feedback').text('');

                    // Show errors
                    $.each(errors, function(key, value) {
                        $(`#add${key.charAt(0).toUpperCase() + key.slice(1)}`).addClass('is-invalid');
                        $(`#add${key.charAt(0).toUpperCase() + key.slice(1)}`).siblings('.invalid-feedback').text(value[0]);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON.message || 'Failed to create user'
                    });
                }
            }
        });
    });

    // Delete User
    $('#usersTable').on('click', '.delete-user', function() {
        const userId = $(this).data('id');
        const userName = $(this).data('name');

        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete user: ${userName}`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/user-management/${userId}`,
                    type: 'DELETE',
                    success: function(response) {
                        table.ajax.reload();

                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON.message || 'Failed to delete user'
                        });
                    }
                });
            }
        });
    });

    // Initialize GLightbox after DataTable loaded
    table.on('draw', function() {
        initGLightbox();
    });

    // Initialize GLightbox function
    function initGLightbox() {
        if (typeof GLightbox !== 'undefined') {
            GLightbox({
                selector: '.glightbox',
                touchNavigation: true,
                loop: true,
                autoplayVideos: true
            });
        }
    }

    // Initial load
    initGLightbox();
});
</script>

<!-- GLightbox JS -->
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>

@endpush

