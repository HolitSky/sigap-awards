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
</script>
@endpush
