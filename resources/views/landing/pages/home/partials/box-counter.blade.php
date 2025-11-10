@push('styles')
<style>
    .vote-menu { text-align: center; padding: 12px 0; }
    .vote-menu__title { color: var(--sigap-color); margin: 0 0 8px; font-size: 34px; }
    .vote-menu__desc { color: var(--sigap-color); opacity: 0.85; margin: 0 0 20px; font-size: 20px; }
    .vote-menu__btn { display: inline-block; background-color: var(--sigap-color); color: #fff; padding: 12px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; }
    .vote-menu__btn:hover { opacity: 0.9; }

    /* Constrain modal to the card container (box counter) */
    .box.counter { position: relative; }
    .box.counter .modal-overlay {
        position: absolute; /* keep inside card */
        inset: 0; /* top/right/bottom/left: 0 */
        display: none; /* toggled by JS */
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.35);
        z-index: 5;
        padding: 16px; /* breathing room on small screens */
        border-radius: inherit; /* follow card rounding if any */
    }
    .box.counter .modal-overlay.show .modal-content { opacity: 1; transform: translateY(0); }
    .box.counter .modal-content {
        width: 100%;
        max-width: 460px; /* desktop/tablet limit inside card */
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 24px rgba(0,0,0,0.18);
        transform: translateY(8px);
        opacity: 0;
        transition: opacity .25s ease, transform .25s ease;
        overflow: hidden;
    }
    .box.counter .modal-header,
    .box.counter .modal-footer { padding: 12px 16px; background: #fff; }
    .box.counter .modal-header { display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #eee; }
    .box.counter .modal-body { padding: 16px; background: #fff; }
    .box.counter .modal-close { cursor: pointer; font-size: 20px; line-height: 1; }
    .box.counter .modal-footer { display: flex; gap: 8px; justify-content: flex-end; border-top: 1px solid #eee; }
    .box.counter .modal-btn-open { background: var(--sigap-color); color: #fff; border: 0; border-radius: 8px; padding: 10px 14px; font-weight: 600; }
    .box.counter .modal-btn-close { background: #edf2f7; color: #111; border: 0; border-radius: 8px; padding: 10px 14px; font-weight: 600; }

    /* Mobile-first fit: always keep content within the card width */
    @media (max-width: 640px) {
        .box.counter .modal-overlay { padding: 12px; }
        .box.counter .modal-content { max-width: none; width: 100%; border-radius: 10px; }
    }
</style>
@endpush

<div class="box counter">
    <div class="counter__content">
        {{--
                                <!-- Calender component -->
                                <ul class="counter__month"></ul>

                                <!-- Counter component -->
                                <div class="counter__countdown">
                                    <ul class="counter__countdown-timer">
                                        <li id="days">000<span>days</span></li>
                                        <li class="counter__countdown-colon">:</li>
                                        <li id="hours">00<span>hours</span></li>
                                        <li class="counter__countdown-colon">:</li>
                                        <li id="minutes">00<span>minutes</span></li>
                                        <li class="counter__countdown-colon">:</li>
                                        <li id="seconds">00<span>seconds</span></li>
                                        <li id="ongoingLabel" style="display: none;">On Going 🎯</li>
                                    </ul>
                                    <div class="counter__countdown-decoration">
                                        <div class="counter__countdown-decoration-arrow"></div>
                                        <div class="counter__countdown-decoration-arrow"></div>
                                        <div class="counter__countdown-decoration-arrow"></div>
                                    </div>
                                    <div class="counter__countdown-percent">0<span>%</span></div>
                                </div>

                                <!-- Progresbar component -->
                                <div class="counter__progressbar">
                                    <progress id="progressBar" class="counter__progress" max="100" value="0"></progress>
                                    <div id="endTip"></div>
                                </div>
        --}}

        @forelse($cardBoxes as $cardBox)
        <div class="vote-menu">
            <h3 class="vote-menu__title">{{ $cardBox->title }}</h3>
            <p class="vote-menu__desc">{{ $cardBox->description }}</p>
            
            @if($cardBox->content_type === 'text_only')
                {{-- No button for text only --}}
            @elseif($cardBox->content_type === 'link')
                <a href="{{ $cardBox->link_url }}" target="_blank" class="vote-menu__btn">{{ $cardBox->button_text }}</a>
            @elseif($cardBox->content_type === 'modal')
                <a href="javascript:void(0);" class="vote-menu__btn open-box-modal" data-modal-id="cardBoxModal{{ $cardBox->id }}" aria-expanded="false">{{ $cardBox->button_text }}</a>
            @endif
        </div>
        @empty
        <div class="vote-menu">
            <h3 class="vote-menu__title">Vote Pengelola IGT Terbaik 2025</h3>
            <p class="vote-menu__desc">Klik tombol di bawah untuk menuju halaman voting.</p>
            <a href="javascript:void(0);" id="openVoteConfirm" class="vote-menu__btn" aria-controls="voteConfirmModal" aria-expanded="false">Buka Halaman Voting</a>
        </div>
        @endforelse

    </div>

    @foreach($cardBoxes as $cardBox)
        @if($cardBox->content_type === 'modal')
        <!-- Modal for {{ $cardBox->title }} -->
        <div id="cardBoxModal{{ $cardBox->id }}" class="modal-overlay" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>{{ $cardBox->title }}</h3>
                    <span class="modal-close" aria-label="Tutup">&times;</span>
                </div>
                <div class="modal-body">
                    {!! nl2br(e($cardBox->modal_content)) !!}
                </div>
                <div class="modal-footer">
                    <button class="modal-btn-close" type="button">Tutup</button>
                </div>
            </div>
        </div>
        @endif
    @endforeach

    <!-- Default Vote Confirmation Modal (fallback) -->
    <div id="voteConfirmModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Konfirmasi</h3>
                <span class="modal-close" aria-label="Tutup">&times;</span>
            </div>
            <div class="modal-body">
                <p>Apakah Anda ingin menuju halaman?</p>
            </div>
            <div class="modal-footer">
                <button class="modal-btn-close" type="button">Batal</button>
                <button class="modal-btn-open" id="btnGoVote" type="button">Ya, lanjut</button>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    function getNumericValue(id) {
        var el = document.getElementById(id);
        if (!el) return 0;
        var text = el.textContent || '';
        var num = parseInt(text, 10);
        return isNaN(num) ? 0 : num;
    }

    function toggleCountdownState() {
        var days = getNumericValue('days');
        var hours = getNumericValue('hours');
        var minutes = getNumericValue('minutes');
        var seconds = getNumericValue('seconds');

        var progressEl = document.getElementById('progressBar');
        var progressVal = 0;
        if (progressEl) {
            // Support both attribute and property value
            var attrVal = progressEl.getAttribute('value');
            progressVal = parseFloat(attrVal || progressEl.value || 0) || 0;
        }

        var ended = (progressVal >= 100) || (days <= 0 && hours <= 0 && minutes <= 0 && seconds <= 0);

        // Toggle visibility of countdown items
        ['days', 'hours', 'minutes', 'seconds'].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) el.style.display = ended ? 'none' : '';
        });
        document.querySelectorAll('.counter__countdown-colon').forEach(function (el) {
            el.style.display = ended ? 'none' : '';
        });

        // Toggle On Going label
        var ongoing = document.getElementById('ongoingLabel');
        if (ongoing) ongoing.style.display = ended ? '' : 'none';
    }

    // Initial run and periodic check while counting progresses
    toggleCountdownState();
    setInterval(toggleCountdownState, 1000);
});
</script>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Generic modal handler for all box counter modals
    function showModal(modal, trigger) {
        if (!modal) return;
        modal.style.display = 'flex';
        setTimeout(function () { modal.classList.add('show'); }, 10);
        if (trigger) trigger.setAttribute('aria-expanded', 'true');
    }

    function hideModal(modal, trigger) {
        if (!modal) return;
        modal.classList.remove('show');
        setTimeout(function () { modal.style.display = 'none'; }, 300);
        if (trigger) trigger.setAttribute('aria-expanded', 'false');
    }

    // Handle all open-box-modal buttons
    document.querySelectorAll('.open-box-modal').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var modalId = this.getAttribute('data-modal-id');
            var modal = document.getElementById(modalId);
            showModal(modal, this);
        });
    });

    // Handle all modal close buttons and icons
    document.querySelectorAll('.modal-overlay').forEach(function(modal) {
        var closeIcon = modal.querySelector('.modal-close');
        var closeBtn = modal.querySelector('.modal-btn-close');
        
        if (closeIcon) {
            closeIcon.addEventListener('click', function() {
                hideModal(modal);
            });
        }
        
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                hideModal(modal);
            });
        }
        
        // Close on backdrop click
        modal.addEventListener('click', function(e) {
            if (e.target === modal) hideModal(modal);
        });
    });

    // Default vote modal (fallback)
    var voteModal = document.getElementById('voteConfirmModal');
    var openBtn = document.getElementById('openVoteConfirm');
    var goBtn = document.getElementById('btnGoVote');
    var targetUrl = 'https://form.sigap-award.site/voting2025';

    if (openBtn) {
        openBtn.addEventListener('click', function (e) { 
            e.preventDefault(); 
            showModal(voteModal, openBtn); 
        });
    }

    if (goBtn) {
        goBtn.addEventListener('click', function () { 
            window.open(targetUrl, '_blank'); 
            hideModal(voteModal, openBtn); 
        });
    }

    // ESC key to close any open modal
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.show').forEach(function(modal) {
                hideModal(modal);
            });
        }
    });
});
</script>
@endpush
