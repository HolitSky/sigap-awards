@extends('landing.layouts.app')
@section('content')

<div class="wrapper">
    @include('landing.pages.home.partials.box-form-choice')
    @include('landing.pages.home.partials.team-info')
    @include('landing.pages.home.partials.launch-date', compact('rangeDate', 'rangeDateStart', 'rangeDateEnd', 'launchFinish'))
    @include('landing.pages.home.partials.box-counter')
    @include('landing.pages.home.partials.box-journal')
</div>

<!-- Welcome Modal (Dynamic from CMS) -->
@if(isset($welcomeModal) && $welcomeModal && $welcomeModal->is_show)
<div id="welcomeModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>{{ $welcomeModal->title }}</h3>
            <span class="modal-close">&times;</span>
        </div>
        <div class="modal-body">
            <p id="welcome-intro-text">{{ $welcomeModal->intro_text }}</p>
            <div style="margin: 20px 0; text-align: left;">
                @if($welcomeModal->meta_links && is_array($welcomeModal->meta_links))
                    @foreach($welcomeModal->meta_links as $link)
                        @if($link['is_active'] ?? true)
                            <div class="category-option"
                                 data-link="{{ $link['link_url'] ?? '#' }}"
                                 style="padding: 10px; background: {{ $link['bg_color'] ?? 'rgba(0,0,0,0.05)' }}; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; border: 2px solid transparent; margin-bottom: 10px;">
                                <strong>{{ $link['icon'] ?? '📌' }} {{ $link['title'] ?? 'Link' }}</strong><br>
                                <small style="color: #666;">{{ $link['subtitle'] ?? '' }}</small>
                            </div>
                        @endif
                    @endforeach
                @else
                    {{-- Fallback jika meta_links kosong --}}
                    <div class="category-option" data-link="https://form.sigap-award.site/voting2025" style="padding: 10px; background: rgba(234, 84, 85, 0.08); border-radius: 8px; cursor: pointer; transition: all 0.3s ease; border: 2px solid transparent;">
                        <strong>🗳️ Voting Pengelola IGT 2025</strong><br>
                        <small style="color: #666;">Menuju halaman voting 2025</small>
                    </div>
                @endif
            </div>
            <p id="welcome-footer-text" style="font-size: 0.9em; color: #888; margin-top: 15px;">
                {!! nl2br(e($welcomeModal->footer_text)) !!}
            </p>
        </div>
        <div class="modal-footer">
            <button class="modal-btn-close" id="welcome-close">Tutup</button>
        </div>
    </div>
</div>
@endif

