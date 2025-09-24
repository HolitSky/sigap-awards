@push('styles')
<style>
    .journal__post-description {
        margin: 15px 0;
        padding: 15px;
        background: rgba(102, 126, 234, 0.05);
        border-left: 4px solid #667eea;
        border-radius: 0 8px 8px 0;
    }

    .journal__post-description p {
        margin: 0;
        color: #555;
        font-style: italic;
        line-height: 1.6;
    }

    .journal__post-link {
        margin-top: 20px;
        text-align: center;
    }

    .journal__link-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-decoration: none;
        padding: 12px 24px;
        border-radius: 25px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .journal__link-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        color: white;
        text-decoration: none;
    }

    .journal__link-btn i {
        font-size: 0.9em;
    }
</style>
@endpush

<div class="box journal">
        <div class="journal__content">
                                <div class="journal__header">
                                    <div class="journal__header-label">Informasi Penting</div>
                                    <div class="journal__header-icon">
                                        {{-- <a href="javascript:void(0);">
                                            <i class="fa-brands fa-github"></i>
                                        </a> --}}
                                    </div>
                                </div>
                                <div class="journal__posts">
                                    <div class="journal__posts-wrapper">
                                        @if(isset($journalData) && is_array($journalData))
                                            @foreach($journalData as $journal)
                                                <article class="journal__post">
                                                    <header class="journal__post-heading">
                                                        <div class="journal__post-date">
                                                            <time class="journal__post-day" datetime="{{ $journal['date'] }}">{{ \Carbon\Carbon::parse($journal['date'])->format('d M Y') }}</time>
                                                            <time class="journal__post-time" datetime="{{ $journal['date'] }}T{{ $journal['time'] }}">{{ \Carbon\Carbon::parse($journal['time'])->format('g:i A') }}</time>
                                                        </div>
                                                    </header>
                                                    <h1 class="journal__post-header">{{ $journal['title'] }}</h1>
                                                    <figure class="journal__post-image">
                                                        <img src="{{ asset('sigap-assets/images/' . $journal['image']) }}" alt="{{ $journal['title'] }}">
                                                    </figure>
                                                    <div class="journal__post-content">
                                                        @if(isset($journal['content']['deskripsi']))
                                                            <div class="journal__post-description">
                                                                <p>{{ $journal['content']['deskripsi'] }}</p>
                                                            </div>
                                                        @endif

                                                        @if(isset($journal['content']['paragraph']))
                                                            <div class="journal__post-text">
                                                                <p>{{ $journal['content']['paragraph'] }}</p>
                                                            </div>
                                                        @endif

                                                        @if(isset($journal['content']['link']))
                                                            <div class="journal__post-link">
                                                                <a href="{{ $journal['content']['link'] }}" target="_blank" class="journal__link-btn">
                                                                    <i class="fas fa-external-link-alt"></i>
                                                                    {{ $journal['btn-text'] }}
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </article>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
       </div>
</div>
