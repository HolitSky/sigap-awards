{{-- All Poster Here --}}

@extends('landing.layouts.app')
@section('content')

@push('styles')
<style>
    /* FORCE override all container styles */
    html, body {
        height: auto !important;
        min-height: 100vh !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
    }

    body {
        display: flex !important;
        flex-direction: column !important;
    }

    #primary {
        flex: 1 0 auto !important;
        width: 100% !important;
        height: auto !important;
        overflow: visible !important;
    }

    .wrapper {
        min-height: auto !important;
        height: auto !important;
        max-height: none !important;
        display: block !important;
        grid-template-columns: 1fr !important;
        overflow: visible !important;
    }

    .announcement-wrapper {
        padding: 80px 20px 100px;
        width: 100%;
        max-width: 100%;
        overflow-x: hidden;
        overflow-y: visible;
        min-height: auto;
        height: auto;
    }

    .announcement-header {
        text-align: center;
        margin-bottom: 60px;
        animation: fadeInDown 0.8s ease-out;
    }

    .announcement-title {
        color: var(--white);
        font-size: 42px;
        font-weight: 700;
        margin-bottom: 16px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        line-height: 1.2;
    }

    .announcement-subtitle {
        color: var(--white);
        font-size: 20px;
        opacity: 0.9;
    }

    .announcement-icon {
        font-size: 80px;
        line-height: 1;
        margin: 0 auto 20px;
        animation: bounce 2s infinite;
        display: block;
        text-align: center;
    }

    .categories-container {
        max-width: 1400px;
        margin: 0 auto 80px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(550px, 1fr));
        gap: 40px;
    }

    .category-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 32px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        animation: fadeInUp 0.8s ease-out;
        display: flex;
        flex-direction: column;
        height: auto;
    }

    .category-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 48px rgba(0, 0, 0, 0.2);
        border-color: var(--sigap-color);
    }

    .category-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid rgba(255, 255, 255, 0.2);
    }

    .category-icon {
        width: 48px;
        height: 48px;
        background: var(--sigap-color);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .category-title {
        color: var(--white);
        font-size: 22px;
        font-weight: 700;
        margin: 0;
    }

    .category-count {
        background: var(--sigap-color);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        margin-left: auto;
    }

    .btn-back-home {
        display: inline-block;
        background-color: var(--sigap-color);
        color: #fff;
        padding: 14px 32px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        font-size: 16px;
        margin-top: 48px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
    }

    .btn-back-home:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.3);
        opacity: 0.95;
    }

    .back-home-container {
        text-align: center;
        margin-top: 60px;
        padding-bottom: 20px;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes bounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }

    @media (max-width: 1400px) {
        .categories-container {
            max-width: 1200px;
            gap: 32px;
        }
    }

    @media (max-width: 1200px) {
        .categories-container {
            grid-template-columns: 1fr;
            max-width: 800px;
            gap: 32px;
        }

        .announcement-wrapper {
            padding: 60px 20px 80px;
        }
    }

    @media (max-width: 1024px) {
        .categories-container {
            grid-template-columns: 1fr;
            max-width: 700px;
            gap: 28px;
        }
    }

    @media (max-width: 768px) {
        .announcement-wrapper {
            padding: 50px 16px 60px;
        }

        .announcement-title {
            font-size: 32px;
        }

        .announcement-subtitle {
            font-size: 16px;
        }

        .announcement-icon {
            font-size: 64px;
        }

        .categories-container {
            grid-template-columns: 1fr;
            max-width: 100%;
            gap: 24px;
            margin-bottom: 60px;
        }

        .category-card {
            padding: 24px;
        }

        .category-title {
            font-size: 20px;
        }
    }

    @media (max-width: 480px) {
        .announcement-title {
            font-size: 26px;
        }

        .categories-container {
            gap: 20px;
        }

        .category-card {
            padding: 20px;
        }
    }

    /* Table styles (adapted from result-presentation) */
    .table-wrapper { width: 100%; overflow-x: auto; }
    table.sheet-table { width: 100%; border-collapse: collapse; }
    table.sheet-table thead th {
        position: sticky; top: 0; z-index: 1;
        background: rgba(255,255,255,0.15);
        color: var(--white);
        text-align: left;
        padding: 12px 14px;
        border-bottom: 2px solid rgba(255,255,255,0.2);
        font-weight: 700; font-size: 14px;
    }
    table.sheet-table tbody td {
        color: var(--white);
        padding: 12px 14px;
        border-bottom: 1px solid rgba(255,255,255,0.12);
        vertical-align: top; font-size: 14px;
    }
    table.sheet-table tbody tr:hover { background: rgba(255,255,255,0.06); }
    .sheet-link { color: #34c38f; font-weight: 600; text-decoration: none; }
    .sheet-link:hover { text-decoration: underline; }
    td.col-no { width: 56px; }
    td.is-empty { text-align: center; color: rgba(255,255,255,0.8); font-style: italic; }

    .expand-btn {
        display: none !important;
        background: var(--sigap-color);
        color: white;
        border: none;
        width: 28px;
        height: 28px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        line-height: 1;
        transition: all 0.3s ease;
        padding: 0;
        flex-shrink: 0;
    }
    .expand-btn:hover { opacity: 0.8; }
    .expand-btn.expanded { background: #e74c3c; }

    .mobile-hidden { display: table-cell; }

    @media (max-width: 768px) {
        .table-wrapper { overflow-x: visible; }

        table.sheet-table { display: block; }
        table.sheet-table thead { display: none; }
        table.sheet-table tbody { display: block; }

        table.sheet-table tbody tr {
            display: block;
            margin-bottom: 16px;
            background: rgba(255,255,255,0.05);
            border-radius: 12px;
            padding: 52px 12px 12px 12px;
            border: 1px solid rgba(255,255,255,0.1);
            position: relative;
        }

        table.sheet-table tbody td {
            display: block;
            padding: 8px 0;
            border: none;
            text-align: left;
        }

        table.sheet-table tbody td:before {
            content: attr(data-label);
            font-weight: 700;
            display: block;
            margin-bottom: 4px;
            color: rgba(255,255,255,0.7);
            font-size: 12px;
            text-transform: uppercase;
        }

        table.sheet-table tbody td.col-no {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 32px;
            height: 32px;
            padding: 0;
            background: var(--sigap-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }

        table.sheet-table tbody td.col-no:before { display: none; }

        .mobile-hidden { display: none !important; }
        tr.expanded .mobile-hidden { display: block !important; }

        .expand-btn {
            display: inline-block !important;
            position: absolute;
            top: 12px;
            left: 12px;
        }
    }
</style>
@endpush

<div class="wrapper">
    <div class="announcement-wrapper">
        <div class="announcement-header">
            <div class="announcement-icon">🖼️</div>
            <h1 class="announcement-title">Rekapan Poster Peserta BPKH dan Produsen Data Geospasial</h1>
            <p class="announcement-subtitle">SIGAP Award 2025</p>
        </div>

        <div class="categories-container">
            {{-- BPKH Posters --}}
            <div class="category-card">
                <div class="category-header">
                    <div class="category-icon">&#127963;</div>
                    <h2 class="category-title">BPKH</h2>
                    <span class="category-count">{{ $bpkhPosters->count() }}</span>
                </div>

                <div class="table-wrapper">
                    <table class="sheet-table">
                        <thead>
                            <tr>
                                <th style="width:56px;">No</th>
                                <th>Nama BPKH</th>
                                <th>File Poster</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bpkhPosters as $index => $poster)
                                <tr>
                                    <td class="col-no" data-label="No">{{ $index + 1 }}</td>
                                    <td data-label="Nama BPKH">{{ $poster->nama_bpkh }}</td>
                                    <td data-label="File Poster">
                                        @if($poster->poster_pdf_path)
                                            <a href="{{ asset('storage/' . $poster->poster_pdf_path) }}" target="_blank" rel="noopener" class="sheet-link">Lihat Poster</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="is-empty">Belum ada poster BPKH</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Produsen Posters --}}
            <div class="category-card">
                <div class="category-header">
                    <div class="category-icon">&#127970;</div>
                    <h2 class="category-title">Produsen Data Geospasial</h2>
                    <span class="category-count">{{ $produsenPosters->count() }}</span>
                </div>

                <div class="table-wrapper">
                    <table class="sheet-table">
                        <thead>
                            <tr>
                                <th style="width:56px;">No</th>
                                <th>Nama Instansi</th>
                                <th>File Poster</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produsenPosters as $index => $poster)
                                <tr>
                                    <td class="col-no" data-label="No">{{ $index + 1 }}</td>
                                    <td data-label="Nama Instansi">{{ $poster->nama_instansi }}</td>
                                    <td data-label="File Poster">
                                        @if($poster->poster_pdf_path)
                                            <a href="{{ asset('storage/' . $poster->poster_pdf_path) }}" target="_blank" rel="noopener" class="sheet-link">Lihat Poster</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="is-empty">Belum ada poster Produsen</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="back-home-container">
            <a href="{{ route('home') }}" class="btn-back-home">
                ← Kembali ke Home
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.expand-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const tr = this.closest('tr');
            if (!tr) return;
            tr.classList.toggle('expanded');
            this.classList.toggle('expanded');
            this.textContent = tr.classList.contains('expanded') ? '−' : '+';
        });
    });
});
</script>
@endpush

@endsection
