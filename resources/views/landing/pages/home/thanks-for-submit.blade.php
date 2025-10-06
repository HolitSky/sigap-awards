@extends('landing.layouts.app')
@section('content')

@push('styles')

<style>
    .thanks-wrapper {
        min-height: 70vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 40px 16px;
        /* center within .wrapper grid */
        grid-column: 1 / -1;
        justify-self: center;
        align-self: center;
    }
    .thanks-message {
        color: var(--white);
        font-size: 20px;
        line-height: 1.6;
        max-width: 720px;
        margin: 0 auto 24px;
    }
    .thanks-title {
        color: var(--white);
        font-size: 32px;
        line-height: 1.3;
        max-width: 720px;
        margin: 0 auto 12px;
    }
    .thanks-lottie {
        width: 260px;
        height: 260px;
        margin: 0 auto 24px;
    }
    .btn-back-home {
        display: inline-block;
        background-color: var(--sigap-color);
        color: #fff;
        padding: 12px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
    }
    .btn-back-home:hover {
        opacity: 0.9;
    }
</style>
@endpush

<div class="wrapper">
    <div class="thanks-wrapper">
        <h1 class="thanks-title">Terima kasih atas dukungannya, suara Anda untuk Pengelola IGT Terbaik 2025 sudah tercatat.</h1>
        <p class="thanks-message">Nantikan pengumuman resminya, ya!</p>
        <div id="thanks-lottie" class="thanks-lottie" aria-label="success animation"></div>
        <a href="{{ route('home') }}" class="btn-back-home">Kembali ke Home</a>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/lottie-web@5.12.2/build/player/lottie.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var container = document.getElementById('thanks-lottie');
        if (container && window.lottie) {
            window.lottie.loadAnimation({
                container: container,
                renderer: 'svg',
                loop: true,
                autoplay: true,
                path: '{{ asset('sigap-assets/animation/Congratulation%20_%20Success%20batch.json') }}'
            });
        }
    });
</script>
@endpush



@endsection
