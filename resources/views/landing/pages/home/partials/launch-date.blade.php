@push('styles')

<style>
.swiper-slide-title{
    color: var(--sigap-color) !important;
}

/* FORCE CALENDAR DISPLAY - NUCLEAR OPTION */
.launch-date__calendar-label::after {
    content: '' !important;
}
.launch-date__calendar-date::after {
    content: '' !important;
}
.launch-date__calendar-month::after {
    content: '' !important;
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
                                        @if(isset($launchDate) && $launchDate)
                                            <span class="launch-date__calendar-label">{{ $launchDate->title ?? 'No Title' }}</span>
                                            <time class="launch-date__calendar-date" datetime="{{ $launchDate->datetime ?? '' }}">
                                                {{ $launchDate->formatted_date ?? '-' }}
                                            </time>
                                            <p class="launch-date__calendar-month">{{ $launchDate->month_name ?? '-' }}</p>
                                        @else
                                            {{-- FALLBACK: Default static content --}}
                                            <span class="launch-date__calendar-label">Sigap Award</span>
                                            <time class="launch-date__calendar-date" datetime="2025-10-23">
                                                23-24
                                            </time>
                                            <p class="launch-date__calendar-month">Oktober</p>
                                        @endif
                                    </div>
                                    <div class="launch-date__calendar-left-part"></div>
                                    <div class="launch-date__calendar-right-part"></div>
                                </div>
                            </div>
</div>
