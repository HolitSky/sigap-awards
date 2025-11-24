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

/* Prevent JS override for special date types */
.launch-date-month-only,
.launch-date-coming-soon {
    pointer-events: none !important;
}

/* Month Only specific styles */
.launch-date__calendar-month.launch-date-month-only {
    font-size: 2.5em !important;
    margin-top: 30px !important;
    font-weight: bold !important;
    text-transform: uppercase !important;
}

.launch-date__calendar-date.launch-date-month-only {
    display: none !important;
    height: 0 !important;
    margin: 0 !important;
    padding: 0 !important;
}

/* Coming Soon specific styles */
.launch-date__calendar-date.launch-date-coming-soon,
.launch-date__calendar-month.launch-date-coming-soon {
    font-size: 2em !important;
    font-weight: bold !important;
    text-transform: uppercase !important;
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

                                            @if($launchDate->date_type == 'month_only')
                                                {{-- Month Only: No date number, only month name --}}
                                                <time class="launch-date__calendar-date launch-date-month-only" datetime="{{ $launchDate->month_year ?? '' }}" style="display: none !important;"></time>
                                                <p class="launch-date__calendar-month launch-date-month-only" style="font-size: 2.5em !important; margin-top: 30px !important; font-weight: bold;">{{ strtoupper($launchDate->month_name ?? '-') }}</p>
                                            @elseif($launchDate->date_type == 'coming_soon')
                                                {{-- Coming Soon: Display "COMING SOON" text --}}
                                                <time class="launch-date__calendar-date launch-date-coming-soon" datetime="" style="font-size: 2em !important; font-weight: bold;">{{ $launchDate->formatted_date }}</time>
                                                <p class="launch-date__calendar-month launch-date-coming-soon" style="font-size: 2em !important; font-weight: bold;">{{ $launchDate->month_name }}</p>
                                            @else
                                                {{-- Single or Range Date: Normal display --}}
                                                <time class="launch-date__calendar-date" datetime="{{ $launchDate->datetime ?? '' }}">
                                                    {{ $launchDate->formatted_date ?? '-' }}
                                                </time>
                                                <p class="launch-date__calendar-month">{{ $launchDate->month_name ?? '-' }}</p>
                                            @endif
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
