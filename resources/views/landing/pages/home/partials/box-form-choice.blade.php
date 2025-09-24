@push('styles')
    <style>
                            .glass-card {
                                background: rgba(255, 255, 255, 0.2);
                                border-radius: 16px;
                                box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
                                backdrop-filter: blur(5px);
                                -webkit-backdrop-filter: blur(5px);
                                border: 1px solid rgba(255, 255, 255, 0.3);
                                padding: 20px;
                                margin: 20px 0;
                                text-align: center;
                                transition: transform 0.3s ease;
                            }
                            .glass-card:hover {
                                transform: translateY(-5px);
                            }
                            .glass-card a {
                                color: #333;
                                text-decoration: none;
                                font-size: 1.5em;
                                font-weight: bold;
                                display: block;
                                padding: 20px;
                            }
                            .logo-container {
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                gap: 20px;
                                margin-bottom: 1px;
                                position: relative;
                                bottom: 7%;
                            }
                            .logo-container img {
                                height: 100px;
                                width: auto;
                                object-fit: contain;
                                object-position: center;
                            }
                            .glass-container {
                                position: relative;
                                bottom: 10%;
                            }

                            .notify-user__content {
                                max-width: 100%;
                                overflow: hidden;
                                box-sizing: border-box;
                            }
                            @media (max-width: 1200px) {
                                    .glass-cards-container {
                                        bottom: 15%;
                                    }
                                    .glass-card a {
                                    color: #333;
                                    text-decoration: none;
                                    font-size: 1.2em;
                                    padding: 10px !important;
                                }
                            }

                            @media (max-width: 920px) {
                                    .glass-cards-container {
                                        bottom: 15%;
                                    }
                                    .glass-card a {
                                    font-size: 1.2em;
                                    padding: 10px !important;
                                }
                            }

                            @media (max-width: 420px) {
                                    .round-player {
                                        top: 60% !important;
                                    }
                            }

                            /* Modal Styles */
                            .modal-overlay {
                                position: fixed;
                                top: 0;
                                left: 0;
                                width: 100%;
                                height: 100%;
                                background: rgba(0, 0, 0, 0.5);
                                backdrop-filter: blur(5px);
                                -webkit-backdrop-filter: blur(5px);
                                z-index: 9999;
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                opacity: 0;
                                visibility: hidden;
                                transition: all 0.3s ease;
                            }

                            .modal-overlay.show {
                                opacity: 1;
                                visibility: visible;
                            }

                            .modal-content {
                                background: rgba(255, 255, 255, 0.95);
                                border-radius: 16px;
                                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
                                backdrop-filter: blur(10px);
                                -webkit-backdrop-filter: blur(10px);
                                border: 1px solid rgba(255, 255, 255, 0.3);
                                max-width: 400px;
                                width: 90%;
                                transform: scale(0.7);
                                transition: transform 0.3s ease;
                            }

                            .modal-overlay.show .modal-content {
                                transform: scale(1);
                            }

                            .modal-header {
                                padding: 20px 20px 10px;
                                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                                display: flex;
                                justify-content: space-between;
                                align-items: center;
                            }

                            .modal-header h3 {
                                margin: 0;
                                color: #333;
                                font-size: 1.5em;
                                font-weight: bold;
                            }

                            .modal-close {
                                font-size: 24px;
                                color: #666;
                                cursor: pointer;
                                transition: color 0.3s ease;
                            }

                            .modal-close:hover {
                                color: #333;
                            }

                            .modal-body {
                                padding: 20px;
                                text-align: center;
                            }

                            .modal-body p {
                                margin: 0;
                                color: #555;
                                font-size: 1.1em;
                                line-height: 1.5;
                            }

                            .modal-footer {
                                padding: 10px 20px 20px;
                                text-align: center;
                            }

                            .modal-btn-close, .modal-btn-open {
                                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                color: white;
                                border: none;
                                padding: 10px 30px;
                                border-radius: 25px;
                                font-size: 1em;
                                cursor: pointer;
                                transition: all 0.3s ease;
                                margin: 0 5px;
                            }

                            .modal-btn-close:hover, .modal-btn-open:hover {
                                transform: translateY(-2px);
                                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
                            }

                            .modal-btn-open {
                                background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
                            }

                            .modal-btn-open:hover {
                                box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
                            }
                        </style>
