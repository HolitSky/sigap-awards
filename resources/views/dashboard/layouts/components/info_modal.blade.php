<style>
.reminder-title { color: #000000; font-size: 28px; line-height: 1.25; margin: 0 0 10px; font-weight: 800; }
.reminder-subtitle { color: #E37828; font-size: 20px; line-height: 1.35; margin: 0; font-weight: 700; }
.reminder-image {
    display: block;
    margin: 0 auto 16px;
    width: clamp(160px, 38%, 240px);
    max-width: 100%;
    height: auto !important;
    object-fit: contain !important;
    aspect-ratio: auto !important;
}
#subscribeModal .modal-btn-close {
    background: linear-gradient(135deg, #E37828 0%, #D96B1F 100%) !important;
    color: #ffffff !important;
    border: none !important;
    padding: 14px 48px !important;
    border-radius: 30px !important;
    font-weight: 700 !important;
    font-size: 17px !important;
    cursor: pointer;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 15px rgba(227, 120, 40, 0.25) !important;
    text-transform: none !important;
    letter-spacing: 0.3px;
    display: inline-block;
    line-height: 1.5;
}
#subscribeModal .modal-btn-close:hover {
    background: linear-gradient(135deg, #D96B1F 0%, #C25E18 100%) !important;
    color: #ffffff !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(227, 120, 40, 0.4) !important;
}
#subscribeModal .modal-btn-close:active {
    background: linear-gradient(135deg, #C25E18 0%, #B05316 100%) !important;
    transform: translateY(0) !important;
    box-shadow: 0 2px 10px rgba(227, 120, 40, 0.3) !important;
}
</style>

<!-- infodashboardModal -->
<div class="modal fade" id="subscribeModal" tabindex="-1" aria-labelledby="subscribeModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 700px;">
       <div class="modal-content" style="background: linear-gradient(135deg, #F3C275 0%, #F6D5A6 100%); border: none; border-radius: 20px; overflow: hidden; min-height: 450px;">
           <div class="modal-header border-bottom-0">
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <div class="modal-body p-4 pb-2">
               <div class="text-center">
                   <img src="{{ asset('sigap-assets/images/image-info-for-poster.png') }}" class="img-fluid mb-4" style="max-width: 100%; height: auto; max-height: 350px; object-fit: contain;" alt="Informasi poster" loading="lazy">
                   <h1 class="reminder-title">Selamat datang, Juri/Admin Sigap Award!</h1>
                   <h3 class="reminder-subtitle">Silakan melanjutkan penilaian form yang masuk dan verifikasi data pendukung.</h3>
               </div>
           </div>
           <div class="modal-footer border-top-0 justify-content-center pb-4">
               <button type="button" class="modal-btn-close" data-bs-dismiss="modal" aria-label="Tutup">Tutup</button>
           </div>
       </div>
   </div>
</div>
<!-- end modal -->
