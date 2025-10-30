@push('styles')
<!-- GLightbox CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
<style>
    /* Floating animation for desktop (up & down) */
    @keyframes floating {
        0% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-20px);
        }
        100% {
            transform: translateY(0px);
        }
    }

    /* Floating animation for mobile (left & right) */
    @keyframes floatingHorizontal {
        0% {
            transform: translateX(0px);
        }
        50% {
            transform: translateX(-15px);
        }
        100% {
            transform: translateX(0px);
        }
    }

    /* Mobile: horizontal floating (< 768px) */
    @media (max-width: 767px) {
        .about-team__image img {
            animation: floatingHorizontal 3s ease-in-out infinite;
        }
    }

    /* Desktop/Laptop: vertical floating (≥ 768px) */
    @media (min-width: 768px) {
        .about-team__image img {
            animation: floating 3s ease-in-out infinite;
        }
    }
</style>
@endpush

<div class="box about-team">
                            <div class="about-team__content">
                                <div class="about-team__info">
                                    <div class="about-team__label">Tentang Juri</div>
                                    <p class="about-team__description">{{ $teamData['description'] }}</p>
                                </div>
                                <div class="about-team__image">
                                    <picture>
                                        <img src="{{ asset('sigap-assets/images/icon-sigap-3d.png') }}" alt="Featured face image">
                                    </picture>
                                </div>
                            </div>
                            <div class="about-team__users">
                                <div class="about-team__users-header">
                                    <div class="about-team__users-btn-close">
                                        <svg viewBox="0 0 24 24" fill="none" class="close-popup" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M4.10083 1.36702C3.34584 0.612034 2.12177 0.612034 1.36678 1.36702C0.61179 2.12201 0.61179 3.34609 1.36678 4.10108L9.26579 12.0001L1.36695 19.8989C0.611958 20.6539 0.611958 21.878 1.36695 22.633C2.12193 23.388 3.34601 23.388 4.101 22.633L11.9998 14.7341L19.8987 22.633C20.6537 23.388 21.8777 23.388 22.6327 22.633C23.3877 21.878 23.3877 20.6539 22.6327 19.8989L14.7339 12.0001L22.6329 4.10109C23.3879 3.3461 23.3879 2.12203 22.6329 1.36704C21.8779 0.612051 20.6538 0.612051 19.8988 1.36704L11.9998 9.26603L4.10083 1.36702Z" fill="#AD5E00"/>
                                        </svg>
                                    </div>
                                </div><div class="about-team__users-content">
                                    <ul class="about-team__cards">
                                        @foreach($teamData['teams'] as $member)
                                        <li class="about-team__card">
                                            <div class="about-team__card-avatar">
                                                <a href="{{ asset('sigap-assets/images/' . $member['avatar']) }}"
                                                   data-fancybox="team-gallery"
                                                   data-caption="{{ $member['name'] }} - {{ $member['position'] }}" onclick="event.stopPropagation();">
                                                    <picture>
                                                        <source srcset="{{ asset('sigap-assets/images/' . $member['avatar']) }}" type="image/webp">
                                                        <img src="{{ asset('sigap-assets/images/' . $member['avatar']) }}" alt="{{ $member['name'] }}" style="cursor: pointer;">
                                                    </picture>
                                                </a>
                                            </div>
                                            <div class="about-team__card-details">
                                                <div class="about-team__card-name">
                                                    <a href="{{ $member['social_links']['instagram'] }}" onclick="event.stopPropagation();" target="_blank">
                                                        {{ $member['name'] }}
                                                    </a>
                                                </div>
                                                <div class="about-team__card-position">{{ $member['position'] }}</div>
                                                <ul class="about-team__card-social-links">
                                                    @if($member['social_links']['email'] !== 'javascript:void(0);')
                                                    <li class="about-team__email"><a href="mailto:{{ $member['social_links']['email'] }}" target="_blank" onclick="event.stopPropagation();"><i class="fa-solid fa-envelope"></i></a></li>
                                                    @endif
                                                    @if($member['social_links']['linkedin'] !== 'javascript:void(0);')
                                                    <li class="about-team__linkedin"><a href="{{ $member['social_links']['linkedin'] }}" target="_blank" onclick="event.stopPropagation();"><i class="fa-brands fa-linkedin"></i></a></li>
                                                    @endif
                                                    @if($member['social_links']['instagram'] !== 'javascript:void(0);')
                                                    <li class="about-team__instagram"><a href="{{ $member['social_links']['instagram'] }}" target="_blank" onclick="event.stopPropagation();"><i class="fa-brands fa-instagram"></i></a></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>


                        @push('scripts')
                         <!-- GLightbox JS -->
                         <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
                         <script>
                             // Initialize GLightbox on existing anchors
                             const lightbox = GLightbox({
                                 selector: '[data-fancybox="team-gallery"]'
                             });
                         </script>
                         @endpush
