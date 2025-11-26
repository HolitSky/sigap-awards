{{-- Hasil Pemenang --}}

@extends('landing.layouts.app')
@section('content')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
<style>
    /* Layout dasar mirip result-presentation */
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

    .back-home-container {
        text-align: center;
        margin-top: 60px;
        padding-bottom: 20px;
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

    /* Table styles */
    .table-wrapper { width: 100%; overflow-x: auto; }
    table.winner-table { width: 100%; border-collapse: collapse; }
    table.winner-table thead th {
        background: rgba(255,255,255,0.15);
        color: var(--white);
        text-align: left;
        padding: 12px 14px;
        border-bottom: 2px solid rgba(255,255,255,0.2);
        font-weight: 700; font-size: 14px;
    }
    table.winner-table tbody td {
        color: var(--white);
        padding: 12px 14px;
        border-bottom: 1px solid rgba(255,255,255,0.12);
        vertical-align: top; font-size: 14px;
    }
    table.winner-table tbody tr:hover { background: rgba(255,255,255,0.06); }
    td.col-no { width: 56px; }
    td.is-empty { text-align: center; color: rgba(255,255,255,0.8); font-style: italic; }

    .winner-photo {
        max-width: 80px;
        max-height: 80px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid rgba(255,255,255,0.3);
        cursor: pointer;
    }

    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    @media (max-width: 1200px) {
        .categories-container {
            grid-template-columns: 1fr;
            max-width: 900px;
            gap: 32px;
        }

        .announcement-wrapper {
            padding: 60px 20px 80px;
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

        /* Mobile table: hide header, tampilkan label di td */
        table.winner-table thead { display: none; }
        table.winner-table tbody tr {
            display: block;
            margin-bottom: 16px;
            background: rgba(255,255,255,0.05);
            border-radius: 12px;
            padding: 12px;
            border: 1px solid rgba(255,255,255,0.1);
        }
        table.winner-table tbody td {
            display: block;
            padding: 6px 0;
            border: none;
            text-align: left;
        }
        table.winner-table tbody td:before {
            content: attr(data-label);
            font-weight: 700;
            display: block;
            margin-bottom: 2px;
            color: rgba(255,255,255,0.7);
            font-size: 12px;
            text-transform: uppercase;
        }
        td.col-no {
            width: auto;
            font-weight: 700;
        }
    }
</style>
@endpush

<div class="wrapper">
    <div class="announcement-wrapper">
        <!-- Header -->
        <div class="announcement-header">
            <div class="announcement-icon">🏆</div>
            <h1 class="announcement-title">Pemenang SIGAP Award 2025</h1>
            <p class="announcement-subtitle">Selamat kepada para pemenang SIGAP Award 2025</p>
        </div>

        <div class="categories-container">
            <!-- 1. Produsen DG Terbaik -->
            <div class="category-card">
                <div class="category-header">
                    <div class="category-icon">🏭</div>
                    <h2 class="category-title">Produsen DG Terbaik</h2>
                    <span class="category-count">{{ $produsenWinners->count() }}</span>
                </div>

                <div class="table-wrapper">
                    <table class="winner-table">
                        <thead>
                            <tr>
                                <th style="width:56px;">No</th>
                                <th>Nama Pemenang</th>
                                <th>Juara</th>
                                <th>Foto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produsenWinners as $winner)
                                <tr>
                                    <td class="col-no" data-label="No">{{ $loop->iteration }}</td>
                                    <td data-label="Nama Pemenang">{{ $winner->nama_pemenang }}</td>
                                    <td data-label="Juara">{{ $winner->juara_label }}</td>
                                    <td data-label="Foto">
                                        @if($winner->foto_path)
                                            <a href="{{ asset('storage/' . $winner->foto_path) }}"
                                               data-fancybox="winner-gallery"
                                               data-caption="{{ $winner->nama_pemenang }} - {{ $winner->juara_label }}">
                                                <img src="{{ asset('storage/' . $winner->foto_path) }}" alt="Foto {{ $winner->nama_pemenang }}" class="winner-photo">
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="is-empty">Belum ada data pemenang untuk kategori ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 2. BPKH Terbaik -->
            <div class="category-card">
                <div class="category-header">
                    <div class="category-icon">🏢</div>
                    <h2 class="category-title">BPKH Terbaik</h2>
                    <span class="category-count">{{ $bpkhWinners->count() }}</span>
                </div>

                <div class="table-wrapper">
                    <table class="winner-table">
                        <thead>
                            <tr>
                                <th style="width:56px;">No</th>
                                <th>Nama Pemenang</th>
                                <th>Juara</th>
                                <th>Foto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bpkhWinners as $winner)
                                <tr>
                                    <td class="col-no" data-label="No">{{ $loop->iteration }}</td>
                                    <td data-label="Nama Pemenang">{{ $winner->nama_pemenang }}</td>
                                    <td data-label="Juara">{{ $winner->juara_label }}</td>
                                    <td data-label="Foto">
                                        @if($winner->foto_path)
                                            <a href="{{ asset('storage/' . $winner->foto_path) }}"
                                               data-fancybox="winner-gallery"
                                               data-caption="{{ $winner->nama_pemenang }} - {{ $winner->juara_label }}">
                                                <img src="{{ asset('storage/' . $winner->foto_path) }}" alt="Foto {{ $winner->nama_pemenang }}" class="winner-photo">
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="is-empty">Belum ada data pemenang untuk kategori ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 3. Pengelola IGT Terbaik -->
            <div class="category-card">
                <div class="category-header">
                    <div class="category-icon">🗺️</div>
                    <h2 class="category-title">Pengelola IGT Terbaik</h2>
                    <span class="category-count">{{ $pengelolaIgtWinners->count() }}</span>
                </div>

                <div class="table-wrapper">
                    <table class="winner-table">
                        <thead>
                            <tr>
                                <th style="width:56px;">No</th>
                                <th>Nama Pemenang</th>
                                <th>Tipe Peserta</th>
                                <th>Juara</th>
                                <th>Foto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengelolaIgtWinners as $winner)
                                <tr>
                                    <td class="col-no" data-label="No">{{ $loop->iteration }}</td>
                                    <td data-label="Nama Pemenang">
                                        {{ $winner->nama_pemenang }}@if($winner->nama_petugas) / {{ $winner->nama_petugas }}@endif
                                    </td>
                                    <td data-label="Tipe Peserta">{{ $winner->tipe_peserta_label }}</td>
                                    <td data-label="Juara">{{ $winner->juara_label }}</td>
                                    <td data-label="Foto">
                                        @if($winner->foto_path)
                                            <a href="{{ asset('storage/' . $winner->foto_path) }}"
                                               data-fancybox="winner-gallery"
                                               data-caption="{{ $winner->nama_pemenang }} - {{ $winner->juara_label }}">
                                                <img src="{{ asset('storage/' . $winner->foto_path) }}" alt="Foto {{ $winner->nama_pemenang }}" class="winner-photo">
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="is-empty">Belum ada data pemenang untuk kategori ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 4. Poster Terbaik -->
            <div class="category-card">
                <div class="category-header">
                    <div class="category-icon">🖼️</div>
                    <h2 class="category-title">Poster Terbaik</h2>
                    <span class="category-count">{{ $posterTerbaikWinners->count() }}</span>
                </div>

                <div class="table-wrapper">
                    <table class="winner-table">
                        <thead>
                            <tr>
                                <th style="width:56px;">No</th>
                                <th>Nama Pemenang</th>
                                <th>Tipe Peserta</th>
                                <th>Juara</th>
                                <th>Foto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($posterTerbaikWinners as $winner)
                                <tr>
                                    <td class="col-no" data-label="No">{{ $loop->iteration }}</td>
                                    <td data-label="Nama Pemenang">{{ $winner->nama_pemenang }}</td>
                                    <td data-label="Tipe Peserta">{{ $winner->tipe_peserta_label }}</td>
                                    <td data-label="Juara">{{ $winner->juara_label }}</td>
                                    <td data-label="Foto">
                                        @if($winner->foto_path)
                                            <a href="{{ asset('storage/' . $winner->foto_path) }}"
                                               data-fancybox="winner-gallery"
                                               data-caption="{{ $winner->nama_pemenang }} - {{ $winner->juara_label }}">
                                                <img src="{{ asset('storage/' . $winner->foto_path) }}" alt="Foto {{ $winner->nama_pemenang }}" class="winner-photo">
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="is-empty">Belum ada data pemenang untuk kategori ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 5. Poster Favorit -->
            <div class="category-card">
                <div class="category-header">
                    <div class="category-icon">❤️</div>
                    <h2 class="category-title">Poster Favorit</h2>
                    <span class="category-count">{{ $posterFavoritWinners->count() }}</span>
                </div>

                <div class="table-wrapper">
                    <table class="winner-table">
                        <thead>
                            <tr>
                                <th style="width:56px;">No</th>
                                <th>Nama Pemenang</th>
                                <th>Tipe Peserta</th>
                                <th>Foto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($posterFavoritWinners as $winner)
                                <tr>
                                    <td class="col-no" data-label="No">{{ $loop->iteration }}</td>
                                    <td data-label="Nama Pemenang">{{ $winner->nama_pemenang }}</td>
                                    <td data-label="Tipe Peserta">{{ $winner->tipe_peserta_label }}</td>
                                    <td data-label="Foto">
                                        @if($winner->foto_path)
                                            <a href="{{ asset('storage/' . $winner->foto_path) }}"
                                               data-fancybox="winner-gallery"
                                               data-caption="{{ $winner->nama_pemenang }} - {{ $winner->juara_label }}">
                                                <img src="{{ asset('storage/' . $winner->foto_path) }}" alt="Foto {{ $winner->nama_pemenang }}" class="winner-photo">
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="is-empty">Belum ada data pemenang untuk kategori ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tombol kembali ke home -->
        <div class="back-home-container">
            <a href="{{ route('home') }}" class="btn-back-home">
                ← Kembali ke Home
            </a>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.GLightbox) {
                GLightbox({
                    selector: '[data-fancybox="winner-gallery"]'
                });
            }
        });
    </script>
@endpush

@endsection
