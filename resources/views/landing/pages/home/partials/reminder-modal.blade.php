@push('styles')
<style>
    /* Reminder modal variant */
    .modal-reminder { z-index: 10001; }
    .modal-reminder .modal-content {
        background: linear-gradient(135deg, #F3C275 0%, #F6D5A6 100%);
        border-radius: 16px;
        border: 0;
        box-shadow: 0 14px 40px rgba(0,0,0,0.22);
        overflow: hidden;
        max-width: 720px; /* override generic modal max-width */
        width: 95%;
    }
    .modal-reminder .modal-header,
    .modal-reminder .modal-body,
    .modal-reminder .modal-footer { background: transparent; border: 0 !important; }
    .modal-reminder .modal-header { display: flex; justify-content: flex-end; padding: 8px 12px; }
    .modal-reminder .modal-close { cursor: pointer; font-size: 22px; line-height: 1; color: #111; }
    .modal-reminder .modal-body { padding: 28px 24px 22px; text-align: center; }
    .modal-reminder .modal-footer { padding: 10px 16px 18px; display: flex; justify-content: center; }

    .reminder-image {
        display: block;
        margin: 0 auto 16px;
        width: clamp(160px, 38%, 240px);
        max-width: 100%;
        height: auto !important;
        object-fit: contain !important;
        aspect-ratio: auto !important; /* cancel global ratio */
    }
    .reminder-title { color: #000000; font-size: 28px; line-height: 1.25; margin: 0 0 10px; font-weight: 800; }
    .reminder-subtitle { color: #E37828; font-size: 20px; line-height: 1.35; margin: 0; font-weight: 700; }

    @media (max-width: 576px) {
        .reminder-title { font-size: 24px; }
        .reminder-subtitle { font-size: 18px; }
    }
</style>
@endpush

<!-- Reminder Modal -->
<div id="reminderModal" class="modal-overlay modal-reminder" style="display: none;">
    <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="reminderTitle">
        <div class="modal-header">
            <span class="modal-close" aria-label="Tutup">&times;</span>
        </div>
        <div class="modal-body">
            <img src="{{ asset('sigap-assets/images/image-info-for-poster.png') }}" class="reminder-image" alt="Informasi poster" loading="lazy">
            <h1 id="reminderTitle" class="reminder-title">Hai #SobatIPSDH !</h1>
            <h3 class="reminder-subtitle">Jangan lupa segera isi kuesioner dan data dukung serta desain poster juga ya!</h3>
        </div>
        <div class="modal-footer">
            <button type="button" class="modal-btn-close" aria-label="Tutup">Tutup</button>
        </div>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var reminderModal = document.getElementById('reminderModal');
    var closeIcon = reminderModal ? reminderModal.querySelector('.modal-close') : null;
    var closeBtn = reminderModal ? reminderModal.querySelector('.modal-btn-close') : null;

    function showReminderModal() {
        if (!reminderModal) return;
        reminderModal.style.display = 'flex';
        setTimeout(function () { reminderModal.classList.add('show'); }, 10);
        window._reminderShown = true;
    }

    function hideReminderModal() {
        if (!reminderModal) return;
        reminderModal.classList.remove('show');
        setTimeout(function () { reminderModal.style.display = 'none'; }, 300);
    }

    if (closeIcon) closeIcon.addEventListener('click', hideReminderModal);
    if (closeBtn) closeBtn.addEventListener('click', hideReminderModal);
    if (reminderModal) reminderModal.addEventListener('click', function (e) { if (e.target === reminderModal) hideReminderModal(); });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && reminderModal && reminderModal.classList.contains('show')) hideReminderModal();
    });

    // expose to global
    window.showReminderModal = showReminderModal;
    window.hideReminderModal = hideReminderModal;
});
</script>
@endpush


