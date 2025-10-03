@extends('landing.layouts.app')
@section('content')

<div class="wrapper">
    @include('landing.pages.home.partials.box-form-choice')
    @include('landing.pages.home.partials.team-info')
    @include('landing.pages.home.partials.launch-date')
    @include('landing.pages.home.partials.box-counter')
    @include('landing.pages.home.partials.box-journal')
</div>

<!-- Default Welcome Modal -->
<div id="welcomeModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Selamat Datang di SIGAP Award 2025</h3>
            <span class="modal-close">&times;</span>
        </div>
        <div class="modal-body">
            <p>Pilih formulir sesuai dengan kategori Anda:</p>
            <div style="margin: 20px 0; text-align: left;">
                <div class="category-option" data-form-type="produsen" style="padding: 10px; background: rgba(40, 167, 69, 0.1); border-radius: 8px; cursor: pointer; transition: all 0.3s ease; border: 2px solid transparent;">
                    <strong>📊 Produsen Data Geospasial</strong><br>
                    <small style="color: #666;">Untuk perusahaan/organisasi produsen data geospasial</small>
                </div>
                <br>
                <div class="category-option" data-form-type="bpkh" style="margin-bottom: 15px; padding: 10px; background: rgba(102, 126, 234, 0.1); border-radius: 8px; cursor: pointer; transition: all 0.3s ease; border: 2px solid transparent;">
                    <strong>🏢 Balai Pemantapan Kawasan Hutan (BPKH)</strong><br>
                    <small style="color: #666;">Untuk instansi BPKH yang ingin berpartisipasi</small>
                </div>
            </div>
            <p style="font-size: 0.9em; color: #888; margin-top: 15px;">
                Klik pada kategori di atas atau kartu formulir untuk memulai pengisian.
            </p>
        </div>
        <div class="modal-footer">
            <button class="modal-btn-close" id="welcome-close">Tutup</button>
        </div>
    </div>
</div>

<!-- Floating WhatsApp Button -->
<a href="https://wa.me/6283199413424" class="whatsapp-float" target="_blank" rel="noopener" aria-label="Chat WhatsApp 6283199413424">
    <i class="fab fa-whatsapp" aria-hidden="true"></i>
    <span class="whatsapp-label">Call Center</span>
</a>

@endsection

@push('styles')
<style>
    .category-option:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border-color: rgba(102, 126, 234, 0.3) !important;
    }

    .category-option[data-form-type="produsen"]:hover {
        border-color: rgba(40, 167, 69, 0.5) !important;
    }

    .category-option[data-form-type="bpkh"]:hover {
        border-color: rgba(102, 126, 234, 0.5) !important;
    }

    /* Floating WhatsApp Button */
    .whatsapp-float {
        position: fixed;
        right: 18px;
        bottom: 50px;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #25D366;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        z-index: 9999;
        text-decoration: none;
        transition: transform 0.15s ease, box-shadow 0.15s ease, background-color 0.15s ease;
        position: fixed;
        isolation: isolate; /* ensure pseudo-element renders behind */
        overflow: visible;
    }
    .whatsapp-float:hover {
        background: #22c35e;
        transform: translateY(-1px);
        box-shadow: 0 10px 28px rgba(0,0,0,0.25);
    }
    .whatsapp-float::after {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: 50%;
        background: rgba(37, 211, 102, 0.35);
        transform: scale(1);
        opacity: 0.6;
        pointer-events: none;
        filter: blur(0.5px);
        animation: waRipple 2.4s ease-out infinite;
        z-index: -1;
    }
    .whatsapp-float i {
        font-size: 28px;
        line-height: 1;
    }
    .whatsapp-label {
        position: absolute;
        left: 50%;
        bottom: -24px;
        transform: translateX(-50%);
        background: #ffffff;
        color: #25D366;
        font-weight: 700;
        font-size: 12px;
        letter-spacing: 0.2px;
        padding: 4px 10px;
        border-radius: 999px;
        border: 1px solid rgba(37, 211, 102, 0.35);
        box-shadow: 0 6px 18px rgba(0,0,0,0.12);
        white-space: nowrap;
        pointer-events: none; /* click will pass through to the button */
    }
    /* Keep inner icon static and slightly higher */
    .whatsapp-float i { transform: translateY(-2px); }
    @keyframes waPulse {
        0%, 100% { transform: scale(1); box-shadow: 0 8px 24px rgba(0,0,0,0.2); }
        50% { transform: scale(1.06); box-shadow: 0 10px 28px rgba(0,0,0,0.25); }
    }
    @keyframes waRipple {
        0% { transform: scale(1); opacity: 0.6; }
        70% { transform: scale(1.9); opacity: 0; }
        100% { transform: scale(1.9); opacity: 0; }
    }
    @media (max-width: 576px) {
        .whatsapp-float { right: 12px; bottom: 36px; width: 52px; height: 52px; }
        .whatsapp-float i { font-size: 26px; }
        .whatsapp-float::after { filter: blur(0.4px); }
        .whatsapp-float i { transform: translateY(-1px); }
        .whatsapp-label { bottom: -20px; font-size: 11px; padding: 3px 8px; }
    }

    @media (max-width: 476px) {
        .whatsapp-float { right: 20px !important; }
    }
