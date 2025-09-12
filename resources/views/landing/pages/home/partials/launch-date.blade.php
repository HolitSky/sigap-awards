<div class="box launch-date">
                            <div class="launch-date__content">
                                <div class="launch-date__slider">
                                    <div class="launch-date__slider-content">
                                        <div class="swiper">
                                            <div class="swiper-wrapper">
                                                <div class="swiper-slide">
                                                    <h2 class="swiper-slide-title">537<span>+</span></h2>
                                                    <p>Successful Projects Completed</p>
                                                </div>
                                                <div class="swiper-slide">
                                                    <h2 class="swiper-slide-title">238<span>+</span></h2>
                                                    <p>Multiple Award-Winning Designs</p>
                                                </div>
                                                <div class="swiper-slide">
                                                    <h2 class="swiper-slide-title">10,000</h2>
                                                    <p>Global Clients All Over The World</p>
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
                                        <span class="launch-date__calendar-label">Launch Date</span>
                                        <time class="launch-date__calendar-date" datetime="{{ optional($launchFinish)->format('Y-m-d') ?? '2025-10-10' }}">{{ optional($launchFinish)->format('d') ?? '10' }}</time>
                                        <p class="launch-date__calendar-month">{{ optional($launchFinish)->translatedFormat('F') ?? 'Oktober' }}</p>
                                    </div>
                                    <div class="launch-date__calendar-left-part"></div>
                                    <div class="launch-date__calendar-right-part"></div>
                                </div>
                            </div>
</div>
