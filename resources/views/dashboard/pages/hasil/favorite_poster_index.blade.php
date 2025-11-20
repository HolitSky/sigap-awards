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

    .vote-count {
        font-size: 20px;
        font-weight: 700;
        color: #34c38f;
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
                    <h4 class="mb-sm-0 font-size-18"><i class="mdi mdi-heart-multiple me-2"></i>{{ $title }}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Hasil Poster Favorit</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- Tables Row -->
        <div class="row">
            <!-- Favorite Poster Table -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h4 class="card-title mb-1">
                                    <i class="mdi mdi-heart-multiple me-2 text-danger"></i>Hasil Penilaian Poster Favorit
                                    <span class="badge bg-danger ms-2">Favorite Category</span>
                                </h4>
                                <p class="card-title-desc mb-0">Hasil voting poster favorit dari BPKH dan Produsen DG berdasarkan jumlah vote tertinggi</p>
                            </div>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bx bx-download"></i> Export
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('dashboard.hasil.export-favorite-poster', ['format' => 'excel']) }}">
                                        <i class="bx bxs-file-export"></i> Excel
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('dashboard.hasil.export-favorite-poster', ['format' => 'pdf']) }}">
                                        <i class="bx bxs-file-pdf"></i> PDF
                                    </a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="bx bx-info-circle me-2"></i>
                            <strong>Informasi:</strong> Tabel ini menampilkan hasil voting <strong>Poster Favorit</strong> dari BPKH dan Produsen DG.
                            Peringkat ditentukan berdasarkan jumlah vote yang diterima (dari tertinggi ke terendah).
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>

                        <div class="table-responsive">
                            <table id="table-favorite" class="table table-bordered table-hover table-hasil w-100">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 40px;">Rank</th>
                                        <th class="text-center" style="width: 80px;">Kategori</th>
                                        <th style="width: 200px;">Nama</th>
                                        <th style="width: 140px;">Petugas</th>
                                        <th class="text-center" style="width: 100px;">Jumlah Vote</th>
                                        <th style="width: 200px;">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($hasilFavorite as $index => $vote)
                                    <tr>
                                        <td class="text-center">
                                            @if($index < 3)
                                                <span class="rank-badge rank-{{ $index + 1 }}">{{ $index + 1 }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($vote->participant_type === 'bpkh')
                                                <span class="badge bg-primary">BPKH</span>
                                            @else
                                                <span class="badge bg-success">Produsen DG</span>
                                            @endif
                                        </td>
                                        <td class="wrap-text">
                                            <strong>{{ $vote->participant_name }}</strong>
                                            <br><small class="text-muted">ID: {{ $vote->respondent_id }}</small>
                                        </td>
                                        <td class="wrap-text">{{ $vote->petugas ?? '-' }}</td>
                                        <td class="text-center">
                                            <span class="vote-count">{{ $vote->vote_count }}</span>
                                        </td>
                                        <td class="wrap-text">{{ $vote->notes ?? '-' }}</td>
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
    // Initialize DataTables for Favorite Poster only if data exists
    @if($hasilFavorite->count() > 0)
    $('#table-favorite').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[4, 'desc']], // Sort by vote count descending
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
