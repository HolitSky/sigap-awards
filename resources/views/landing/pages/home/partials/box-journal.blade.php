<div class="box journal">
        <div class="journal__content">
                                <div class="journal__header">
                                    <div class="journal__header-label">Update Terkini dari Kami</div>
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
                                                    <div class="journal__post-text">
                                                        @foreach($journal['content'] as $paragraph)
                                                            <p>{{ $paragraph }}</p>
                                                        @endforeach
                                                    </div>
                                                </article>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
       </div>
</div>
