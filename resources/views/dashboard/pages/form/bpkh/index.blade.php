@extends('dashboard.layouts.app')
@section('title', 'Hasil Form BPKH')
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

                                    <form method="get" action="{{ route('dashboard.form.bpkh.index') }}" class="row g-2 align-items-center mb-3">
                                        <div class="col-sm-8 col-md-6 col-lg-4">
                                            <input type="text" name="q" value="{{ $term ?? request('q') }}" class="form-control" placeholder="Cari Respondent, Nama, Petugas, Phone, Website" />
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-primary">Cari</button>
                                        </div>
                                    </form>

                                    <h4 class="card-title">Tujuan</h4>
                                    <p class="card-title-desc">Form Penilaian (FP) ini adalah salah satu dari sejumlah alat analisis dalam metodologi SIGAP Award 2025 untuk implementasi IIG (Infrastruktur Informasi Geospasial) Kehutanan menggunakan Kerangka Kerja Informasi Geospasial Terintegrasi - Integrated Geospatial Information Framework (IGIF). Tujuan utama FP adalah untuk mengumpulkan informasi yang diperlukan guna menyelesaikan penilaian dasar (kondisi saat ini) dari pengembangan IIG Kehutanan.
                                    </p>

                                    <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama BPKH</th>
                                            <th>Petugas BPKH</th>
                                            <th>Nomor Telepon / Nomor WhatsApp Aktif</th>
                                            <th>Status Nilai</th>
                                            <th>Nilai Final</th>
                                            <th>Nilai Bobot <br> Akhir {{ ($forms->first()->bobot ?? 45) }}%</th>
                                            <th>Catatan</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @forelse(($forms ?? []) as $form)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $form->nama_bpkh }}</td>
                                                    <td>{{ $form->petugas_bpkh }}</td>
                                                    <td>{{ $form->phone }}</td>


                                                    <td>
                                                        @php
                                                            $badgeClass = match($form->status_nilai) {
                                                                'pending' => 'bg-secondary',
                                                                'in_review' => 'bg-warning',
                                                                'scored' => 'bg-success',
                                                                default => 'bg-secondary',
                                                            };
                                                        @endphp
                                                        <span class="badge {{ $badgeClass }}">{{ $form->status_label ?? $form->status_nilai }}</span>
                                                    </td>
                                                    <td>{{ $form->total_score !== null && $form->total_score !== '' ? $form->total_score : 'Belum Ada' }}</td>
                                                    <td>{{ $form->nilai_bobot_total !== null ? number_format($form->nilai_bobot_total, 2) : 'Belum Ada' }}</td>
                                                    <td>{{ ($form->notes ?? '') !== '' ? $form->notes : '-' }}</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('dashboard.form.bpkh.show', $form->respondent_id) }}">Lihat Detail</a>
                                                            <a class="btn btn-sm btn-primary" href="{{ route('dashboard.form.bpkh.score.edit', $form->respondent_id) }}">Nilai Ulang Form</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
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
        width: 200px !important;
        max-width: 200px !important;
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
        width: 130px !important;
        max-width: 130px !important;
        word-wrap: break-word;
        white-space: normal;
    }

    #datatable th:nth-child(5),
    #datatable td:nth-child(5) {
        width: 100px !important;
        max-width: 100px !important;
        text-align: center;
    }

    #datatable th:nth-child(6),
    #datatable td:nth-child(6) {
        width: 80px !important;
        max-width: 80px !important;
        text-align: center;
    }

    #datatable th:nth-child(7),
    #datatable td:nth-child(7) {
        width: 90px !important;
        max-width: 90px !important;
        text-align: center;
    }

    #datatable th:nth-child(8),
    #datatable td:nth-child(8) {
        width: 150px !important;
        max-width: 150px !important;
        word-wrap: break-word;
        white-space: normal;
    }

    #datatable th:nth-child(9),
    #datatable td:nth-child(9) {
        width: 180px !important;
        max-width: 180px !important;
    }

    #datatable td {
        vertical-align: middle;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Destroy default DataTable if exists
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
            { width: "5%", targets: 0 },   // No
            { width: "18%", targets: 1 },  // Nama BPKH
            { width: "13%", targets: 2 },  // Petugas BPKH
            { width: "10%", targets: 3 },  // Nomor Telepon
            { width: "9%", targets: 4 },   // Status Nilai
            { width: "7%", targets: 5 },   // Nilai Final
            { width: "13%", targets: 6 },   // Nilai dengan Bobot
            { width: "8%", targets: 7 },  // Catatan
            { width: "17%", targets: 8 }   // Action
        ],
        autoWidth: false
    });
});
</script>
@endpush
