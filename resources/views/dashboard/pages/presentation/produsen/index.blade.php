@extends('dashboard.layouts.app')
@section('title', 'Penilaian Presentasi Produsen DG')
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

                                    <form method="get" action="{{ route('dashboard.presentation.produsen.index') }}" class="row g-2 align-items-center mb-3">
                                        <div class="col-sm-8 col-md-6 col-lg-4">
                                            <input type="text" name="q" value="{{ $term ?? request('q') }}" class="form-control" placeholder="Cari Respondent, Nama Instansi, Petugas" />
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-primary">Cari</button>
                                        </div>
                                    </form>

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h4 class="card-title mb-1">Penilaian Presentasi Produsen DG</h4>
                                            <p class="card-title-desc mb-0">Daftar penilaian presentasi untuk Produsen DG yang sudah masuk nominasi dan dalam tahap presentasi.</p>
                                        </div>
                                        <div id="bulk-action-container" style="display: none;">
                                            <button type="button" id="btn-bulk-score" class="btn btn-primary">
                                                <i class="mdi mdi-clipboard-check-multiple-outline me-1"></i>
                                                Penilaian Kolektif (<span id="selected-count">0</span>)
                                            </button>
                                        </div>
                                    </div>

                                    <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                                        <thead>
                                        <tr>
                                            <th><input type="checkbox" id="select-all" class="form-check-input"></th>
                                            <th>No</th>
                                            <th>Nama Instansi</th>
                                            <th>Nama Petugas</th>
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
                                                    <td><input type="checkbox" class="form-check-input participant-checkbox" data-id="{{ $form->respondent_id }}" data-name="{{ $form->nama_instansi }}"></td>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $form->nama_instansi }}</td>
                                                    <td>{{ $form->nama_petugas }}</td>
                                                    <td class="text-center">{{ count($form->penilaian_per_juri ?? []) }}</td>
                                                    <td class="text-center">{{ $form->nilai_final !== null ? number_format($form->nilai_final, 2) : '-' }}</td>
                                                    <td class="text-center">{{ $form->nilai_final_dengan_bobot !== null ? number_format($form->nilai_final_dengan_bobot, 2) : '-' }}</td>
                                                    <td>
                                                        @if(!empty($form->kategori_skor) && $form->kategori_skor !== null)
                                                            @php
                                                                $badgeClass = match(trim($form->kategori_skor)) {
                                                                    'Sangat Baik' => 'bg-success',
                                                                    'Baik' => 'bg-info',
                                                                    'Cukup' => 'bg-warning',
                                                                    'Kurang' => 'bg-orange',
                                                                    'Sangat Kurang' => 'bg-danger',
                                                                    default => 'bg-secondary',
                                                                };
                                                            @endphp
                                                            <span class="badge {{ $badgeClass }}">{{ trim($form->kategori_skor) }}</span>
                                                        @else
                                                            <span class="badge bg-secondary">Belum Dinilai</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a class="btn btn-sm btn-outline-info" href="{{ route('dashboard.presentation.produsen.show', $form->respondent_id) }}">Detail</a>
                                                            <a class="btn btn-sm btn-primary" href="{{ route('dashboard.presentation.produsen.edit', $form->respondent_id) }}">Nilai Presentasi</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center">Tidak ada data</td>
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
    /* Custom badge colors */
    .badge.bg-orange {
        background-color: #fd7e14 !important;
        color: #fff !important;
    }

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
        order: [[1, 'asc']],
        columnDefs: [
            { width: "3%", targets: 0, orderable: false },   // Checkbox
            { width: "3%", targets: 1 },   // No
            { width: "19%", targets: 2 },  // Nama Instansi
            { width: "15%", targets: 3 },  // Nama Petugas
            { width: "12%", targets: 4 },  // Jumlah Juri
            { width: "9%", targets: 5 },   // Nilai Final
            { width: "10%", targets: 6 },  // Nilai Bobot
            { width: "10%", targets: 7 },  // Kategori
            { width: "19%", targets: 8 }   // Action
        ],
        autoWidth: false
    });

    // Handle select all checkbox
    $('#select-all').on('change', function() {
        $('.participant-checkbox').prop('checked', $(this).prop('checked'));
        updateBulkButton();
    });

    // Handle individual checkbox
    $('.participant-checkbox').on('change', function() {
        updateBulkButton();
        // Update select-all if all checkboxes are checked
        const allChecked = $('.participant-checkbox').length === $('.participant-checkbox:checked').length;
        $('#select-all').prop('checked', allChecked);
    });

    // Update bulk action button visibility and count
    function updateBulkButton() {
        const checkedCount = $('.participant-checkbox:checked').length;
        $('#selected-count').text(checkedCount);
        if (checkedCount > 0) {
            $('#bulk-action-container').slideDown(200);
        } else {
            $('#bulk-action-container').slideUp(200);
        }
    }

    // Handle bulk score button click
    $('#btn-bulk-score').on('click', function() {
        const selectedIds = [];
        $('.participant-checkbox:checked').each(function() {
            selectedIds.push($(this).data('id'));
        });

        if (selectedIds.length === 0) {
            alert('Pilih minimal 1 peserta untuk dinilai');
            return;
        }

        // Redirect to bulk scoring page with selected IDs
        const ids = selectedIds.join(',');
        window.location.href = '{{ route("dashboard.presentation.produsen.bulk-score") }}?ids=' + ids;
    });
});
</script>
@endpush
