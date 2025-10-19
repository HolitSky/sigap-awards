@extends('dashboard.layouts.app')
@section('title', 'Penilaian Presentasi BPKH')
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

                                    <form method="get" action="{{ route('dashboard.presentation.bpkh.index') }}" class="row g-2 align-items-center mb-3">
                                        <div class="col-sm-8 col-md-6 col-lg-4">
                                            <input type="text" name="q" value="{{ $term ?? request('q') }}" class="form-control" placeholder="Cari Respondent, Nama BPKH, Petugas" />
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-primary">Cari</button>
                                        </div>
                                    </form>

                                    <h4 class="card-title">Penilaian Presentasi BPKH</h4>
                                    <p class="card-title-desc">Daftar penilaian presentasi untuk BPKH yang sudah masuk tahap presentasi.</p>

                                    <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama BPKH</th>
                                            <th>Petugas BPKH</th>
                                            <th>Total Juri yang<br>sudah menilai</th>
                                            <th>Nilai Final</th>
                                            <th>Nilai Bobot <br> Akhir {{ ($forms->first()->bobot_presentasi ?? 35) }}%</th>
                                            <th>Kategori</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @forelse(($forms ?? []) as $form)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $form->nama_bpkh }}</td>
                                                    <td>{{ $form->petugas_bpkh }}</td>
                                                    <td class="text-center">{{ count($form->penilaian_per_juri ?? []) }}</td>
                                                    <td class="text-center">{{ $form->nilai_final !== null ? number_format($form->nilai_final, 2) : '-' }}</td>
                                                    <td class="text-center">{{ $form->nilai_final_dengan_bobot !== null ? number_format($form->nilai_final_dengan_bobot, 2) : '-' }}</td>
                                                    <td>
                                                        @if($form->kategori_skor)
                                                            @php
                                                                $badgeClass = match($form->kategori_skor) {
                                                                    'Sangat Baik' => 'bg-success',
                                                                    'Baik' => 'bg-info',
                                                                    'Cukup' => 'bg-warning',
                                                                    'Kurang' => 'bg-orange',
                                                                    'Sangat Kurang' => 'bg-danger',
                                                                    default => 'bg-secondary',
                                                                };
                                                            @endphp
                                                            <span class="badge {{ $badgeClass }}">{{ $form->kategori_skor }}</span>
                                                        @else
                                                            <span class="badge bg-secondary">Belum Dinilai</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a class="btn btn-sm btn-outline-info" href="{{ route('dashboard.presentation.bpkh.show', $form->respondent_id) }}">Detail</a>
                                                            <a class="btn btn-sm btn-primary" href="{{ route('dashboard.presentation.bpkh.edit', $form->respondent_id) }}">Nilai Presentasi</a>
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
<style>
    #datatable {
        table-layout: fixed;
        width: 100% !important;
    }

    #datatable th:nth-child(1),
    #datatable td:nth-child(1) {
        width: 50px !important;
        max-width: 50px !important;
    }

    #datatable th:nth-child(2),
    #datatable td:nth-child(2) {
        width: 180px !important;
        max-width: 180px !important;
        word-wrap: break-word;
        white-space: normal;
    }

    #datatable th:nth-child(3),
    #datatable td:nth-child(3) {
        width: 150px !important;
        max-width: 150px !important;
        word-wrap: break-word;
        white-space: normal;
    }

    #datatable th:nth-child(4),
    #datatable td:nth-child(4) {
        width: 80px !important;
        max-width: 80px !important;
        text-align: center;
    }

    #datatable th:nth-child(5),
    #datatable td:nth-child(5) {
        width: 90px !important;
        max-width: 90px !important;
        text-align: center;
    }

    #datatable th:nth-child(6),
    #datatable td:nth-child(6) {
        width: 90px !important;
        max-width: 90px !important;
        text-align: center;
    }

    #datatable th:nth-child(7),
    #datatable td:nth-child(7) {
        width: 110px !important;
        max-width: 110px !important;
        text-align: center;
    }

    #datatable th:nth-child(8),
    #datatable td:nth-child(8) {
        width: 200px !important;
        max-width: 200px !important;
    }

    #datatable td {
        vertical-align: middle;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Destroy existing DataTable if it exists
    if ($.fn.DataTable.isDataTable('#datatable')) {
        $('#datatable').DataTable().destroy();
    }

    // Re-initialize with custom options
    $('#datatable').DataTable({
        lengthMenu: [[10, 15, 25, 50, -1], [10, 15, 25, 50, "All"]],
        pageLength: 15,
        responsive: true,
        order: [[0, 'asc']],
        columnDefs: [
            { width: "4%", targets: 0 },   // No
            { width: "20%", targets: 1 },  // Nama BPKH
            { width: "16%", targets: 2 },  // Petugas BPKH
            { width: "13%", targets: 3 },  // Jumlah Juri
            { width: "10%", targets: 4 },  // Nilai Final
            { width: "10%", targets: 5 },  // Nilai Bobot
            { width: "10%", targets: 6 },  // Kategori
            { width: "17%", targets: 7 }   // Action
        ],
        autoWidth: false
    });
});
</script>
@endpush
