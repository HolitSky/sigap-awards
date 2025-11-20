@extends('dashboard.layouts.app')
@section('title', 'Input Penilaian Poster Favorit')
@section('content')

<div class="page-content">
    <div class="container-fluid">

        @include('dashboard.pages.form.components.sub-head')

        <div class="row">
            <div class="col-lg-8 col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Input Penilaian Poster Favorit</h4>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('dashboard.favorite-poster.update', $vote->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Nama Peserta</label>
                                <input type="text" class="form-control" value="{{ $vote->participant_name }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <input type="text" class="form-control" value="{{ $vote->participant_type === 'bpkh' ? 'BPKH' : 'Produsen DG' }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Petugas</label>
                                <input type="text" class="form-control" value="{{ $vote->petugas ?? '-' }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="vote_count" class="form-label">Jumlah Vote <span class="text-danger">*</span></label>
                                <input
                                    type="number"
                                    class="form-control @error('vote_count') is-invalid @enderror"
                                    id="vote_count"
                                    name="vote_count"
                                    value="{{ old('vote_count', $vote->vote_count > 0 ? $vote->vote_count : '') }}"
                                    placeholder="0"
                                    min="0"
                                    required
                                >
                                @error('vote_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Masukkan jumlah vote yang diterima (angka bulat, minimal 0)</small>
                            </div>

                            <div class="mb-4">
                                <label for="notes" class="form-label">Catatan</label>
                                <textarea
                                    class="form-control @error('notes') is-invalid @enderror"
                                    id="notes"
                                    name="notes"
                                    rows="3"
                                    maxlength="1000"
                                >{{ old('notes', $vote->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Catatan tambahan (opsional, maksimal 1000 karakter)</small>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-content-save me-1"></i> Simpan
                                </button>
                                <a href="{{ route('dashboard.favorite-poster.index') }}" class="btn btn-secondary">
                                    <i class="mdi mdi-arrow-left me-1"></i> Kembali
                                </a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
