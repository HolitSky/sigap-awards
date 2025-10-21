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

    .nilai-final {
        font-size: 18px;
        font-weight: 700;
        color: #556ee6;
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
                    <h4 class="mb-sm-0 font-size-18"><i class="bx bx-trophy me-2"></i>{{ $title }}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Hasil Penilaian Final</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- Tables Row -->
        <div class="row">
            <!-- BPKH Table -->
            <div class="col-lg-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-3">
                            <i class="bx bx-building me-2 text-primary"></i>Hasil Penilaian Final BPKH
                            <span class="badge bg-success ms-2">Nominees Only</span>
                            <span class="badge bg-info ms-1">Dinilai 3 Juri</span>
                        </h4>
                        <p class="card-title-desc">Nilai Final = Form (45%) + Presentasi (35%) + Exhibition (20%) | Hanya menampilkan peserta nominasi yang sudah dinilai oleh 3 juri di presentasi dan exhibition</p>

                        <div class="table-responsive">
                            <table id="table-bpkh" class="table table-bordered table-hover table-hasil dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 40px;">Rank</th>
                                        <th style="width: 180px;">Nama BPKH</th>
                                        <th style="width: 120px;">Petugas</th>
                                        <th class="text-center" style="width: 90px;">Form<br>(45%)</th>
                                        <th class="text-center" style="width: 90px;">Presentasi<br>(35%)</th>
                                        <th class="text-center" style="width: 90px;">Exhibition<br>(20%)</th>
                                        <th class="text-center" style="width: 100px;">Nilai Final</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($hasilBpkh as $index => $item)
                                    <tr>
                                        <td class="text-center">
                                            @if($index < 3)
                                                <span class="rank-badge rank-{{ $index + 1 }}">{{ $index + 1 }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $item->nama_bpkh }}</strong>
                                        </td>
                                        <td>{{ $item->petugas_bpkh }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-info">{{ number_format($item->nilai_form, 2) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning">{{ number_format($item->nilai_presentasi, 2) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success">{{ number_format($item->nilai_exhibition, 2) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="nilai-final">{{ number_format($item->nilai_final, 2) }}</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Belum ada data penilaian</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Produsen Table -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-3">
                            <i class="bx bx-briefcase me-2 text-success"></i>Hasil Penilaian Final Produsen
                            <span class="badge bg-success ms-2">Nominees Only</span>
                            <span class="badge bg-info ms-1">Dinilai 3 Juri</span>
                        </h4>
                        <p class="card-title-desc">Nilai Final = Form (45%) + Presentasi (35%) + Exhibition (20%) | Hanya menampilkan peserta nominasi yang sudah dinilai oleh 3 juri di presentasi dan exhibition</p>

                        <div class="table-responsive">
                            <table id="table-produsen" class="table table-bordered table-hover table-hasil dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 40px;">Rank</th>
                                        <th style="width: 180px;">Nama Instansi</th>
                                        <th style="width: 120px;">Petugas</th>
                                        <th class="text-center" style="width: 90px;">Form<br>(45%)</th>
                                        <th class="text-center" style="width: 90px;">Presentasi<br>(35%)</th>
                                        <th class="text-center" style="width: 90px;">Exhibition<br>(20%)</th>
                                        <th class="text-center" style="width: 100px;">Nilai Final</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($hasilProdusen as $index => $item)
                                    <tr>
                                        <td class="text-center">
                                            @if($index < 3)
                                                <span class="rank-badge rank-{{ $index + 1 }}">{{ $index + 1 }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $item->nama_instansi }}</strong>
                                        </td>
                                        <td>{{ $item->petugas_produsen }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-info">{{ number_format($item->nilai_form, 2) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning">{{ number_format($item->nilai_presentasi, 2) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success">{{ number_format($item->nilai_exhibition, 2) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="nilai-final">{{ number_format($item->nilai_final, 2) }}</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Belum ada data penilaian</td>
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
    // Initialize DataTables for BPKH only if data exists
    @if($hasilBpkh->count() > 0)
    $('#table-bpkh').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[0, 'asc']], // Sort by rank
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

    // Initialize DataTables for Produsen only if data exists
    @if($hasilProdusen->count() > 0)
    $('#table-produsen').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[0, 'asc']], // Sort by rank
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
