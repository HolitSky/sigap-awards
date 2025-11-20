@php
    // Define variables at top level for use throughout the view
    $hasMenuChoice = isset($menuChoice) && $menuChoice;
    $useModal = $hasMenuChoice && ($menuChoice->use_main_menu === true || $menuChoice->use_main_menu === 1 || $menuChoice->use_main_menu === '1');
@endphp

@push('styles')
    <style>
                            .glass-card {
                                background: rgba(255, 255, 255, 0.2);
                                border-radius: 16px;
                                box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
                                backdrop-filter: blur(5px);
                                -webkit-backdrop-filter: blur(5px);
                                border: 1px solid rgba(255, 255, 255, 0.3);
                                padding: 10px;
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
                                margin-bottom: 20px;
                                position: relative;
                                flex-shrink: 0;
                            }
                            .logo-container img {
                                height: 100px;
                                width: auto;
                                object-fit: contain;
                                object-position: center;
                            }
                            .glass-container {
                                position: relative;
                                bottom: 0;
                                max-height: calc(100% - 140px);
                                overflow-y: auto;
                                overflow-x: hidden;
                                padding-right: 5px;
                                padding-bottom: 10px;
                                flex: 1;
                                /* Custom scrollbar */
                                scrollbar-width: thin;
                                scrollbar-color: rgba(255, 255, 255, 0.5) transparent;
                            }

                            /* Webkit scrollbar styling */
                            .glass-container::-webkit-scrollbar {
                                width: 6px;
                            }

                            .glass-container::-webkit-scrollbar-track {
                                background: rgba(255, 255, 255, 0.1);
                                border-radius: 10px;
                            }

                            .glass-container::-webkit-scrollbar-thumb {
                                background: rgba(255, 255, 255, 0.5);
                                border-radius: 10px;
                            }

                            .glass-container::-webkit-scrollbar-thumb:hover {
                                background: rgba(255, 255, 255, 0.7);
                            }

                            .notify-user__content {
                                max-width: 100%;
                                overflow: hidden;
                                box-sizing: border-box;
                                max-height: calc(100% - 16px);
                                display: flex;
                                flex-direction: column;
                                padding: 3em 4em !important;
                                position: relative;
                            }

                            /* Ensure content stays within ::after border */
                            .notify-user__content > * {
                                position: relative;
                                z-index: 1;
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
                                .notify-user__content {
                                    padding: 2.5em 3em !important;
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
                                .notify-user__content {
                                    padding: 2em 2.5em !important;
                                }
                                .logo-container img {
                                    height: 80px;
                                }
                            }

                            @media (max-width: 420px) {
                                    .round-player {
                                        top: 60% !important;
                                    }
                                    .notify-user__content {
                                        padding: 1.5em 2em !important;
                                    }
                                    .logo-container {
                                        margin-bottom: 15px;
                                    }
                                    .logo-container img {
                                        height: 60px;
                                    }
                                    .glass-card a {
                                        font-size: 1em;
                                        padding: 12px !important;
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

                            /* Main Menu Button */
                            .main-menu-button {
                                background: linear-gradient(135deg, #8b9bff 0%, #9b7dd4 100%);
                                color: white;
                                border: none;
                                padding: 20px 40px;
                                border-radius: 25px;
                                font-size: 1.5em;
                                font-weight: bold;
                                cursor: pointer;
                                transition: all 0.3s ease;
                                box-shadow: 0 4px 15px rgba(139, 155, 255, 0.25);
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                gap: 12px;
                                width: 100%;
                                max-width: 400px;
                                margin: 0 auto;
                                border: 2px solid rgba(255, 255, 255, 0.3);
                                outline: none;
                            }

                            .main-menu-button:hover {
                                transform: translateY(-3px);
                                box-shadow: 0 8px 25px rgba(139, 155, 255, 0.35);
                                background: linear-gradient(135deg, #a5b5ff 0%, #b599e4 100%);
                                border: 2px solid rgba(255, 255, 255, 0.5);
                            }

                            .main-menu-button:active {
                                transform: translateY(-1px);
                            }

                            .main-menu-button:focus {
                                outline: none;
                                box-shadow: 0 4px 15px rgba(139, 155, 255, 0.25);
                            }

                            /* Menu Modal Styles */
                            .menu-modal {
                                position: fixed;
                                top: 0;
                                left: 0;
                                width: 100%;
                                height: 100%;
                                background: rgba(0, 0, 0, 0.7);
                                backdrop-filter: blur(8px);
                                z-index: 10000;
                                display: none;
                                justify-content: center;
                                align-items: center;
                                opacity: 0;
                                transition: opacity 0.3s ease;
                            }

                            .menu-modal.show {
                                display: flex;
                                opacity: 1;
                            }

                            .menu-modal-content {
                                background: rgba(255, 255, 255, 0.95);
                                border-radius: 20px;
                                padding: 32px;
                                max-width: 600px;
                                width: 90%;
                                max-height: 80vh;
                                overflow-y: auto;
                                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                                transform: scale(0.9);
                                transition: transform 0.3s ease;
                            }

                            .menu-modal.show .menu-modal-content {
                                transform: scale(1);
                            }

                            .menu-modal-header {
                                display: flex;
                                justify-content: space-between;
                                align-items: center;
                                margin-bottom: 24px;
                                padding-bottom: 16px;
                                border-bottom: 2px solid rgba(0, 0, 0, 0.1);
                            }

                            .menu-modal-title {
                                font-size: 1.8em;
                                font-weight: bold;
                                color: #333;
                                margin: 0;
                            }

                            .menu-modal-close {
                                background: none;
                                border: none;
                                font-size: 32px;
                                color: #666;
                                cursor: pointer;
                                width: 40px;
                                height: 40px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                border-radius: 50%;
                                transition: all 0.3s ease;
                            }

                            .menu-modal-close:hover {
                                background: rgba(0, 0, 0, 0.1);
                                color: #333;
                            }

                            .menu-list {
                                display: flex;
                                flex-direction: column;
                                gap: 12px;
                            }

                            .menu-item {
                                background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
                                border: 2px solid rgba(102, 126, 234, 0.2);
                                border-radius: 12px;
                                padding: 16px 20px;
                                text-decoration: none;
                                color: #333;
                                font-size: 1.1em;
                                font-weight: 600;
                                transition: all 0.3s ease;
                                display: flex;
                                align-items: center;
                                gap: 12px;
                            }

                            .menu-item:hover {
                                background: linear-gradient(135deg, rgba(102, 126, 234, 0.2) 0%, rgba(118, 75, 162, 0.2) 100%);
                                border-color: rgba(102, 126, 234, 0.4);
                                transform: translateX(8px);
                            }

                            .menu-item-icon {
                                font-size: 1.5em;
                                flex-shrink: 0;
                            }

                            @media (max-width: 768px) {
                                .main-menu-button {
                                    font-size: 1.2em;
                                    padding: 16px 32px;
                                }

                                .menu-modal-content {
                                    padding: 24px;
                                    width: 95%;
                                }

                                .menu-modal-title {
                                    font-size: 1.4em;
                                }

                                .menu-item {
                                    font-size: 1em;
                                    padding: 14px 16px;
                                }
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
                            @if($hasMenuChoice && $useModal)
                                <!-- Main Menu Button (Modal Mode) -->
                                <button class="main-menu-button" onclick="event.preventDefault(); event.stopPropagation(); showMenuModal();">
                                    <span class="menu-item-icon">📋</span>
                                    <span>{{ $menuChoice->main_menu_title }}</span>
                                </button>
                            @elseif($hasMenuChoice && !$useModal)
                                <!-- Direct Menu Display -->
                                @foreach($menuChoice->menu_items as $index => $item)
                                <div class="glass-card">
                                    @if(isset($item['type']) && $item['type'] === 'modal')
                                        <a href="javascript:void(0);" onclick="showSubmenuModal('submenu-{{ $index }}')">
                                            @if(!empty($item['icon']))
                                                <span class="menu-item-icon">{{ $item['icon'] }}</span>
                                            @endif
                                            {{ $item['title'] }}
                                        </a>
                                    @elseif(isset($item['type']) && $item['type'] === 'coming_soon')
                                        <a href="javascript:void(0);" onclick="showComingSoonModal()">
                                            @if(!empty($item['icon']))
                                                <span class="menu-item-icon">{{ $item['icon'] }}</span>
                                            @endif
                                            {{ $item['title'] }}
                                        </a>
                                    @else
                                        <a href="{{ $item['link'] }}" target="_blank">
                                            @if(!empty($item['icon']))
                                                <span class="menu-item-icon">{{ $item['icon'] }}</span>
                                            @endif
                                            {{ $item['title'] }}
                                        </a>
                                    @endif
                                </div>
                                @endforeach
                            @else
                                <!-- Fallback jika tidak ada menu choice aktif -->
                                <button class="main-menu-button" onclick="showMenuModal()">
                                    <span class="menu-item-icon">📋</span>
                                    <span>Menu SIGAP Award 2025</span>
                                </button>
                            @endif
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

                    <!-- Modal Upload Poster -->
                    <div id="uploadPosterModal" class="modal-overlay" style="display: none;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3>Upload Poster</h3>
                                <span class="modal-close-poster">&times;</span>
                            </div>
                            <div class="modal-body">
                                <p style="margin-bottom: 20px;">Pilih kategori untuk upload poster:</p>
                                <div style="display: flex; flex-direction: column; gap: 15px;">
                                    <a href="https://form.sigap-award.site/upload-poster-bpkh" target="_blank"
                                       style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 30px; border-radius: 25px; text-decoration: none; font-weight: bold; text-align: center; transition: all 0.3s ease;">
                                        🖼️ BPKH
                                    </a>
                                    <a href="https://form.sigap-award.site/upload-poster-produsen" target="_blank"
                                       style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 15px 30px; border-radius: 25px; text-decoration: none; font-weight: bold; text-align: center; transition: all 0.3s ease;">
                                        🖼️ Produsen
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Upload Presentasi -->
                    <div id="uploadPresentasiModal" class="modal-overlay" style="display: none;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3>Upload Presentasi</h3>
                                <span class="modal-close-upload">&times;</span>
                            </div>
                            <div class="modal-body">
                                <p style="margin-bottom: 20px;">Pilih kategori untuk upload presentasi:</p>
                                <div style="display: flex; flex-direction: column; gap: 15px;">
                                    <a href="https://form.sigap-award.site/bpkh-presentasi" target="_blank"
                                       style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 30px; border-radius: 25px; text-decoration: none; font-weight: bold; text-align: center; transition: all 0.3s ease;">
                                        📋 BPKH
                                    </a>
                                    <a href="https://form.sigap-award.site/produsen-presentasi" target="_blank"
                                       style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 15px 30px; border-radius: 25px; text-decoration: none; font-weight: bold; text-align: center; transition: all 0.3s ease;">
                                        🏭 Produsen
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Coming Soon -->
                    <div id="comingSoonModal" class="modal-overlay" style="display: none;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3>⏳ Sedang Disiapkan</h3>
                                <span class="modal-close-coming-soon">&times;</span>
                            </div>
                            <div class="modal-body">
                                <p style="margin-bottom: 20px; text-align: center; font-size: 1.1em;">
                                    Fitur ini sedang dalam tahap persiapan dan akan segera tersedia.
                                </p>
                                <p style="text-align: center; color: #666;">
                                    Mohon ditunggu untuk informasi lebih lanjut. Terima kasih! 🙏
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button class="modal-btn-close-coming-soon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 30px; border: none; border-radius: 25px; font-weight: bold; cursor: pointer; transition: all 0.3s ease;">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Menu Modal -->
                    <div id="menuModal" class="menu-modal">
                        <div class="menu-modal-content">
                            <div class="menu-modal-header">
                                <h3 class="menu-modal-title">
                                    @if($hasMenuChoice && $useModal)
                                        {{ $menuChoice->main_menu_title }}
                                    @else
                                        Menu SIGAP Award 2025
                                    @endif
                                </h3>
                                <button class="menu-modal-close" onclick="closeMenuModal()">&times;</button>
                            </div>
                            <div class="menu-list">
                                @if($hasMenuChoice && $useModal)
                                    @foreach($menuChoice->menu_items as $index => $item)
                                        @if(isset($item['type']) && $item['type'] === 'modal')
                                            <a href="javascript:void(0);" onclick="closeMenuModal(); showSubmenuModal('submenu-{{ $index }}');" class="menu-item">
                                                @if(!empty($item['icon']))
                                                    <span class="menu-item-icon">{{ $item['icon'] }}</span>
                                                @endif
                                                <span>{{ $item['title'] }}</span>
                                            </a>
                                        @elseif(isset($item['type']) && $item['type'] === 'coming_soon')
                                            <a href="javascript:void(0);" onclick="closeMenuModal(); showComingSoonModal();" class="menu-item">
                                                @if(!empty($item['icon']))
                                                    <span class="menu-item-icon">{{ $item['icon'] }}</span>
                                                @endif
                                                <span>{{ $item['title'] }}</span>
                                            </a>
                                        @else
                                            <a href="{{ $item['link'] }}" class="menu-item">
                                                @if(!empty($item['icon']))
                                                    <span class="menu-item-icon">{{ $item['icon'] }}</span>
                                                @endif
                                                <span>{{ $item['title'] }}</span>
                                            </a>
                                        @endif
                                    @endforeach
                                @else
                                    <!-- Fallback Static Menu Items -->
                                    <a href="javascript:void(0);" onclick="closeMenuModal(); showUploadPosterModal();" class="menu-item">
                                        <span class="menu-item-icon">🖼️</span>
                                        <span>Upload Poster SIGAP Award 2025</span>
                                    </a>
                                    <a href="{{ route('poster-criteria') }}" class="menu-item">
                                        <span class="menu-item-icon">📋</span>
                                        <span>Kriteria Poster SIGAP Award 2025</span>
                                    </a>
                                    <a href="{{ route('result-presentation') }}" class="menu-item">
                                        <span class="menu-item-icon">📑</span>
                                        <span>Rekapan Presentasi Peserta Sigap Award 2025</span>
                                    </a>
                                    <a href="{{ route('cv-juri') }}" class="menu-item">
                                        <span class="menu-item-icon">👨‍⚖️</span>
                                        <span>Lihat CV Juri SIGAP Award 2025</span>
                                    </a>
                                    <a href="{{ route('announcement') }}" class="menu-item">
                                        <span class="menu-item-icon">📢</span>
                                        <span>Pengumuman: List Peserta Tahap Presentasi</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Sub-menu Modals (Dynamic) -->
                    @if($hasMenuChoice)
                        @foreach($menuChoice->menu_items as $index => $item)
                            @if(isset($item['type']) && $item['type'] === 'modal' && isset($item['submenu']))
                            <div id="submenu-{{ $index }}" class="modal-overlay" style="display: none;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3>{{ $item['title'] }}</h3>
                                        <span class="modal-close" onclick="closeSubmenuModal('submenu-{{ $index }}')">&times;</span>
                                    </div>
                                    <div class="modal-body">
                                        <p style="margin-bottom: 20px;">Pilih kategori:</p>
                                        <div style="display: flex; flex-direction: column; gap: 15px;">
                                            @foreach($item['submenu'] as $subitem)
                                            <a href="{{ $subitem['link'] }}" target="_blank"
                                               style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 30px; border-radius: 25px; text-decoration: none; font-weight: bold; text-align: center; transition: all 0.3s ease;">
                                                {{ $subitem['title'] }}
                                            </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    @endif
</div>


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configuration
        const formPublish = true; // Set to false to show "coming soon" modal
        const deadline = true; // Set to true to show "deadline passed" message

        const modal = document.getElementById('formModal');
        const formCards = document.querySelectorAll('.glass-card a');
        const closeBtn = document.querySelector('.modal-close');
        const closeBtnFooter = document.querySelector('.modal-btn-close');
        const openBtn = document.getElementById('modal-btn-open');
        const modalMessage = document.getElementById('modal-message');
        let currentFormUrl = '';

        // Function to show modal (make it global)
        window.showModal = function(formType) {
            if (deadline) {
                // Deadline has passed - show thank you message
                let kategori = formType === 'bpkh' ? 'Balai Pemantapan Kawasan Hutan (BPKH)' : 'Produsen Data Geospasial';
                modalMessage.textContent = `Kategori Form ${kategori} ditutup. Terima kasih telah berpartisipasi dalam pengisian form penilaian SIGAP Award 2025 , Nantikan pengumuman resmi dari kami. Sampai jumpa di Bali!🙏🏻😁`;
                openBtn.style.display = 'none';
                currentFormUrl = '';
            } else if (formPublish) {
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
                // Skip if this is the announcement link (has actual href, not javascript:void)
                const href = card.getAttribute('href');
                if (href && !href.includes('javascript:void')) {
                    // Let the link work normally
                    return;
                }

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

        // Upload Poster Modal Functions
        const uploadPosterModal = document.getElementById('uploadPosterModal');
        const closePosterBtn = document.querySelector('.modal-close-poster');

        window.showUploadPosterModal = function() {
            uploadPosterModal.style.display = 'flex';
            setTimeout(() => {
                uploadPosterModal.classList.add('show');
            }, 10);
        }

        window.hideUploadPosterModal = function() {
            uploadPosterModal.classList.remove('show');
            setTimeout(() => {
                uploadPosterModal.style.display = 'none';
            }, 300);
        }

        // Close poster modal events
        if (closePosterBtn) {
            closePosterBtn.addEventListener('click', hideUploadPosterModal);
        }

        // Close poster modal when clicking outside
        uploadPosterModal.addEventListener('click', function(e) {
            if (e.target === uploadPosterModal) {
                hideUploadPosterModal();
            }
        });

        // Upload Presentasi Modal Functions
        const uploadModal = document.getElementById('uploadPresentasiModal');
        const closeUploadBtn = document.querySelector('.modal-close-upload');

        window.showUploadPresentasiModal = function() {
            uploadModal.style.display = 'flex';
            setTimeout(() => {
                uploadModal.classList.add('show');
            }, 10);
        }

        window.hideUploadPresentasiModal = function() {
            uploadModal.classList.remove('show');
            setTimeout(() => {
                uploadModal.style.display = 'none';
            }, 300);
        }

        // Close upload modal events
        if (closeUploadBtn) {
            closeUploadBtn.addEventListener('click', hideUploadPresentasiModal);
        }

        // Close upload modal when clicking outside
        uploadModal.addEventListener('click', function(e) {
            if (e.target === uploadModal) {
                hideUploadPresentasiModal();
            }
        });

        // Close upload modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && uploadModal.classList.contains('show')) {
                hideUploadPresentasiModal();
            }
        });

        // Menu Modal Functions
        window.showMenuModal = function() {
            const menuModal = document.getElementById('menuModal');
            console.log('showMenuModal called, menuModal:', menuModal);
            if (menuModal) {
                menuModal.style.display = 'flex';
                setTimeout(() => {
                    menuModal.classList.add('show');
                }, 10);
                console.log('Modal opened with show class');
            } else {
                console.error('menuModal element not found!');
            }
        }

        window.closeMenuModal = function() {
            const menuModal = document.getElementById('menuModal');
            if (menuModal) {
                menuModal.classList.remove('show');
                setTimeout(() => {
                    menuModal.style.display = 'none';
                }, 300);
            }
        }

        // Sub-menu Modal Functions
        window.showSubmenuModal = function(modalId) {
            const submenuModal = document.getElementById(modalId);
            if (submenuModal) {
                submenuModal.style.display = 'flex';
                setTimeout(() => {
                    submenuModal.classList.add('show');
                }, 10);
            }
        }

        window.closeSubmenuModal = function(modalId) {
            const submenuModal = document.getElementById(modalId);
            if (submenuModal) {
                submenuModal.classList.remove('show');
                setTimeout(() => {
                    submenuModal.style.display = 'none';
                }, 300);
            }
        }

        // Close menu modal when clicking outside
        menuModal.addEventListener('click', function(e) {
            if (e.target === menuModal) {
                closeMenuModal();
            }
        });

        // Close menu modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && menuModal.classList.contains('show')) {
                closeMenuModal();
            }
        });

        // Coming Soon Modal Functions
        const comingSoonModal = document.getElementById('comingSoonModal');
        const closeComingSoonBtn = document.querySelector('.modal-close-coming-soon');
        const closeComingSoonFooterBtn = document.querySelector('.modal-btn-close-coming-soon');

        window.showComingSoonModal = function() {
            if (comingSoonModal) {
                comingSoonModal.style.display = 'flex';
                setTimeout(() => {
                    comingSoonModal.classList.add('show');
                }, 10);
            }
        }

        window.hideComingSoonModal = function() {
            if (comingSoonModal) {
                comingSoonModal.classList.remove('show');
                setTimeout(() => {
                    comingSoonModal.style.display = 'none';
                }, 300);
            }
        }

        // Close coming soon modal events
        if (closeComingSoonBtn) {
            closeComingSoonBtn.addEventListener('click', hideComingSoonModal);
        }
        if (closeComingSoonFooterBtn) {
            closeComingSoonFooterBtn.addEventListener('click', hideComingSoonModal);
        }

        // Close coming soon modal when clicking outside
        if (comingSoonModal) {
            comingSoonModal.addEventListener('click', function(e) {
                if (e.target === comingSoonModal) {
                    hideComingSoonModal();
                }
            });
        }

        // Close coming soon modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && comingSoonModal && comingSoonModal.classList.contains('show')) {
                hideComingSoonModal();
            }
        });
    });
</script>
@endpush
