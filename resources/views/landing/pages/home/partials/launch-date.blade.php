@push('styles')

<style>
.swiper-slide-title{
    color: var(--sigap-color) !important;
}


</style>

@endpush

<div class="box launch-date">
                            <div class="launch-date__content">
                                <div class="launch-date__slider">
                                    <div class="launch-date__slider-content">
                                        <div class="swiper">
                                            <div class="swiper-wrapper">
                                                <div class="swiper-slide">
                                                    <h2 class="swiper-slide-title">IGIF</h2>
                                                    <p>Kerangka implementasi</p>
                                                </div>
                                                <div class="swiper-slide">
                                                    <h2 class="swiper-slide-title">IDS</h2>
                                                    <p>Fondasi data spasial</p>
                                                </div>
                                                <div class="swiper-slide">
                                                    <h2 class="swiper-slide-title">IGT</h2>
                                                    <p>Data tematik kehutanan</p>
                                                </div>
                                            </div>
                                            <div class="swiper-pagination"></div>
                                        </div>
                                    </div>
                                    <div class="launch-date__slider-left-part"></div>
                                    <div class="launch-date__slider-right-part"></div>
                                </div>
                                <div class="launch-date__calendar">
                                    <div class="launch-date__calendar-content">
                                        {{-- Batas Pengumpulan 16 Oktober 2025 --}}
                                        <span class="launch-date__calendar-label">Tanggal Sosialisasi</span>
                                        <time class="launch-date__calendar-date" datetime="{{ optional($launchFinish)->format('Y-m-d') ?? '2025-10-10' }}">{{ optional($launchFinish)->format('d') ?? '10' }}</time>
                                        <p class="launch-date__calendar-month">{{ optional($launchFinish)->translatedFormat('F') ?? 'Oktober' }}</p>
                                    </div>
                                    <div class="launch-date__calendar-left-part"></div>
                                    <div class="launch-date__calendar-right-part"></div>
                                </div>
                            </div>
</div>
