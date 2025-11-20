@extends('dashboard.layouts.app')
@section('title', 'Penilaian Poster Favorit')
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

                                    @if($errors->any())
                                        <div class="alert alert-danger mb-3">
                                            @foreach($errors->all() as $error)
                                                <div>{{ $error }}</div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <form method="get" action="{{ route('dashboard.favorite-poster.index') }}" class="row g-2 align-items-center mb-3">
                                        <div class="col-sm-6 col-md-4 col-lg-3">
                                            <input type="text" name="q" value="{{ $term ?? request('q') }}" class="form-control" placeholder="Cari Nama, Respondent ID" />
                                        </div>
                                        <div class="col-sm-4 col-md-3 col-lg-2">
                                            <select name="type" class="form-select">
                                                <option value="">Semua Kategori</option>
                                                <option value="bpkh" {{ $typeFilter === 'bpkh' ? 'selected' : '' }}>BPKH</option>
                                                <option value="produsen" {{ $typeFilter === 'produsen' ? 'selected' : '' }}>Produsen DG</option>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-primary">Cari</button>
                                        </div>
                                    </form>

                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h4 class="card-title mb-1">Penilaian Poster Favorit</h4>
                                            <p class="card-title-desc mb-0">Input jumlah vote untuk poster favorit (BPKH & Produsen DG).</p>
                                        </div>
                                        <div class="d-flex gap-2 align-items-center">
                                            <!-- Export All Dropdown -->
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bx bx-download"></i> Export Semua
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="{{ route('dashboard.favorite-poster.export-all', ['format' => 'excel']) }}">
                                                        <i class="bx bxs-file-export"></i> Excel
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="{{ route('dashboard.favorite-poster.export-all', ['format' => 'pdf']) }}">
                                                        <i class="bx bxs-file-pdf"></i> PDF
                                                    </a></li>
                                                </ul>
                                            </div>

                                            @if(auth()->user()->role !== 'admin-view')
                                            <div id="bulk-action-container" style="display: none;">
                                                <button type="button" id="btn-bulk-edit" class="btn btn-primary">
                                                    <i class="mdi mdi-clipboard-edit-outline me-1"></i>
                                                    Input Kolektif (<span id="selected-count">0</span>)
                                                </button>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <table id="datatable-favorite-poster" class="table table-bordered dt-responsive nowrap w-100">
                                        <thead>
                                        <tr>
                                            @if(auth()->user()->role !== 'admin-view')
                                            <th><input type="checkbox" id="select-all" class="form-check-input"></th>
                                            @endif
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Kategori</th>
                                            <th>Petugas</th>
                                            <th>Jumlah Vote</th>
                                            <th>Catatan</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @forelse(($votes ?? []) as $vote)
                                                <tr>
                                                    @if(auth()->user()->role !== 'admin-view')
                                                    <td><input type="checkbox" class="form-check-input participant-checkbox" data-id="{{ $vote->id }}"></td>
                                                    @endif
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $vote->participant_name }}</td>
                                                    <td>
                                                        @if($vote->participant_type === 'bpkh')
                                                            <span class="badge bg-primary">BPKH</span>
                                                        @else
                                                            <span class="badge bg-success">Produsen DG</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $vote->petugas ?? '-' }}</td>
                                                    <td class="text-center">
                                                        <span class="badge bg-success fs-6">{{ $vote->vote_count }}</span>
                                                    </td>
                                                    <td>{{ $vote->notes ? Str::limit($vote->notes, 50) : '-' }}</td>
                                                    <td>
                                                        @if(auth()->user()->role !== 'admin-view')
                                                        <a class="btn btn-sm btn-primary" href="{{ route('dashboard.favorite-poster.edit', $vote->id) }}">
                                                            <i class="mdi mdi-pencil"></i> Input Vote
                                                        </a>
                                                        @else
                                                        <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="{{ auth()->user()->role !== 'admin-view' ? '8' : '7' }}" class="text-center">Tidak ada data</td>
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
        #datatable-favorite-poster th:nth-child(1) { width: 3% !important; min-width: 30px; }
        #datatable-favorite-poster th:nth-child(2) { width: 5% !important; min-width: 40px; }
        #datatable-favorite-poster th:nth-child(3) { width: 25% !important; }
        #datatable-favorite-poster th:nth-child(4) { width: 10% !important; text-align: center; }
        #datatable-favorite-poster th:nth-child(5) { width: 15% !important; }
        #datatable-favorite-poster th:nth-child(6) { width: 10% !important; text-align: center; }
        #datatable-favorite-poster th:nth-child(7) { width: 20% !important; }
        #datatable-favorite-poster th:nth-child(8) { width: 12% !important; }

        /* Admin-view specific styles (without checkbox) */
        body.admin-view-role #datatable-favorite-poster th:nth-child(1) { width: 5% !important; }
        body.admin-view-role #datatable-favorite-poster th:nth-child(2) { width: 28% !important; }
        body.admin-view-role #datatable-favorite-poster th:nth-child(3) { width: 12% !important; }
        body.admin-view-role #datatable-favorite-poster th:nth-child(4) { width: 17% !important; }
        body.admin-view-role #datatable-favorite-poster th:nth-child(5) { width: 12% !important; }
        body.admin-view-role #datatable-favorite-poster th:nth-child(6) { width: 18% !important; }
        body.admin-view-role #datatable-favorite-poster th:nth-child(7) { width: 8% !important; }
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
    if ($.fn.DataTable.isDataTable('#datatable-favorite-poster')) {
        $('#datatable-favorite-poster').DataTable().destroy();
    }

    // Stop default datatables.init.js from initializing this table
    $('#datatable-favorite-poster').addClass('dt-custom-init');

    // Check if user is admin-view
    const isAdminView = {{ auth()->user()->role === 'admin-view' ? 'true' : 'false' }};

    // Configure columns based on role
    let columnDefs = [];
    if (isAdminView) {
        columnDefs = [
            { width: "5%", targets: 0 },
            { width: "28%", targets: 1 },
            { width: "12%", targets: 2 },
            { width: "17%", targets: 3 },
            { width: "12%", targets: 4 },
            { width: "18%", targets: 5 },
            { width: "8%", targets: 6, orderable: false }
        ];
    } else {
        columnDefs = [
            { width: "3%", targets: 0, orderable: false },
            { width: "5%", targets: 1 },
            { width: "25%", targets: 2 },
            { width: "10%", targets: 3 },
            { width: "15%", targets: 4 },
            { width: "10%", targets: 5 },
            { width: "20%", targets: 6 },
            { width: "12%", targets: 7, orderable: false }
        ];
    }

    var table = $("#datatable-favorite-poster").DataTable({
        responsive: true,
        order: [[isAdminView ? 4 : 5, 'desc']],
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

    // Select all checkbox
    $('#select-all').on('change', function() {
        $('.participant-checkbox').prop('checked', $(this).is(':checked'));
        updateBulkActionButton();
    });

    // Individual checkbox
    $('.participant-checkbox').on('change', function() {
        updateBulkActionButton();

        // Update select-all checkbox
        const total = $('.participant-checkbox').length;
        const checked = $('.participant-checkbox:checked').length;
        $('#select-all').prop('checked', total === checked && total > 0);
    });

    // Bulk edit button
    $('#btn-bulk-edit').on('click', function() {
        const selectedIds = [];
        $('.participant-checkbox:checked').each(function() {
            selectedIds.push($(this).data('id'));
        });

        if (selectedIds.length === 0) {
            alert('Pilih minimal 1 peserta');
            return;
        }

        window.location.href = '{{ route("dashboard.favorite-poster.bulk-edit") }}?ids=' + selectedIds.join(',');
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