</style>
@endpush

@push('scripts')
<script>
    window.LAUNCH_DATES = {
        startDate: @json(optional($launchStart)->locale('en')->isoFormat('MMMM D, YYYY 00:00:00')) ?? 'October 1, 2025 00:00:00',
        finishDate: @json(optional($launchFinish)->locale('en')->isoFormat('MMMM D, YYYY 00:00:00')) ?? 'October 10, 2025 00:00:00'
    };

    // Welcome Modal Auto Show
    document.addEventListener('DOMContentLoaded', function() {
        const welcomeModal = document.getElementById('welcomeModal');
        const welcomeCloseBtn = document.getElementById('welcome-close');
        const welcomeModalClose = welcomeModal.querySelector('.modal-close');
        const categoryOptions = document.querySelectorAll('.category-option');

        // Function to show welcome modal
        function showWelcomeModal() {
            welcomeModal.style.display = 'flex';
            setTimeout(() => {
                welcomeModal.classList.add('show');
            }, 10);
        }

        // Function to hide welcome modal
        function hideWelcomeModal() {
            welcomeModal.classList.remove('show');
            setTimeout(() => {
                welcomeModal.style.display = 'none';
            }, 300);
        }

        // Function to trigger form modal (from box-form-choice)
        function triggerFormModal(formType) {
            // Hide welcome modal first
            hideWelcomeModal();

            // Wait for welcome modal to close, then trigger form modal
            setTimeout(() => {
                // Check if the form modal functions exist (from box-form-choice)
                if (typeof showModal === 'function') {
                    showModal(formType);
                } else {
                    // If showModal doesn't exist yet, wait a bit more and try again
                    setTimeout(() => {
                        if (typeof showModal === 'function') {
                            showModal(formType);
                        } else {
                            console.log('Form modal not available yet');
                        }
                    }, 500);
                }
            }, 350); // Wait for welcome modal close animation
        }

        // Show modal after a short delay to ensure page is fully loaded
        setTimeout(() => {
            showWelcomeModal();
        }, 7000); // 7 second delay

        // Add click events to category options
        categoryOptions.forEach(option => {
            option.addEventListener('click', function() {
                const formType = this.getAttribute('data-form-type');
                triggerFormModal(formType);
            });
        });

        // Close modal events
        welcomeCloseBtn.addEventListener('click', hideWelcomeModal);
        welcomeModalClose.addEventListener('click', hideWelcomeModal);

        // Close modal when clicking outside
        welcomeModal.addEventListener('click', function(e) {
            if (e.target === welcomeModal) {
                hideWelcomeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && welcomeModal.classList.contains('show')) {
                hideWelcomeModal();
            }
        });
    });

    // Image Modal Functions
    function openImageModal(imageSrc, imageName) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        const modalCaption = document.getElementById('modalCaption');

        modalImage.src = imageSrc;
        modalImage.alt = imageName;
        modalCaption.textContent = imageName;

        modal.style.display = 'flex';
        setTimeout(() => {
            modal.classList.add('show');
        }, 10);
    }

    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.classList.remove('show');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }

    // Close image modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const imageModal = document.getElementById('imageModal');
            if (imageModal && imageModal.style.display === 'flex') {
                closeImageModal();
            }
        }
    });
</script>
@endpush
