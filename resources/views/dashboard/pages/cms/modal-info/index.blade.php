@extends('dashboard.layouts.app')
@section('title', 'Manajemen Modal Info')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css"/>
<style>
    .modal-info-card {
        transition: all 0.3s ease;
    }
    .modal-info-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .badge-active {
        background-color: #28a745;
    }
    .badge-inactive {
        background-color: #dc3545;
    }
    .badge-reminder {
        background-color: #F3C275;
        color: #000;
    }
    .badge-welcome {
        background-color: #667eea;
        color: #fff;
    }
    
    /* Color picker styling */
    .color-picker-wrapper {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    .color-preview {
        width: 40px;
        height: 40px;
        border-radius: 6px;
        border: 2px solid #ddd;
        cursor: pointer;
        transition: all 0.2s;
    }
    .color-preview:hover {
        border-color: #667eea;
        transform: scale(1.05);
    }
    .pickr {
        display: inline-block;
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

        <!-- Modal Info Management -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-3">
                            <i class="mdi mdi-information-outline text-primary me-2"></i>Manajemen Modal Info
                        </h4>

                        <!-- Info Disclaimer -->
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-information-outline me-2"></i>
                            <strong>Info:</strong> Kelola 2 modal yang muncul di landing page:
                            <ul class="mb-0 mt-2">
                                <li><strong>Modal 1 (Welcome):</strong> Modal pertama yang muncul setelah 3 detik</li>
                                <li><strong>Modal 2 (Reminder):</strong> Modal kedua yang muncul setelah 6 detik</li>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>

                        <!-- Modal Infos List -->
                        <div class="row">
                            @foreach($modalInfos as $index => $modalInfo)
                                <div class="col-md-6 mb-4">
                                    <div class="card modal-info-card border">
                                        <div class="card-header bg-light">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="mb-0">
                                                    <i class="mdi mdi-message-text-outline me-1"></i>
                                                    Modal {{ $index + 1 }}
                                                    @if($modalInfo->modal_type === 'reminder')
                                                        <span class="badge badge-reminder ms-2">Reminder</span>
                                                    @else
                                                        <span class="badge badge-welcome ms-2">Welcome</span>
                                                    @endif
                                                </h5>
                                                @if($modalInfo->is_show)
                                                    <span class="badge badge-active">
                                                        <i class="mdi mdi-eye"></i> Aktif
                                                    </span>
                                                @else
                                                    <span class="badge badge-inactive">
                                                        <i class="mdi mdi-eye-off"></i> Tidak Aktif
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="text-muted small">Judul (H3):</label>
                                                <p class="mb-0 fw-bold">{{ $modalInfo->title }}</p>
                                                <small class="text-muted">{{ strlen($modalInfo->title) }}/100 karakter</small>
                                            </div>

                                            @if($modalInfo->modal_type === 'reminder')
                                                <div class="mb-3">
                                                    <label class="text-muted small">Subjudul (H3):</label>
                                                    <p class="mb-0">{{ $modalInfo->subtitle }}</p>
                                                    <small class="text-muted">{{ strlen($modalInfo->subtitle) }}/200 karakter</small>
                                                </div>
                                            @else
                                                <div class="mb-3">
                                                    <label class="text-muted small">Intro Text:</label>
                                                    <p class="mb-0">{{ $modalInfo->intro_text }}</p>
                                                    <small class="text-muted">{{ strlen($modalInfo->intro_text) }}/200 karakter</small>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="text-muted small">Footer Text:</label>
                                                    <p class="mb-0">{{ $modalInfo->footer_text }}</p>
                                                    <small class="text-muted">{{ strlen($modalInfo->footer_text) }}/200 karakter</small>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="text-muted small">Total Links:</label>
                                                    <span class="badge bg-info">
                                                        {{ $modalInfo->meta_links ? count($modalInfo->meta_links) : 0 }} link(s)
                                                    </span>
                                                </div>
                                            @endif

                                            <div class="text-end">
                                                <button type="button" class="btn btn-sm btn-warning btn-edit" 
                                                        data-id="{{ $modalInfo->id }}"
                                                        data-type="{{ $modalInfo->modal_type }}"
                                                        data-title="{{ $modalInfo->title }}"
                                                        data-subtitle="{{ $modalInfo->subtitle }}"
                                                        data-intro-text="{{ $modalInfo->intro_text }}"
                                                        data-footer-text="{{ $modalInfo->footer_text }}"
                                                        data-meta-links='@json($modalInfo->meta_links)'
                                                        data-is-show="{{ $modalInfo->is_show ? 1 : 0 }}">
                                                    <i class="mdi mdi-pencil me-1"></i>Edit
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal: Edit Reminder Modal -->
<div class="modal fade" id="editReminderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="editReminderForm">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title"><i class="mdi mdi-pencil me-2"></i>Edit Reminder Modal</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editReminderTitle" class="form-label">Judul (H3) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editReminderTitle" name="title" required minlength="4" maxlength="100">
                        <small class="text-muted">Minimal 4 karakter, maksimal 100 karakter</small>
                    </div>

                    <div class="mb-3">
                        <label for="editReminderSubtitle" class="form-label">Subjudul (H3) <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="editReminderSubtitle" name="subtitle" rows="3" required minlength="10" maxlength="200"></textarea>
                        <small class="text-muted">Minimal 10 karakter, maksimal 200 karakter</small>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_show" id="isShowReminder">
                            <label class="form-check-label" for="isShowReminder">
                                <strong>Tampilkan Modal di Landing Page</strong>
                            </label>
                        </div>
                        <small class="text-muted">Jika diaktifkan, modal ini akan muncul di halaman landing</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="mdi mdi-close me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="mdi mdi-content-save me-1"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Edit Welcome Modal -->
<div class="modal fade" id="editWelcomeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="editWelcomeForm">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title"><i class="mdi mdi-pencil me-2"></i>Edit Welcome Modal</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul Modal (H3) <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="editWelcomeTitle" class="form-control" required minlength="4" maxlength="100">
                        <small class="text-muted">Minimal 4 karakter, maksimal 100 karakter</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Intro Text <span class="text-danger">*</span></label>
                        <textarea name="intro_text" id="editWelcomeIntro" class="form-control" rows="3" required minlength="10" maxlength="200"></textarea>
                        <small class="text-muted">Text yang muncul di atas pilihan kategori. Min 10, max 200 karakter</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Footer Text <span class="text-danger">*</span></label>
                        <textarea name="footer_text" id="editWelcomeFooter" class="form-control" rows="3" required minlength="10" maxlength="200"></textarea>
                        <small class="text-muted">Text yang muncul di bawah pilihan kategori. Min 10, max 200 karakter</small>
                    </div>

                    <!-- Meta Links Manager -->
                    <div class="mb-3">
                        <label class="form-label">Kategori Links <span class="text-danger">*</span></label>
                        <div id="metaLinksContainer" class="border rounded p-3" style="background: #f8f9fa;">
                            <!-- Links will be dynamically added here -->
                        </div>
                        <button type="button" class="btn btn-sm btn-success mt-2" id="addLinkBtn">
                            <i class="mdi mdi-plus-circle me-1"></i>Tambah Link
                        </button>
                        <input type="hidden" name="meta_links" id="metaLinksInput">
                        <small class="text-muted d-block mt-1">Kelola link external yang muncul di modal welcome. Gunakan toggle "Aktif" pada setiap link untuk show/hide.</small>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_show" id="isShowWelcome">
                            <label class="form-check-label" for="isShowWelcome">
                                <strong>Tampilkan Modal di Landing Page</strong>
                            </label>
                        </div>
                        <small class="text-muted">Jika diaktifkan, modal ini akan muncul di halaman landing</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="mdi mdi-close me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="mdi mdi-content-save me-1"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('dashboard-assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.min.js"></script>

<script>
$(document).ready(function() {
    // Strict validation for title input (min 4, max 100)
    $('input[name="title"]').on('input', function() {
        let value = $(this).val();
        
        // Enforce max length
        if (value.length > 100) {
            $(this).val(value.substring(0, 100));
        }
        
        // Show validation feedback
        const length = $(this).val().length;
        const parent = $(this).parent();
        
        // Remove existing feedback
        parent.find('.validation-feedback').remove();
        
        if (length < 4 && length > 0) {
            parent.append('<div class="validation-feedback text-danger small mt-1">Minimal 4 karakter (saat ini: ' + length + ')</div>');
        } else if (length >= 4) {
            parent.append('<div class="validation-feedback text-success small mt-1">✓ Valid (' + length + '/100 karakter)</div>');
        }
    });

    // Strict validation for subtitle textarea (min 10, max 200)
    $('textarea[name="subtitle"]').on('input', function() {
        let value = $(this).val();
        
        // Enforce max length
        if (value.length > 200) {
            $(this).val(value.substring(0, 200));
        }
        
        // Show validation feedback
        const length = $(this).val().length;
        const parent = $(this).parent();
        
        // Remove existing feedback
        parent.find('.validation-feedback').remove();
        
        if (length < 10 && length > 0) {
            parent.append('<div class="validation-feedback text-danger small mt-1">Minimal 10 karakter (saat ini: ' + length + ')</div>');
        } else if (length >= 10) {
            parent.append('<div class="validation-feedback text-success small mt-1">✓ Valid (' + length + '/200 karakter)</div>');
        }
    });

    // Strict validation for intro/footer text (min 10, max 200)
    $('textarea[name="intro_text"], textarea[name="footer_text"]').on('input', function() {
        let value = $(this).val();
        
        // Enforce max length
        if (value.length > 200) {
            $(this).val(value.substring(0, 200));
        }
        
        // Show validation feedback
        const length = $(this).val().length;
        const parent = $(this).parent();
        
        // Remove existing feedback
        parent.find('.validation-feedback').remove();
        
        if (length < 10 && length > 0) {
            parent.append('<div class="validation-feedback text-danger small mt-1">Minimal 10 karakter (saat ini: ' + length + ')</div>');
        } else if (length >= 10) {
            parent.append('<div class="validation-feedback text-success small mt-1">✓ Valid (' + length + '/200 karakter)</div>');
        }
    });

    // Edit button click
    $('.btn-edit').on('click', function() {
        const id = $(this).data('id');
        const type = $(this).data('type');
        const title = $(this).data('title');
        const subtitle = $(this).data('subtitle');
        const introText = $(this).data('intro-text');
        const footerText = $(this).data('footer-text');
        const isShow = $(this).data('is-show');

        if (type === 'reminder') {
            // Open Reminder Modal
            $('#editReminderTitle').val(title);
            $('#editReminderSubtitle').val(subtitle);
            $('#isShowReminder').prop('checked', isShow == 1);

            // Trigger input event to show character count
            $('#editReminderTitle').trigger('input');
            $('#editReminderSubtitle').trigger('input');

            $('#editReminderForm').attr('action', '{{ route("dashboard.cms.modal-info.update", ":id") }}'.replace(':id', id));
            $('#editReminderModal').modal('show');
        } else {
            // Open Welcome Modal
            $('#editWelcomeTitle').val(title);
            $('#editWelcomeIntro').val(introText);
            $('#editWelcomeFooter').val(footerText);
            $('#isShowWelcome').prop('checked', isShow == 1);

            // Trigger input event to show character count
            $('#editWelcomeTitle').trigger('input');
            $('#editWelcomeIntro').trigger('input');
            $('#editWelcomeFooter').trigger('input');

            // Load meta links
            const metaLinksData = $(this).data('meta-links');
            loadMetaLinks(metaLinksData);

            $('#editWelcomeForm').attr('action', '{{ route("dashboard.cms.modal-info.update", ":id") }}'.replace(':id', id));
            $('#editWelcomeModal').modal('show');
        }
    });

    // Meta Links Manager
    let metaLinksArray = [];

    function loadMetaLinks(data) {
        metaLinksArray = data || [];
        renderMetaLinks();
    }

    function renderMetaLinks() {
        const container = $('#metaLinksContainer');
        container.empty();

        if (metaLinksArray.length === 0) {
            container.html('<p class="text-muted mb-0">Belum ada link. Klik "Tambah Link" untuk menambahkan.</p>');
            updateMetaLinksInput();
            return;
        }

        metaLinksArray.forEach((link, index) => {
            const linkHtml = `
                <div class="link-item border rounded p-3 mb-2" style="background: white;" data-index="${index}">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="mb-0">Link ${index + 1}</h6>
                        <div>
                            <button type="button" class="btn btn-sm btn-danger btn-remove-link" data-index="${index}">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">Judul</label>
                            <input type="text" class="form-control form-control-sm link-title" value="${link.title || ''}" data-index="${index}" maxlength="50">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">Icon</label>
                            <input type="text" class="form-control form-control-sm link-icon" value="${link.icon || '📌'}" data-index="${index}" maxlength="5">
                        </div>
                        <div class="col-12 mb-2">
                            <label class="form-label small">Subjudul</label>
                            <input type="text" class="form-control form-control-sm link-subtitle" value="${link.subtitle || ''}" data-index="${index}" maxlength="100">
                        </div>
                        <div class="col-12 mb-2">
                            <label class="form-label small">Link URL</label>
                            <input type="text" class="form-control form-control-sm link-link-url" value="${link.link_url || ''}" data-index="${index}" placeholder="https://example.com">
                            <small class="text-muted">URL external yang akan dibuka di tab baru</small>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">Background Color</label>
                            <div class="color-picker-wrapper">
                                <div class="color-preview color-picker-btn" data-index="${index}" style="background: ${link.bg_color || 'rgba(0,0,0,0.05)'}"></div>
                                <input type="text" class="form-control form-control-sm link-bg-color" value="${link.bg_color || 'rgba(0,0,0,0.05)'}" data-index="${index}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input link-is-active" type="checkbox" data-index="${index}" ${link.is_active ? 'checked' : ''}>
                                <label class="form-check-label small">Aktif</label>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            container.append(linkHtml);
        });

        // Attach event listeners
        $('.link-title, .link-subtitle, .link-icon, .link-link-url, .link-bg-color').on('input change', function() {
            const index = $(this).data('index');
            const classes = $(this).attr('class').split(' ');
            const linkClass = classes.find(c => c.startsWith('link-'));
            const field = linkClass.replace('link-', '').replace(/-/g, '_');
            metaLinksArray[index][field] = $(this).val();
            updateMetaLinksInput();
        });

        $('.link-is-active').on('change', function() {
            const index = $(this).data('index');
            metaLinksArray[index].is_active = $(this).is(':checked');
            updateMetaLinksInput();
        });

        $('.btn-remove-link').on('click', function() {
            const index = $(this).data('index');
            metaLinksArray.splice(index, 1);
            renderMetaLinks();
        });

        updateMetaLinksInput();
        initColorPickers();
    }

    function updateMetaLinksInput() {
        $('#metaLinksInput').val(JSON.stringify(metaLinksArray));
    }

    // Initialize color pickers for all color preview buttons
    let colorPickerInstances = [];
    
    function initColorPickers() {
        // Destroy existing instances
        colorPickerInstances.forEach(pickr => pickr.destroyAndRemove());
        colorPickerInstances = [];

        // Create new instances
        $('.color-picker-btn').each(function() {
            const index = $(this).data('index');
            const currentColor = metaLinksArray[index].bg_color || 'rgba(0,0,0,0.05)';
            
            const pickr = Pickr.create({
                el: this,
                theme: 'classic',
                default: currentColor,
                swatches: [
                    'rgba(40, 167, 69, 0.1)',
                    'rgba(102, 126, 234, 0.1)',
                    'rgba(234, 84, 85, 0.08)',
                    'rgba(255, 193, 7, 0.1)',
                    'rgba(23, 162, 184, 0.1)',
                    'rgba(108, 117, 125, 0.1)',
                    'rgba(220, 53, 69, 0.1)',
                    'rgba(0, 123, 255, 0.1)'
                ],
                components: {
                    preview: true,
                    opacity: true,
                    hue: true,
                    interaction: {
                        hex: true,
                        rgba: true,
                        input: true,
                        save: true
                    }
                }
            });

            pickr.on('save', (color, instance) => {
                const rgbaString = color.toRGBA().toString();
                metaLinksArray[index].bg_color = rgbaString;
                
                // Update input and preview
                $(`.link-bg-color[data-index="${index}"]`).val(rgbaString);
                $(`.color-picker-btn[data-index="${index}"]`).css('background', rgbaString);
                
                updateMetaLinksInput();
                pickr.hide();
            });

            colorPickerInstances.push(pickr);
        });
    }

    $('#addLinkBtn').on('click', function() {
        metaLinksArray.push({
            title: 'Link Baru',
            subtitle: 'Deskripsi link',
            icon: '📌',
            link_url: 'https://example.com',
            bg_color: 'rgba(0, 0, 0, 0.05)',
            is_active: true
        });
        renderMetaLinks();
    });
});
</script>
@endpush
