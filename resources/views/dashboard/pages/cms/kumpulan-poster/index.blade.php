@extends('dashboard.layouts.app')
@section('title', 'Manajemen Kumpulan Poster')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        @include('dashboard.pages.form.components.sub-head')

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="mdi mdi-alert-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="mdi mdi-alert-circle me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-3">
                            <i class="mdi mdi-file-document-outline text-danger me-2"></i>Manajemen Kumpulan Poster
                        </h4>
                        <p class="card-title-desc">
                            Kelola file poster untuk peserta BPKH dan Produsen. File gambar akan otomatis dikompres hingga maksimal sekitar 5 MB.
                        </p>

                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" data-bs-toggle="tab" href="#tab-bpkh" role="tab">
                                    <i class="mdi mdi-domain me-1"></i> BPKH
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#tab-produsen" role="tab">
                                    <i class="mdi mdi-factory me-1"></i> Produsen DG
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content p-3">
                            <div class="tab-pane active" id="tab-bpkh" role="tabpanel">
                                <form method="POST" action="{{ route('dashboard.cms.kumpulan-poster.bpkh.store') }}" enctype="multipart/form-data" class="row g-3 align-items-start">
                                    @csrf
                                    <div class="col-md-4">
                                        <label class="form-label">Nama BPKH <span class="text-danger">*</span></label>
                                        <select name="nama_bpkh" class="form-select" required>
                                            <option value="">-- Pilih BPKH --</option>
                                            @foreach($bpkhList as $item)
                                                <option value="{{ $item->nama_wilayah }}" {{ old('nama_bpkh') == $item->nama_wilayah ? 'selected' : '' }}>
                                                    {{ $item->nama_wilayah }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">File Poster (PDF / Gambar) <span class="text-danger">*</span></label>
                                        <input type="file" name="poster" class="form-control poster-input" accept=".pdf,image/*" data-preview-target="#preview-bpkh-create" required>
                                        <small class="text-muted">Format: PDF, JPG, PNG. Gambar akan dikompres otomatis hingga &plusmn;5 MB, PDF boleh diupload selama ukuran file tidak lebih dari 50 MB.</small>
                                        <div id="preview-bpkh-create" class="mt-2"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="mdi mdi-upload me-1"></i>Upload Poster BPKH
                                        </button>
                                    </div>
                                </form>

                                <hr class="my-4">

                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="50">No</th>
                                                <th>Nama BPKH</th>
                                                <th>File Poster</th>
                                                <th>Ukuran</th>
                                                <th>Diunggah</th>
                                                <th width="120">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($bpkhPosters as $index => $poster)
                                                <tr>
                                                    <td class="text-center">{{ $index + 1 }}</td>
                                                    <td>{{ $poster->nama_bpkh }}</td>
                                                    <td>
                                                        @if($poster->poster_pdf_path)
                                                            <a href="{{ asset('storage/' . $poster->poster_pdf_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                <i class="mdi mdi-file-document-outline me-1"></i>Lihat File
                                                            </a>
                                                        @else
                                                            <span class="text-muted small">Tidak ada file</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($poster->file_size)
                                                            {{ number_format($poster->file_size / 1024, 0) }} KB
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>{{ $poster->created_at ? $poster->created_at->format('d M Y H:i') : '-' }}</td>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <button type="button" class="btn btn-sm btn-warning btn-edit-bpkh"
                                                                    data-id="{{ $poster->id }}"
                                                                    data-nama-bpkh="{{ $poster->nama_bpkh }}"
                                                                    data-file-url="{{ $poster->poster_pdf_path ? asset('storage/' . $poster->poster_pdf_path) : '' }}">
                                                                <i class="mdi mdi-pencil"></i>
                                                            </button>
                                                            <form method="POST" action="{{ route('dashboard.cms.kumpulan-poster.bpkh.destroy', $poster->id) }}" onsubmit="return confirm('Hapus poster BPKH ini?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                    <i class="mdi mdi-delete"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">
                                                        <i class="mdi mdi-information-outline me-1"></i>Belum ada poster BPKH yang diupload
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane" id="tab-produsen" role="tabpanel">
                                <form method="POST" action="{{ route('dashboard.cms.kumpulan-poster.produsen.store') }}" enctype="multipart/form-data" class="row g-3 align-items-start">
                                    @csrf
                                    <div class="col-md-4">
                                        <label class="form-label">Nama Produsen <span class="text-danger">*</span></label>
                                        <select name="nama_instansi" class="form-select" required>
                                            <option value="">-- Pilih Produsen --</option>
                                            @foreach($produsenList as $item)
                                                <option value="{{ $item->nama_unit }}" {{ old('nama_instansi') == $item->nama_unit ? 'selected' : '' }}>
                                                    {{ $item->nama_unit }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">File Poster (PDF / Gambar) <span class="text-danger">*</span></label>
                                        <input type="file" name="poster" class="form-control poster-input" accept=".pdf,image/*" data-preview-target="#preview-produsen-create" required>
                                        <small class="text-muted">Format: PDF, JPG, PNG. Gambar akan dikompres otomatis hingga &plusmn;5 MB, PDF boleh diupload selama ukuran file tidak lebih dari 50 MB.</small>
                                        <div id="preview-produsen-create" class="mt-2"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="mdi mdi-upload me-1"></i>Upload Poster Produsen
                                        </button>
                                    </div>
                                </form>

                                <hr class="my-4">

                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="50">No</th>
                                                <th>Nama Produsen</th>
                                                <th>File Poster</th>
                                                <th>Ukuran</th>
                                                <th>Diunggah</th>
                                                <th width="120">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($produsenPosters as $index => $poster)
                                                <tr>
                                                    <td class="text-center">{{ $index + 1 }}</td>
                                                    <td>{{ $poster->nama_instansi }}</td>
                                                    <td>
                                                        @if($poster->poster_pdf_path)
                                                            <a href="{{ asset('storage/' . $poster->poster_pdf_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                <i class="mdi mdi-file-document-outline me-1"></i>Lihat File
                                                            </a>
                                                        @else
                                                            <span class="text-muted small">Tidak ada file</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($poster->file_size)
                                                            {{ number_format($poster->file_size / 1024, 0) }} KB
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>{{ $poster->created_at ? $poster->created_at->format('d M Y H:i') : '-' }}</td>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <button type="button" class="btn btn-sm btn-warning btn-edit-produsen"
                                                                    data-id="{{ $poster->id }}"
                                                                    data-nama-instansi="{{ $poster->nama_instansi }}"
                                                                    data-file-url="{{ $poster->poster_pdf_path ? asset('storage/' . $poster->poster_pdf_path) : '' }}">
                                                                <i class="mdi mdi-pencil"></i>
                                                            </button>
                                                            <form method="POST" action="{{ route('dashboard.cms.kumpulan-poster.produsen.destroy', $poster->id) }}" onsubmit="return confirm('Hapus poster Produsen ini?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                    <i class="mdi mdi-delete"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">
                                                        <i class="mdi mdi-information-outline me-1"></i>Belum ada poster Produsen yang diupload
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- Edit Modals --}}
        <div class="modal fade" id="editBpkhPosterModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" id="editBpkhPosterForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-header bg-warning text-white">
                            <h5 class="modal-title"><i class="mdi mdi-pencil me-2"></i>Edit Poster BPKH</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama BPKH <span class="text-danger">*</span></label>
                                <select name="nama_bpkh" id="edit_bpkh_nama_bpkh" class="form-select" required>
                                    <option value="">-- Pilih BPKH --</option>
                                    @foreach($bpkhList as $item)
                                        <option value="{{ $item->nama_wilayah }}">{{ $item->nama_wilayah }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ganti File Poster (opsional)</label>
                                <input type="file" name="poster" id="edit_bpkh_poster" class="form-control poster-input" accept=".pdf,image/*" data-preview-target="#preview-bpkh-edit">
                                <small class="text-muted">Kosongkan jika tidak ingin mengganti file. Format: PDF, JPG, PNG. Gambar akan dikompres otomatis hingga &plusmn;5 MB, PDF maksimal 50 MB.</small>
                                <div class="mt-2">
                                    <a href="#" id="current-bpkh-file-link" target="_blank" class="btn btn-sm btn-outline-secondary d-none">
                                        <i class="mdi mdi-link me-1"></i>Lihat file saat ini
                                    </a>
                                </div>
                                <div id="preview-bpkh-edit" class="mt-2"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning">
                                <i class="mdi mdi-check me-1"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editProdusenPosterModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" id="editProdusenPosterForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-header bg-warning text-white">
                            <h5 class="modal-title"><i class="mdi mdi-pencil me-2"></i>Edit Poster Produsen</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Produsen <span class="text-danger">*</span></label>
                                <select name="nama_instansi" id="edit_produsen_nama_instansi" class="form-select" required>
                                    <option value="">-- Pilih Produsen --</option>
                                    @foreach($produsenList as $item)
                                        <option value="{{ $item->nama_unit }}">{{ $item->nama_unit }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ganti File Poster (opsional)</label>
                                <input type="file" name="poster" id="edit_produsen_poster" class="form-control poster-input" accept=".pdf,image/*" data-preview-target="#preview-produsen-edit">
                                <small class="text-muted">Kosongkan jika tidak ingin mengganti file. Format: PDF, JPG, PNG. Gambar akan dikompres otomatis hingga &plusmn;5 MB, PDF maksimal 50 MB.</small>
                                <div class="mt-2">
                                    <a href="#" id="current-produsen-file-link" target="_blank" class="btn btn-sm btn-outline-secondary d-none">
                                        <i class="mdi mdi-link me-1"></i>Lihat file saat ini
                                    </a>
                                </div>
                                <div id="preview-produsen-edit" class="mt-2"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning">
                                <i class="mdi mdi-check me-1"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        // Preview untuk semua input file poster
        $(document).on('change', '.poster-input', function () {
            const input = this;
            const $input = $(this);
            const target = $input.data('preview-target');
            if (!target) return;

            const $preview = $(target);
            $preview.empty();

            if (!input.files || !input.files[0]) {
                $preview.hide();
                return;
            }

            const file = input.files[0];
            const url = URL.createObjectURL(file);

            let html = '';
            if (file.type && file.type.startsWith('image/')) {
                html = '<div class="border rounded p-2 mt-1">' +
                    '<strong>Preview Gambar:</strong><br>' +
                    '<img src="' + url + '" class="img-fluid" style="max-height:300px;object-fit:contain;" />' +
                    '</div>';
            } else if (file.type === 'application/pdf') {
                html = '<div class="border rounded p-2 mt-1">' +
                    '<strong>Preview PDF:</strong><br>' +
                    '<embed src="' + url + '" type="application/pdf" width="100%" height="300px" />' +
                    '</div>';
            } else {
                html = '<div class="text-muted small mt-1">File terpilih: ' + file.name + '</div>';
            }

            $preview.html(html).show();
        });

        // Edit BPKH
        $('.btn-edit-bpkh').on('click', function () {
            const id = $(this).data('id');
            const nama = $(this).data('nama-bpkh');
            const fileUrl = $(this).data('file-url');

            const actionTemplate = '{{ route("dashboard.cms.kumpulan-poster.bpkh.update", ["id" => ':id']) }}';
            $('#editBpkhPosterForm').attr('action', actionTemplate.replace(':id', id));

            $('#edit_bpkh_nama_bpkh').val(nama);

            const $currentLink = $('#current-bpkh-file-link');
            if (fileUrl) {
                $currentLink.attr('href', fileUrl).removeClass('d-none');
            } else {
                $currentLink.addClass('d-none');
            }

            $('#preview-bpkh-edit').empty();

            $('#editBpkhPosterModal').modal('show');
        });

        // Edit Produsen
        $('.btn-edit-produsen').on('click', function () {
            const id = $(this).data('id');
            const nama = $(this).data('nama-instansi');
            const fileUrl = $(this).data('file-url');

            const actionTemplate = '{{ route("dashboard.cms.kumpulan-poster.produsen.update", ["id" => ':id']) }}';
            $('#editProdusenPosterForm').attr('action', actionTemplate.replace(':id', id));

            $('#edit_produsen_nama_instansi').val(nama);

            const $currentLink = $('#current-produsen-file-link');
            if (fileUrl) {
                $currentLink.attr('href', fileUrl).removeClass('d-none');
            } else {
                $currentLink.addClass('d-none');
            }

            $('#preview-produsen-edit').empty();

            $('#editProdusenPosterModal').modal('show');
        });
    });
</script>
@endpush
