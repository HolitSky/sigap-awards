@extends('dashboard.layouts.app')
@section('title', 'Nilai Exhibition/Poster BPKH')
@section('content')

<div class="page-content">
    <div class="container-fluid">
        @include('dashboard.pages.form.components.sub-head')

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <h4 class="card-title">Penilaian Exhibition/Poster</h4>
                        <p class="card-title-desc mb-4">
                            <strong>{{ $form->nama_bpkh }}</strong><br>
                            Petugas: {{ $form->petugas_bpkh }}
                        </p>

                        <form method="POST" action="{{ route('dashboard.exhibition.bpkh.update', $form->respondent_id) }}">
                            @csrf
                            
                            <div class="row">
                                <div class="col-lg-8">
                                    <h5 class="mb-3">Aspek Penilaian</h5>
                                    
                                    <!-- Kesesuaian Materi dengan Kuesioner -->
                                    <div class="mb-4">
                                        <label class="form-label">
                                            <strong>Kesesuaian Materi dengan Kuesioner</strong> 
                                            <span class="badge bg-primary">Bobot 30%</span>
                                        </label>
                                        <input type="number" 
                                               name="kesesuaian_materi" 
                                               class="form-control" 
                                               min="1" max="100" 
                                               value="{{ old('kesesuaian_materi', $userAssessment['aspek_scores']['kesesuaian_materi'] ?? '') }}" 
                                               required>
                                        <small class="text-muted">Nilai 1-100</small>
                                    </div>

                                    <!-- Kejelasan Informasi dan Struktur Penyajian -->
                                    <div class="mb-4">
                                        <label class="form-label">
                                            <strong>Kejelasan Informasi dan Struktur Penyajian</strong> 
                                            <span class="badge bg-primary">Bobot 25%</span>
                                        </label>
                                        <input type="number" 
                                               name="kejelasan_informasi" 
                                               class="form-control" 
                                               min="1" max="100" 
                                               value="{{ old('kejelasan_informasi', $userAssessment['aspek_scores']['kejelasan_informasi'] ?? '') }}" 
                                               required>
                                        <small class="text-muted">Nilai 1-100</small>
                                    </div>

                                    <!-- Kualitas Visual dan Desain Grafis -->
                                    <div class="mb-4">
                                        <label class="form-label">
                                            <strong>Kualitas Visual dan Desain Grafis</strong> 
                                            <span class="badge bg-primary">Bobot 20%</span>
                                        </label>
                                        <input type="number" 
                                               name="kualitas_visual" 
                                               class="form-control" 
                                               min="1" max="100" 
                                               value="{{ old('kualitas_visual', $userAssessment['aspek_scores']['kualitas_visual'] ?? '') }}" 
                                               required>
                                        <small class="text-muted">Nilai 1-100</small>
                                    </div>

                                    <!-- Inovasi dan Kreativitas Penyajian -->
                                    <div class="mb-4">
                                        <label class="form-label">
                                            <strong>Inovasi dan Kreativitas Penyajian</strong> 
                                            <span class="badge bg-primary">Bobot 15%</span>
                                        </label>
                                        <input type="number" 
                                               name="inovasi_kreativitas" 
                                               class="form-control" 
                                               min="1" max="100" 
                                               value="{{ old('inovasi_kreativitas', $userAssessment['aspek_scores']['inovasi_kreativitas'] ?? '') }}" 
                                               required>
                                        <small class="text-muted">Nilai 1-100</small>
                                    </div>

                                    <!-- Relevansi dengan Tema dan Tujuan SIGAP Award 2025 -->
                                    <div class="mb-4">
                                        <label class="form-label">
                                            <strong>Relevansi dengan Tema dan Tujuan SIGAP Award 2025</strong> 
                                            <span class="badge bg-primary">Bobot 10%</span>
                                        </label>
                                        <input type="number" 
                                               name="relevansi_tema" 
                                               class="form-control" 
                                               min="1" max="100" 
                                               value="{{ old('relevansi_tema', $userAssessment['aspek_scores']['relevansi_tema'] ?? '') }}" 
                                               required>
                                        <small class="text-muted">Nilai 1-100</small>
                                    </div>

                                    <!-- Catatan Juri -->
                                    <div class="mb-4">
                                        <label class="form-label"><strong>Catatan Juri</strong></label>
                                        <textarea name="catatan_juri" 
                                                  class="form-control" 
                                                  rows="4" 
                                                  placeholder="Tuliskan apresiasi, keunggulan utama, serta rekomendasi perbaikan yang dapat memperkuat implementasi ke depan">{{ old('catatan_juri', $userAssessment['catatan'] ?? $userAssessment['catatan_juri'] ?? '') }}</textarea>
                                    </div>

                                    <!-- Rekomendasi -->
                                    <div class="mb-4">
                                        <label class="form-label"><strong>Rekomendasi</strong></label>
                                        <select name="rekomendasi" class="form-select" required>
                                            <option value="">-- Pilih Rekomendasi --</option>
                                            @foreach($rekomendasiOptions as $option)
                                                <option value="{{ $option }}" 
                                                        {{ old('rekomendasi', $userAssessment['rekomendasi'] ?? '') == $option ? 'selected' : '' }}>
                                                    {{ $option }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">Simpan Penilaian</button>
                                        <a href="{{ route('dashboard.exhibition.bpkh.show', $form->respondent_id) }}" class="btn btn-secondary">Batal</a>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5 class="card-title">Informasi Bobot</h5>
                                            <ul class="list-unstyled mb-0">
                                                <li class="mb-2"><i class="mdi mdi-check-circle text-success"></i> Kesesuaian Materi: <strong>30%</strong></li>
                                                <li class="mb-2"><i class="mdi mdi-check-circle text-success"></i> Kejelasan Informasi: <strong>25%</strong></li>
                                                <li class="mb-2"><i class="mdi mdi-check-circle text-success"></i> Kualitas Visual: <strong>20%</strong></li>
                                                <li class="mb-2"><i class="mdi mdi-check-circle text-success"></i> Inovasi dan Kreativitas: <strong>15%</strong></li>
                                                <li class="mb-2"><i class="mdi mdi-check-circle text-success"></i> Relevansi Tema: <strong>10%</strong></li>
                                            </ul>
                                            <hr>
                                            <p class="mb-0 small text-muted">
                                                <i class="mdi mdi-information"></i> 
                                                Nilai final akan dihitung otomatis berdasarkan bobot masing-masing aspek.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Form validation and confirmation before submit
    $('#score-form').on('submit', function(e) {
        e.preventDefault();
        
        // Confirm before submit with SweetAlert
        Swal.fire({
            icon: 'question',
            title: 'Konfirmasi Penyimpanan',
            html: `Apakah Anda yakin ingin menyimpan penilaian untuk <strong>{{ $form->nama_bpkh }}</strong>?<br><small class="text-muted">Data yang sudah disimpan dapat diubah kembali.</small>`,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Menyimpan...',
                    html: 'Mohon tunggu, sedang memproses penilaian.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit the form
                e.target.submit();
            }
        });
    });
});
</script>
@endpush
