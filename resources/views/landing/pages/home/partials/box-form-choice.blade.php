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
                        </style>
@endpush

<div class="box notify-user">
                    <div class="round-player">
                        <div class="round-player__wrapper">
                            <audio id="track" loop preload="none">
                                <source src="{{ asset('sigap-assets/static/audio.mp3') }}" type="audio/mpeg">
                            </audio>
                            <button id="playButton" class="buttong"></button>
                            <div class="round-player__text">We are coming soon * Stay tuned *</div>
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
                                Form Unit Produsen Data
                            </a>
                        </div>
                        <div class="glass-card">
                            <a href="javascript:void(0);">
                                Form Unit BPKH
                            </a>
                        </div>
                        </div>

                    </div>
                    </div>