@endpush

<div class="box notify-user">
                    <div class="round-player">
                        <div class="round-player__wrapper">
                            <audio id="track" loop preload="none">
                                <source src="{{ asset('sigap-assets/static/audio.mp3') }}" type="audio/mpeg">
                            </audio>
                            <button id="playButton" class="buttong"></button>
                            <div class="round-player__text">Sigap Award *2025* Kehutanan </div>
                        </div>
                    </div>
                    <div class="notify-user__content">
                        <div class="logo-container">
                            <img src="{{ asset('sigap-assets/images/kehutanan-logo.png') }}" alt="Logo Kehutanan">
                            <img src="{{ asset('sigap-assets/images/m4cr-logo.png') }}" alt="Logo M4CR">
                        </div>
                        <div class="glass-container">
                            <div class="glass-card">
                            <a href="javascript:void(0);">
                                Form Produsen Data Geospasial
                            </a>
                        </div>
                        <div class="glass-card">
                            <a href="javascript:void(0);">
                                Form Balai Pemantapan Kawasan Hutan (BPKH)
                            </a>
                        </div>
                        </div>
                    </div>

                    <!-- Modal Popup -->
                    <div id="formModal" class="modal-overlay" style="display: none;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3>Informasi</h3>
                                <span class="modal-close">&times;</span>
                            </div>
                            <div class="modal-body">
                                <p id="modal-message">Formulir sudah dibuka</p>
                            </div>
                            <div class="modal-footer">
                                <button class="modal-btn-close">Tutup</button>
                                <button class="modal-btn-open" id="modal-btn-open">Buka Form</button>
                            </div>
                        </div>
                    </div>
</div>


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configuration
        const formPublish = true; // Set to false to show "coming soon" modal

        const modal = document.getElementById('formModal');
        const formCards = document.querySelectorAll('.glass-card a');
        const closeBtn = document.querySelector('.modal-close');
        const closeBtnFooter = document.querySelector('.modal-btn-close');
        const openBtn = document.getElementById('modal-btn-open');
        const modalMessage = document.getElementById('modal-message');
        let currentFormUrl = '';

        // Function to show modal (make it global)
        window.showModal = function(formType) {
            if (formPublish) {
                // Form is published - show with open button
                modalMessage.textContent = 'Formulir sudah dibuka';
                openBtn.style.display = 'inline-block';

                // Set URL based on form type
                if (formType === 'bpkh') {
                    // currentFormUrl = 'https://tally.so/r/w5Ne2P';
                    currentFormUrl = 'https://form.sigap-award.site/bpkh';
                } else if (formType === 'produsen') {
                    // currentFormUrl = 'https://tally.so/r/nrX1al';
                    currentFormUrl = 'https://form.sigap-award.site/produsen';
                }
            } else {
                // Form not published - show coming soon message
                modalMessage.textContent = 'Formulir akan dibuka dalam waktu dekat';
                openBtn.style.display = 'none';
                currentFormUrl = '';
            }

            modal.style.display = 'flex';
            setTimeout(() => {
                modal.classList.add('show');
            }, 10);
        }

        // Function to hide modal (make it global)
        window.hideModal = function() {
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }

        // Add click event to form cards
        formCards.forEach((card, index) => {
            card.addEventListener('click', function(e) {
                e.preventDefault();
                // Determine form type based on card content
                const cardText = card.textContent.trim().toLowerCase();
                if (cardText.includes('bpkh')) {
                    showModal('bpkh');
                } else if (cardText.includes('produsen')) {
                    showModal('produsen');
                }
            });
        });

        // Open form button event
        openBtn.addEventListener('click', function() {
            if (currentFormUrl) {
                window.open(currentFormUrl, '_blank');
            }
        });

        // Close modal events
        closeBtn.addEventListener('click', hideModal);
        closeBtnFooter.addEventListener('click', hideModal);

        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                hideModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('show')) {
                hideModal();
            }
        });
    });
</script>
@endpush
