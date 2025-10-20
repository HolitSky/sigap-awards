@extends('dashboard.layouts.app')
@section('title', 'Penilaian Exhibition/Poster Produsen DG')
@section('content')

 <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->

            <div class="page-content">
                <div class="container-fluid">

                   @include('dashboard.pages.form.components.sub-head')

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    @if(session('success'))
                                        <div class="alert alert-success mb-3">
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    <form method="get" action="{{ route('dashboard.exhibition.produsen.index') }}" class="row g-2 align-items-center mb-3">
                                        <div class="col-sm-8 col-md-6 col-lg-4">
                                            <input type="text" name="q" value="{{ $term ?? request('q') }}" class="form-control" placeholder="Cari Respondent, Nama Instansi, Petugas" />
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-primary">Cari</button>
                                        </div>
                                    </form>

                                    <h4 class="card-title">Penilaian Exhibition/Poster Produsen DG</h4>
                                    <p class="card-title-desc">Daftar penilaian exhibition/poster untuk Produsen DG.</p>

                                    <table id="datatable-exhibition-produsen" class="table table-bordered dt-responsive nowrap w-100">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Instansi</th>
                                            <th>Nama Petugas</th>
                                            <th>Total Juri yang<br>sudah menilai</th>
                                            <th>Nilai Final</th>
                                            <th>Nilai Bobot <br> Akhir {{ ($forms->first()->bobot_exhibition ?? 20) }}%</th>
                                            <th>Kategori</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @forelse(($forms ?? []) as $form)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $form->nama_instansi }}</td>
                                                    <td>{{ $form->nama_petugas }}</td>
                                                    <td class="text-center">{{ count($form->penilaian_per_juri ?? []) }}</td>
                                                    <td class="text-center">{{ $form->nilai_final !== null ? number_format($form->nilai_final, 2) : '-' }}</td>
                                                    <td class="text-center">{{ $form->nilai_final_dengan_bobot !== null ? number_format($form->nilai_final_dengan_bobot, 2) : '-' }}</td>
                                                    <td>
                                                        @if($form->kategori_penilaian)
                                                            @php
                                                                $badgeClass = match($form->kategori_penilaian) {
                                                                    'Sangat Baik' => 'bg-success',
                                                                    'Baik' => 'bg-info',
                                                                    'Cukup' => 'bg-warning',
                                                                    'Kurang' => 'bg-orange',
                                                                    'Sangat Kurang' => 'bg-danger',
                                                                    default => 'bg-secondary',
                                                                };
                                                            @endphp
                                                            <span class="badge {{ $badgeClass }}">{{ $form->kategori_penilaian }}</span>
                                                        @else
                                                            <span class="badge bg-secondary">Belum Dinilai</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a class="btn btn-sm btn-outline-info" href="{{ route('dashboard.exhibition.produsen.show', $form->respondent_id) }}">Detail</a>
                                                            <a class="btn btn-sm btn-primary" href="{{ route('dashboard.exhibition.produsen.edit', $form->respondent_id) }}">Nilai Exhibition</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center">Tidak ada data</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div> <!-- end row -->

                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

        <!-- end main content-->

@endsection

@push('styles')
    <!-- DataTables -->
    <link href="{{ asset('dashboard-assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('dashboard-assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        /* Custom badge colors */
        .badge.bg-orange {
            background-color: #fd7e14 !important;
            color: #fff !important;
        }
        #datatable-exhibition-produsen th:nth-child(1) { width: 3% !important; min-width: 30px; }
        #datatable-exhibition-produsen th:nth-child(2) { width: 20% !important; }
        #datatable-exhibition-produsen th:nth-child(3) { width: 13% !important; }
        #datatable-exhibition-produsen th:nth-child(4) { width: 9% !important; text-align: center; }
        #datatable-exhibition-produsen th:nth-child(5) { width: 8% !important; text-align: center; }
        #datatable-exhibition-produsen th:nth-child(6) { width: 11% !important; text-align: center; }
        #datatable-exhibition-produsen th:nth-child(7) { width: 10% !important; text-align: center; }
        #datatable-exhibition-produsen th:nth-child(8) { width: 26% !important; }
        #datatable-exhibition-produsen td:nth-child(2),
        #datatable-exhibition-produsen td:nth-child(3) {
            white-space: normal !important;
            word-wrap: break-word !important;
        }
    </style>
@endpush

@push('scripts')
    <!-- Required datatable js -->
    <script src="{{ asset('dashboard-assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dashboard-assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Responsive examples -->
    <script src="{{ asset('dashboard-assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('dashboard-assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

    <script>
$(document).ready(function () {
    // Check if DataTable is already initialized and destroy it
    if ($.fn.DataTable.isDataTable('#datatable-exhibition-produsen')) {
        $('#datatable-exhibition-produsen').DataTable().destroy();
    }

    // Stop default datatables.init.js from initializing this table
    $('#datatable-exhibition-produsen').addClass('dt-custom-init');

    var table = $("#datatable-exhibition-produsen").DataTable({
        responsive: true,
        order: [[0, 'asc']],
        columnDefs: [
            { width: "3%", targets: 0 },   // No
            { width: "20%", targets: 1 },  // Nama Instansi
            { width: "13%", targets: 2 },  // Nama Petugas
            { width: "9%", targets: 3 },   // Jumlah Juri
            { width: "8%", targets: 4 },   // Nilai Final
            { width: "11%", targets: 5 },  // Nilai Bobot
            { width: "10%", targets: 6 },  // Kategori
            { width: "26%", targets: 7, orderable: false }   // Action
        ],
        autoWidth: false,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            },
            zeroRecords: "Tidak ada data yang ditemukan",
            emptyTable: "Tidak ada data"
        }
    });
});
</script>
@endpush
