@extends('dashboard.layouts.app')
@section('title', 'Detail Penilaian Exhibition/Poster Produsen DG')
@section('content')

<div class="page-content">
    <div class="container-fluid">
        @include('dashboard.pages.form.components.sub-head')

        <div class="row">
            <div class="col-12">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Info Card -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ $form->nama_instansi }}</h4>
                        <p class="text-muted mb-3">Petugas: {{ $form->nama_petugas }}</p>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="text-muted small">Jumlah Juri</label>
                                    <h5>{{ count($form->penilaian_per_juri ?? []) }} Juri</h5>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="text-muted small">Nilai Final</label>
                                    <h5>{{ $form->nilai_final !== null ? number_format($form->nilai_final, 2) : '-' }}</h5>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="text-muted small">Bobot Exhibition</label>
                                    <h5>{{ number_format($form->bobot_exhibition, 0) }}%</h5>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="text-muted small">Nilai Final Dengan Bobot</label>
                                    <h5>{{ $form->nilai_final_dengan_bobot !== null ? number_format($form->nilai_final_dengan_bobot, 2) : '-' }}</h5>
                                </div>
                            </div>
                        </div>

                        @if($form->kategori_penilaian)
                            <div class="alert alert-info">
                                <strong>Kategori Penilaian: {{ $form->kategori_penilaian }}</strong><br>
                                {{ $form->deskripsi_kategori }}
                            </div>
                        @endif

                        <a href="{{ route('dashboard.exhibition.produsen.edit', $form->respondent_id) }}" class="btn btn-primary">
                            Nilai / Edit Penilaian
                        </a>
                        <a href="{{ route('dashboard.exhibition.produsen.index') }}" class="btn btn-secondary">
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>

                <!-- Penilaian Per Juri -->
                @if(!empty($form->penilaian_per_juri))
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Penilaian dari Juri</h4>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Juri</th>
                                            <th>Nilai Akhir</th>
                                            <th>Rekomendasi</th>
                                            <th>Catatan</th>
                                            <th>Waktu Penilaian</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($form->penilaian_per_juri as $index => $penilaian)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $penilaian['user_name'] ?? 'N/A' }}</td>
                                                <td class="text-center">
                                                    <strong>{{ number_format($penilaian['nilai_akhir_user'] ?? 0, 2) }}</strong>
                                                </td>
                                                <td>{{ $penilaian['rekomendasi'] ?? '-' }}</td>
                                                <td>{{ $penilaian['catatan'] ?? $penilaian['catatan_juri'] ?? '-' }}</td>
                                                <td>{{ isset($penilaian['assessed_at']) ? \Carbon\Carbon::parse($penilaian['assessed_at'])->format('d/m/Y H:i') : (isset($penilaian['created_at']) ? \Carbon\Carbon::parse($penilaian['created_at'])->format('d/m/Y H:i') : '-') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Riwayat Penilaian -->
                @if($assessmentHistory->count() > 0)
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Riwayat Penilaian</h4>
                            
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Waktu</th>
                                            <th>Juri</th>
                                            <th>Nilai</th>
                                            <th>Rekomendasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($assessmentHistory as $record)
                                            <tr>
                                                <td>{{ $record->assessed_at->format('d/m/Y H:i') }}</td>
                                                <td>{{ $record->user_name }}</td>
                                                <td>{{ number_format($record->nilai_akhir_user, 2) }}</td>
                                                <td>{{ $record->rekomendasi }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
