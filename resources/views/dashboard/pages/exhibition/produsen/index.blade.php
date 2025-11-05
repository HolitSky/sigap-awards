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

                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h4 class="card-title mb-1">Penilaian Exhibition/Poster Produsen DG</h4>
                                            <p class="card-title-desc mb-0">Daftar penilaian exhibition/poster untuk Produsen DG.</p>
                                        </div>
                                        <div class="d-flex gap-2 align-items-center">
                                            <!-- Export All Dropdown -->
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bx bx-download"></i> Export Semua
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="{{ route('dashboard.exhibition.produsen.export-all', ['format' => 'excel']) }}">
                                                        <i class="bx bxs-file-export"></i> Excel
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="{{ route('dashboard.exhibition.produsen.export-all', ['format' => 'pdf']) }}">
                                                        <i class="bx bxs-file-pdf"></i> PDF
                                                    </a></li>
                                                </ul>
                                            </div>
                                            
                                            @if(auth()->user()->role !== 'admin-view')
                                            <div class="btn-group" role="group">
                                                @foreach($sessions as $sessionName => $participants)
                                                    @php
                                                        $isCompleted = in_array($sessionName, $completedSessions ?? []);
                                                        $btnClass = $isCompleted ? 'btn-success' : 'btn-outline-secondary';
                                                        $icon = $isCompleted ? 'mdi-check-circle' : 'mdi-checkbox-multiple-marked-outline';
                                                    @endphp
                                                    <button type="button" class="btn {{ $btnClass }} btn-sm session-select {{ $isCompleted ? 'session-completed' : '' }}" data-session="{{ $sessionName }}">
                                                        <i class="mdi {{ $icon }} me-1"></i>{{ $sessionName }}
                                                    </button>
                                                @endforeach
                                            </div>
                                            <div id="bulk-action-container" style="display: none;">
                                                <button type="button" id="btn-bulk-score" class="btn btn-primary">
                                                    <i class="mdi mdi-clipboard-check-multiple-outline me-1"></i>
                                                    Penilaian Kolektif (<span id="selected-count">0</span>)
                                                </button>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <table id="datatable-exhibition-produsen" class="table table-bordered dt-responsive nowrap w-100">
                                        <thead>
                                        <tr>
                                            @if(auth()->user()->role !== 'admin-view')
                                            <th><input type="checkbox" id="select-all" class="form-check-input"></th>
                                            @endif
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
                                                    @if(auth()->user()->role !== 'admin-view')
                                                    <td><input type="checkbox" class="form-check-input participant-checkbox" data-id="{{ $form->respondent_id }}" data-name="{{ $form->nama_instansi }}"></td>
                                                    @endif
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
                                                            @if(auth()->user()->role !== 'admin-view')
                                                            <a class="btn btn-sm btn-primary" href="{{ route('dashboard.exhibition.produsen.edit', $form->respondent_id) }}">Nilai Exhibition</a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="{{ auth()->user()->role !== 'admin-view' ? '9' : '8' }}" class="text-center">Tidak ada data</td>
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
        #datatable-exhibition-produsen th:nth-child(2) { width: 3% !important; min-width: 30px; }
        #datatable-exhibition-produsen th:nth-child(3) { width: 18% !important; max-width: 200px; }
        #datatable-exhibition-produsen th:nth-child(4) { width: 12% !important; max-width: 150px; }
        #datatable-exhibition-produsen th:nth-child(5) { width: 8% !important; text-align: center; }
        #datatable-exhibition-produsen th:nth-child(6) { width: 8% !important; text-align: center; }
        #datatable-exhibition-produsen th:nth-child(7) { width: 10% !important; text-align: center; }
        #datatable-exhibition-produsen th:nth-child(8) { width: 10% !important; text-align: center; }
        #datatable-exhibition-produsen th:nth-child(9) { width: 24% !important; }
        #datatable-exhibition-produsen td:nth-child(3),
        #datatable-exhibition-produsen td:nth-child(4) {
            white-space: normal !important;
            word-wrap: break-word !important;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Admin-view specific styles (without checkbox) */
        body.admin-view-role #datatable-exhibition-produsen th:nth-child(1) { width: 5% !important; min-width: 40px; text-align: center; }
        body.admin-view-role #datatable-exhibition-produsen th:nth-child(2) { width: 22% !important; max-width: 250px; }
        body.admin-view-role #datatable-exhibition-produsen th:nth-child(3) { width: 15% !important; max-width: 180px; }
        body.admin-view-role #datatable-exhibition-produsen th:nth-child(4) { width: 10% !important; text-align: center; }
        body.admin-view-role #datatable-exhibition-produsen th:nth-child(5) { width: 10% !important; text-align: center; }
        body.admin-view-role #datatable-exhibition-produsen th:nth-child(6) { width: 12% !important; text-align: center; }
        body.admin-view-role #datatable-exhibition-produsen th:nth-child(7) { width: 12% !important; text-align: center; }
        body.admin-view-role #datatable-exhibition-produsen th:nth-child(8) { width: 14% !important; }
        
        body.admin-view-role #datatable-exhibition-produsen td:nth-child(1) { text-align: center; }
        body.admin-view-role #datatable-exhibition-produsen td:nth-child(2),
        body.admin-view-role #datatable-exhibition-produsen td:nth-child(3) {
            white-space: normal !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        /* Session button active state */
        .session-select.active {
            background-color: #556ee6 !important;
            color: #fff !important;
            border-color: #556ee6 !important;
        }
        
        /* Session button completed state */
        .session-select.session-completed {
            background-color: #34c38f !important;
            color: #fff !important;
            border-color: #34c38f !important;
            font-weight: 600;
        }
        
        .session-select.session-completed:hover {
            background-color: #2ca87f !important;
            border-color: #2ca87f !important;
        }
        
        /* Keep completed state even when active */
        .session-select.session-completed.active {
            background-color: #2ca87f !important;
            border-color: #2ca87f !important;
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

    // Check if user is admin-view
    const isAdminView = {{ auth()->user()->role === 'admin-view' ? 'true' : 'false' }};
    
    // Configure columns based on role
    let columnDefs = [];
    if (isAdminView) {
        // Without checkbox column
        columnDefs = [
            { width: "5%", targets: 0 },   // No
            { width: "20%", targets: 1 },  // Nama Instansi
            { width: "15%", targets: 2 },  // Nama Petugas
            { width: "10%", targets: 3 },   // Jumlah Juri
            { width: "10%", targets: 4 },   // Nilai Final
            { width: "12%", targets: 5 },  // Nilai Bobot
            { width: "12%", targets: 6 },  // Kategori
            { width: "16%", targets: 7, orderable: false }   // Action
        ];
    } else {
        // With checkbox column
        columnDefs = [
            { width: "3%", targets: 0, orderable: false },   // Checkbox
            { width: "3%", targets: 1 },   // No
            { width: "18%", targets: 2 },  // Nama Instansi
            { width: "12%", targets: 3 },  // Nama Petugas
            { width: "8%", targets: 4 },   // Jumlah Juri
            { width: "8%", targets: 5 },   // Nilai Final
            { width: "10%", targets: 6 },  // Nilai Bobot
            { width: "10%", targets: 7 },  // Kategori
            { width: "24%", targets: 8, orderable: false }   // Action
        ];
    }

    var table = $("#datatable-exhibition-produsen").DataTable({
        responsive: true,
        order: [[isAdminView ? 0 : 1, 'asc']],
        columnDefs: columnDefs,
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
    
    // Session button handler
    $('.session-select').on('click', function() {
        const sessionName = $(this).data('session');
        const sessionParticipants = @json($sessions);
        const participants = sessionParticipants[sessionName] || [];
        
        // Uncheck all checkboxes first
        $('.participant-checkbox').prop('checked', false);
        $('#select-all').prop('checked', false);
        
        // Check participants in this session
        participants.forEach(function(participantName) {
            $('.participant-checkbox').each(function() {
                if ($(this).data('name') === participantName) {
                    $(this).prop('checked', true);
                }
            });
        });
        
        updateBulkActionButton();
        
        // Visual feedback - toggle active class
        $('.session-select').removeClass('active');
        $(this).addClass('active');
    });
    
    // Select all checkbox
    $('#select-all').on('change', function() {
        $('.participant-checkbox').prop('checked', $(this).is(':checked'));
        $('.session-select').removeClass('active');
        updateBulkActionButton();
    });
    
    // Individual checkbox
    $('.participant-checkbox').on('change', function() {
        updateBulkActionButton();
        $('.session-select').removeClass('active');
        
        // Update select-all checkbox
        const total = $('.participant-checkbox').length;
        const checked = $('.participant-checkbox:checked').length;
        $('#select-all').prop('checked', total === checked && total > 0);
    });
    
    // Bulk score button
    $('#btn-bulk-score').on('click', function() {
        const selectedIds = [];
        $('.participant-checkbox:checked').each(function() {
            selectedIds.push($(this).data('id'));
        });
        
        if (selectedIds.length === 0) {
            alert('Pilih minimal 1 peserta');
            return;
        }
        
        window.location.href = '{{ route("dashboard.exhibition.produsen.bulk-score") }}?ids=' + selectedIds.join(',');
    });
    
    // Update bulk action button visibility
    function updateBulkActionButton() {
        const checkedCount = $('.participant-checkbox:checked').length;
        $('#selected-count').text(checkedCount);
        
        if (checkedCount > 0) {
            $('#bulk-action-container').show();
        } else {
            $('#bulk-action-container').hide();
        }
    }
});
</script>
@endpush
