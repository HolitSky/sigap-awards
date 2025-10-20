@extends('dashboard.layouts.app')
@section('title', $title)
@section('content')

<div class="page-content">
    <div class="container-fluid">

        @include('dashboard.pages.form.components.sub-head')

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('dashboard.presentation.produsen.bulk-score.store') }}" id="bulk-score-form">
            @csrf

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h4 class="card-title mb-1">Penilaian Kolektif - {{ $forms->count() }} Peserta</h4>
                                    <p class="text-muted mb-0">Isi penilaian untuk setiap peserta. Semua penilaian akan disimpan sekaligus.</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-secondary" id="expand-all-btn">
                                        <i class="mdi mdi-arrow-expand-all me-1"></i> Buka Semua
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="collapse-all-btn">
                                        <i class="mdi mdi-arrow-collapse-all me-1"></i> Tutup Semua
                                    </button>
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="mdi mdi-content-save me-1"></i> Simpan Semua Penilaian
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Participants Accordion -->
            <div class="accordion" id="participantsAccordion">
                @foreach($forms as $index => $form)
                <div class="card mb-3">
                    <div class="card-header" id="heading{{ $index }}">
                        <h5 class="mb-0">
                            <button class="btn btn-link d-flex justify-content-between align-items-center w-100 text-start" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="true">
                                <span>
                                    <i class="mdi mdi-numeric-{{ $index + 1 }}-circle me-2"></i>
                                    <strong>{{ $form->nama_instansi }}</strong> - {{ $form->nama_petugas }}
                                    @if(!empty($userAssessments[$form->respondent_id]))
                                        <span class="badge bg-success ms-2"><i class="mdi mdi-check"></i> Sudah Dinilai</span>
                                    @else
                                        <span class="badge bg-warning ms-2"><i class="mdi mdi-alert-circle-outline"></i> Belum Dinilai</span>
                                    @endif
                                </span>
                                <i class="mdi mdi-chevron-down"></i>
                            </button>
                        </h5>
                    </div>

                    <div id="collapse{{ $index }}" class="collapse show">
                        <div class="card-body">
                            <div class="row g-3">

                                <!-- Aspek Penilaian Grid -->
                                <div class="col-12">
                                    <h6 class="text-primary mb-3"><i class="mdi mdi-clipboard-list-outline me-1"></i> Aspek Penilaian</h6>
                                    <div class="row g-2">
                                        @foreach($aspekPenilaian as $key => $aspek)
                                        <div class="col-md-4 col-sm-6">
                                            <label class="form-label small mb-1">
                                                <strong>{{ $aspek['label'] }}</strong>
                                                <span class="badge bg-secondary">{{ $aspek['bobot'] }}%</span>
                                            </label>
                                            <input type="number"
                                                   class="form-control form-control-sm"
                                                   name="participants[{{ $form->respondent_id }}][{{ $key }}]"
                                                   min="1"
                                                   max="100"
                                                   required
                                                   placeholder="1-100"
                                                   value="{{ $userAssessments[$form->respondent_id]['aspek_scores'][$key]['score'] ?? '' }}">
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Rekomendasi -->
                                <div class="col-md-6">
                                    <label class="form-label"><strong>Rekomendasi</strong> <span class="text-danger">*</span></label>
                                    <select class="form-select" name="participants[{{ $form->respondent_id }}][rekomendasi]" required>
                                        <option value="">-- Pilih Rekomendasi --</option>
                                        @foreach($rekomendasiOptions as $option)
                                            <option value="{{ $option }}" 
                                                {{ (isset($userAssessments[$form->respondent_id]['rekomendasi']) && $userAssessments[$form->respondent_id]['rekomendasi'] == $option) ? 'selected' : '' }}>
                                                {{ $option }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Catatan Juri -->
                                <div class="col-md-6">
                                    <label class="form-label"><strong>Catatan Juri</strong> (Opsional)</label>
                                    <textarea class="form-control"
                                              name="participants[{{ $form->respondent_id }}][catatan_juri]"
                                              rows="3"
                                              placeholder="Tulis catatan atau komentar...">{{ $userAssessments[$form->respondent_id]['catatan'] ?? '' }}</textarea>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Submit Button (Sticky Bottom) -->
            <div class="row">

                <div class="col-lg-12">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Informasi Bobot Penilaian</h5>
                            <p class="text-muted small mb-3">Total bobot semua aspek adalah 100%</p>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2">✓ Substansi & Capaian: <strong>30%</strong></li>
                                        <li class="mb-2">✓ Implementasi & Strategi: <strong>20%</strong></li>
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2">✓ Kedalaman Analisis: <strong>15%</strong></li>
                                        <li class="mb-2">✓ Kejelasan Alur: <strong>10%</strong></li>
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2">✓ Kemampuan Menjawab: <strong>15%</strong></li>
                                        <li class="mb-2">✓ Kreativitas & Daya Tarik: <strong>10%</strong></li>
                                    </ul>
                                </div>
                            </div>
                            <hr>
                            <p class="text-muted small mb-0">
                                <strong>Catatan:</strong> Nilai akhir dihitung berdasarkan rata-rata tertimbang dari semua aspek penilaian.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card bg-light">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="{{ route('dashboard.presentation.produsen.index') }}" class="btn btn-secondary">
                                        <i class="mdi mdi-arrow-left me-1"></i> Kembali
                                    </a>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-success btn-lg px-5">
                                        <i class="mdi mdi-content-save me-1"></i> Simpan {{ $forms->count() }} Penilaian
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>

    </div>
</div>

@endsection

@push('styles')
<style>
    .accordion .btn-link {
        text-decoration: none;
        color: #495057;
        font-size: 1rem;
    }
    .accordion .btn-link:hover {
        color: #007bff;
    }
    .accordion .card-header {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }
    .form-control-sm {
        font-size: 0.875rem;
    }
    .badge {
        font-size: 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Expand all accordions
    $('#expand-all-btn').on('click', function() {
        $('.accordion .collapse').addClass('show');
    });

    // Collapse all accordions
    $('#collapse-all-btn').on('click', function() {
        $('.accordion .collapse').removeClass('show');
    });

    // Form validation before submit
    $('#bulk-score-form').on('submit', function(e) {
        e.preventDefault();
        
        const emptyFields = [];
        $('input[type="number"][required]').each(function() {
            if (!$(this).val()) {
                emptyFields.push($(this));
            }
        });

        if (emptyFields.length > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Belum Lengkap',
                text: 'Harap isi semua aspek penilaian untuk setiap peserta',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            }).then(() => {
                emptyFields[0].focus();
            });
            return false;
        }

        // Confirm before submit with SweetAlert
        Swal.fire({
            icon: 'question',
            title: 'Konfirmasi Penyimpanan',
            html: `Apakah Anda yakin ingin menyimpan penilaian untuk <strong>{{ $forms->count() }} peserta</strong>?<br><small class="text-muted">Data yang sudah disimpan tidak dapat dibatalkan.</small>`,
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="mdi mdi-content-save me-1"></i> Ya, Simpan!',
            cancelButtonText: '<i class="mdi mdi-close me-1"></i> Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Menyimpan...',
                    html: 'Mohon tunggu, sedang memproses penilaian {{ $forms->count() }} peserta.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit form
                $('#bulk-score-form')[0].submit();
            }
        });
    });
});
</script>
@endpush
