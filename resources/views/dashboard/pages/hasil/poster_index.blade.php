@extends('dashboard.layouts.app')
@section('title', $title)

@push('styles')
<!-- DataTables -->
<link href="{{ asset('dashboard-assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('dashboard-assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

<style>
    .rank-badge {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: bold;
        font-size: 14px;
    }

    .rank-1 { background: linear-gradient(135deg, #FFD700, #FFA500); color: #fff; }
    .rank-2 { background: linear-gradient(135deg, #C0C0C0, #808080); color: #fff; }
    .rank-3 { background: linear-gradient(135deg, #CD7F32, #8B4513); color: #fff; }

    .table-hasil th {
        background-color: #f8f9fa;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
    }

    .table-hasil td.wrap-text {
        white-space: normal !important;
        word-wrap: break-word;
        word-break: break-word;
    }

    .nilai-final {
        font-size: 18px;
        font-weight: 700;
        color: #556ee6;
    }

    .row-not-final {
        background-color: rgba(220, 53, 69, 0.15) !important;
    }

    .row-not-final:hover {
        background-color: rgba(220, 53, 69, 0.2) !important;
    }

    .row-not-final td {
        background-color: rgba(220, 53, 69, 0.08) !important;
    }

    table.dataTable tbody tr.row-not-final {
        background-color: rgba(220, 53, 69, 0.15) !important;
    }

    table.dataTable tbody tr.row-not-final:hover {
        background-color: rgba(220, 53, 69, 0.2) !important;
    }

    table.dataTable tbody tr.row-not-final td {
        background-color: transparent !important;
    }
</style>
@endpush

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18"><i class="bx bx-image me-2"></i>{{ $title }}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Hasil Penilaian Poster/Exhibition</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- Tables Row -->
        <div class="row">
            <!-- Combined Poster/Exhibition Table -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h4 class="card-title mb-1">
                                    <i class="bx bx-image me-2 text-primary"></i>Hasil Penilaian Poster/Exhibition
                                    <span class="badge bg-success ms-2">Poster/Exhibition Category</span>
                                </h4>
                                <p class="card-title-desc mb-0">Gabungan penilaian poster/exhibition BPKH dan Produsen | Badge menunjukkan jumlah juri yang sudah menilai</p>
                            </div>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bx bx-download"></i> Export
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('dashboard.hasil.export-poster', ['format' => 'excel']) }}">
                                        <i class="bx bxs-file-export"></i> Excel
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('dashboard.hasil.export-poster', ['format' => 'pdf']) }}">
                                        <i class="bx bxs-file-pdf"></i> PDF
                                    </a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="bx bx-info-circle me-2"></i>
                            <strong>Informasi:</strong> Tabel ini menampilkan hasil penilaian <strong>Poster/Exhibition</strong> dari BPKH dan Produsen yang sudah dinilai oleh juri.
                            Nilai yang ditampilkan adalah khusus kategori exhibition / poster. 
                            Baris dengan <span class="badge bg-danger">background merah</span> menandakan belum dinilai oleh <strong>minimal 3 juri</strong>.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>

                        <div class="table-responsive">
                            <table id="table-poster" class="table table-bordered table-hover table-hasil w-100">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 40px;">Rank</th>
                                        <th class="text-center" style="width: 80px;">Kategori</th>
                                        <th style="width: 180px;">Nama</th>
                                        <th style="width: 120px;">Petugas</th>
                                        <th class="text-center" style="width: 100px;">Nilai Exhibition</th>
                                        <th class="text-center" style="width: 90px;">Juri Penilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($hasilPoster as $index => $item)
                                    @php
                                        $isFinal = ($item->total_juri_exhibition >= 3);
                                    @endphp
                                    <tr class="{{ !$isFinal ? 'row-not-final' : '' }}">
                                        <td class="text-center">
                                            @if($index < 3)
                                                <span class="rank-badge rank-{{ $index + 1 }}">{{ $index + 1 }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($item->kategori === 'BPKH')
                                                <span class="badge bg-primary">BPKH</span>
                                            @else
                                                <span class="badge bg-success">Produsen</span>
                                            @endif
                                        </td>
                                        <td class="wrap-text">
                                            <strong>{{ $item->nama }}</strong>
                                        </td>
                                        <td class="wrap-text">{{ $item->petugas }}</td>
                                        <td class="text-center">
                                            <span class="nilai-final">{{ number_format($item->nilai_exhibition, 2) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex flex-column align-items-center gap-1">
                                                <span class="badge {{ $isFinal ? 'bg-success' : 'bg-warning' }}">{{ $item->total_juri_exhibition ?? 0 }} Juri</span>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Belum ada data penilaian</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end tables row -->

    </div>
    <!-- container-fluid -->
</div>
<!-- End Page-content -->
@endsection

@push('scripts')
<!-- DataTables -->
<script src="{{ asset('dashboard-assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dashboard-assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('dashboard-assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('dashboard-assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

<script>
$(document).ready(function() {
    // Initialize DataTables for Poster only if data exists
    @if($hasilPoster->count() > 0)
    $('#table-poster').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[0, 'asc']], // Sort by rank
        autoWidth: false,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            },
            emptyTable: "Tidak ada data tersedia"
        }
    });
    @endif
});
</script>
@endpush