@include('landing.pages.home.partials.reminder-modal')

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

    .category-option[data-action="vote"]:hover {
        border-color: rgba(234, 84, 85, 0.5) !important;
    }

    /* Floating WhatsApp Button */
    .whatsapp-float {
        position: fixed;
        right: 18px;
        bottom: 70px;
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
        finishDate: @json(optional($launchFinish)->locale('en')->isoFormat('MMMM D, YYYY 00:00:00')) ?? 'October 10, 2025 00:00:00',
        rangeDate: {{ isset($rangeDate) && $rangeDate ? 'true' : 'false' }},
        rangeDateStart: @json(optional($rangeDateStart)->locale('en')->isoFormat('MMMM D, YYYY 00:00:00')) ?? 'October 22, 2025 00:00:00',
        rangeDateEnd: @json(optional($rangeDateEnd)->locale('en')->isoFormat('MMMM D, YYYY 00:00:00')) ?? 'October 24, 2025 00:00:00',
        launchTitle: @json(optional($launchDate)->title) ?? 'Penganugerahan Sigap Award'
    };

    console.log('LAUNCH_DATES config:', window.LAUNCH_DATES);

    // Override calendar display for range date mode - CONTINUOUS FORCE
    document.addEventListener('DOMContentLoaded', function() {
        if (window.LAUNCH_DATES.rangeDate) {
            function forceCalendarDisplay() {
                const calendarDate = document.querySelector('.launch-date__calendar-date');
                const calendarMonth = document.querySelector('.launch-date__calendar-month');
                const calendarLabel = document.querySelector('.launch-date__calendar-label');

                if (calendarDate && calendarMonth && calendarLabel) {
                    // Get dates dynamically from LAUNCH_DATES
                    const startDate = new Date(window.LAUNCH_DATES.rangeDateStart);
                    const endDate = new Date(window.LAUNCH_DATES.rangeDateEnd);
                    const startDay = startDate.getDate();
                    const endDay = endDate.getDate();
                    const displayDate = startDay + '-' + endDay;
                    const monthName = startDate.toLocaleString('id-ID', { month: 'long' });
                    const isoDate = startDate.toISOString().split('T')[0];

                    // Only update if current value is wrong
                    if (calendarDate.textContent.trim() !== displayDate) {
                        calendarLabel.textContent = window.LAUNCH_DATES.launchTitle || 'Penganugerahan Sigap Award';
                        calendarDate.textContent = displayDate;
                        calendarMonth.textContent = monthName.charAt(0).toUpperCase() + monthName.slice(1);
                        calendarDate.setAttribute('datetime', isoDate);

                        // Stop any GSAP animations on these elements
                        if (window.gsap) {
                            gsap.killTweensOf(calendarDate);
                            gsap.killTweensOf(calendarMonth);
                        }
                    }
                }
            }

            // Force immediately and continuously
            forceCalendarDisplay();

            // Keep forcing every 100ms to override any animations
            const intervalId = setInterval(forceCalendarDisplay, 100);

            // Also use MutationObserver to catch any changes
            setTimeout(function() {
                const calendarDate = document.querySelector('.launch-date__calendar-date');
                if (calendarDate) {
                    const observer = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {
                            if (mutation.type === 'childList' || mutation.type === 'characterData') {
                                forceCalendarDisplay();
                            }
                        });
                    });

                    observer.observe(calendarDate, {
                        childList: true,
                        characterData: true,
                        subtree: true
                    });
                }
            }, 500);
        }
    });

    // Welcome Modal Auto Show
    document.addEventListener('DOMContentLoaded', function() {
        const welcomeModal = document.getElementById('welcomeModal');

        // If welcome modal exists, initialize it
        if (welcomeModal) {
            const welcomeCloseBtn = document.getElementById('welcome-close');
            const welcomeModalClose = welcomeModal.querySelector('.modal-close');
            const categoryOptions = document.querySelectorAll('.category-option');

            // Function to show welcome modal
            function showWelcomeModal() {
                welcomeModal.style.display = 'flex';
                setTimeout(() => {
                    welcomeModal.classList.add('show');
                }, 10);
                // schedule reminder 3s after welcome shown
                setTimeout(() => {
                    if (typeof window.showReminderModal === 'function') {
                        window.showReminderModal();
                    }
                }, 3000);
            }

            // Function to hide welcome modal
            function hideWelcomeModal() {
                welcomeModal.classList.remove('show');
                setTimeout(() => {
                    welcomeModal.style.display = 'none';
                }, 300);
            }

            // Show modal after a short delay to ensure page is fully loaded
            setTimeout(() => {
                showWelcomeModal();
            }, 3000); // 3 second delay to show welcome

            // Add click events to category options
            categoryOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const link = this.getAttribute('data-link');

                    if (link) {
                        hideWelcomeModal();
                        setTimeout(() => {
                            window.open(link, '_blank');
                        }, 350);
                    }
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
        } else {
            // If welcome modal doesn't exist, trigger reminder modal directly
            setTimeout(() => {
                if (typeof window.showReminderModal === 'function') {
                    window.showReminderModal();
                }
            }, 3000); // Show reminder after 3 seconds if no welcome modal
        }
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
