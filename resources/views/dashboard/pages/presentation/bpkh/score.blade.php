@extends('dashboard.layouts.app')
@section('title', 'Nilai Presentasi BPKH')
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

                        <h4 class="card-title">Penilaian Presentasi</h4>
                        <p class="card-title-desc mb-4">
                            <strong>{{ $form->nama_bpkh }}</strong><br>
                            Petugas: {{ $form->petugas_bpkh }}
                        </p>

                        <form method="POST" action="{{ route('dashboard.presentation.bpkh.update', $form->respondent_id) }}">
                            @csrf
                            
                            <div class="row">
                                <div class="col-lg-8">
                                    <h5 class="mb-3">Aspek Penilaian</h5>
                                    
                                    <!-- Substansi & Capaian Kinerja -->
                                    <div class="mb-4">
                                        <label class="form-label">
                                            <strong>Substansi & Capaian Kinerja</strong> 
                                            <span class="badge bg-primary">Bobot 30%</span>
                                        </label>
                                        <input type="number" 
                                               name="substansi_capaian" 
                                               class="form-control" 
                                               min="1" max="100" 
                                               value="{{ old('substansi_capaian', $userAssessment['aspek_scores']['substansi_capaian']['score'] ?? '') }}" 
                                               required>
                                        <small class="text-muted">Nilai 1-100</small>
                                    </div>

                                    <!-- Implementasi Strategi & Dampak -->
                                    <div class="mb-4">
                                        <label class="form-label">
                                            <strong>Implementasi Strategi & Dampak</strong> 
                                            <span class="badge bg-primary">Bobot 20%</span>
                                        </label>
                                        <input type="number" 
                                               name="implementasi_strategi" 
                                               class="form-control" 
                                               min="1" max="100" 
                                               value="{{ old('implementasi_strategi', $userAssessment['aspek_scores']['implementasi_strategi']['score'] ?? '') }}" 
                                               required>
                                        <small class="text-muted">Nilai 1-100</small>
                                    </div>

                                    <!-- Kedalaman Analisis -->
                                    <div class="mb-4">
                                        <label class="form-label">
                                            <strong>Kedalaman Analisis</strong> 
                                            <span class="badge bg-primary">Bobot 15%</span>
                                        </label>
                                        <input type="number" 
                                               name="kedalaman_analisis" 
                                               class="form-control" 
                                               min="1" max="100" 
                                               value="{{ old('kedalaman_analisis', $userAssessment['aspek_scores']['kedalaman_analisis']['score'] ?? '') }}" 
                                               required>
                                        <small class="text-muted">Nilai 1-100</small>
                                    </div>

                                    <!-- Kejelasan & Alur Penyampaian -->
                                    <div class="mb-4">
                                        <label class="form-label">
                                            <strong>Kejelasan & Alur Penyampaian</strong> 
                                            <span class="badge bg-primary">Bobot 10%</span>
                                        </label>
                                        <input type="number" 
                                               name="kejelasan_alur" 
                                               class="form-control" 
                                               min="1" max="100" 
                                               value="{{ old('kejelasan_alur', $userAssessment['aspek_scores']['kejelasan_alur']['score'] ?? '') }}" 
                                               required>
                                        <small class="text-muted">Nilai 1-100</small>
                                    </div>

                                    <!-- Kemampuan Menjawab Pertanyaan Juri -->
                                    <div class="mb-4">
                                        <label class="form-label">
                                            <strong>Kemampuan Menjawab Pertanyaan Juri</strong> 
                                            <span class="badge bg-primary">Bobot 15%</span>
                                        </label>
                                        <input type="number" 
                                               name="kemampuan_menjawab" 
                                               class="form-control" 
                                               min="1" max="100" 
                                               value="{{ old('kemampuan_menjawab', $userAssessment['aspek_scores']['kemampuan_menjawab']['score'] ?? '') }}" 
                                               required>
                                        <small class="text-muted">Nilai 1-100</small>
                                    </div>

                                    <!-- Kreativitas & Daya Tarik Presentasi -->
                                    <div class="mb-4">
                                        <label class="form-label">
                                            <strong>Kreativitas & Daya Tarik Presentasi</strong> 
                                            <span class="badge bg-primary">Bobot 10%</span>
                                        </label>
                                        <input type="number" 
                                               name="kreativitas_daya_tarik" 
                                               class="form-control" 
                                               min="1" max="100" 
                                               value="{{ old('kreativitas_daya_tarik', $userAssessment['aspek_scores']['kreativitas_daya_tarik']['score'] ?? '') }}" 
                                               required>
                                        <small class="text-muted">Nilai 1-100</small>
                                    </div>

                                    <!-- Catatan Juri -->
                                    <div class="mb-4">
                                        <label class="form-label"><strong>Catatan Juri</strong></label>
                                        <textarea name="catatan_juri" 
                                                  class="form-control" 
                                                  rows="4" 
                                                  placeholder="Tuliskan apresiasi, keunggulan utama, serta rekomendasi perbaikan yang dapat memperkuat implementasi ke depan">{{ old('catatan_juri', $userAssessment['catatan'] ?? '') }}</textarea>
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
                                        <a href="{{ route('dashboard.presentation.bpkh.index') }}" class="btn btn-secondary">Batal</a>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5 class="card-title">Informasi Bobot</h5>
                                            <p class="text-muted small">Total bobot semua aspek adalah 100%</p>
                                            <ul class="list-unstyled">
                                                <li class="mb-2">✓ Substansi & Capaian Kinerja: <strong>30%</strong></li>
                                                <li class="mb-2">✓ Implementasi Strategi & Dampak: <strong>20%</strong></li>
                                                <li class="mb-2">✓ Kedalaman Analisis: <strong>15%</strong></li>
                                                <li class="mb-2">✓ Kejelasan & Alur Penyampaian: <strong>10%</strong></li>
                                                <li class="mb-2">✓ Kemampuan Menjawab Pertanyaan: <strong>15%</strong></li>
                                                <li class="mb-2">✓ Kreativitas & Daya Tarik: <strong>10%</strong></li>
                                            </ul>
                                            <hr>
                                            <p class="text-muted small mb-0">
                                                <strong>Catatan:</strong> Nilai akhir dihitung berdasarkan rata-rata tertimbang dari semua aspek penilaian.
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
