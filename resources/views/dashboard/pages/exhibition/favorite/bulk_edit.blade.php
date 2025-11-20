@extends('dashboard.layouts.app')
@section('title', 'Input Penilaian Kolektif Poster Favorit')
@section('content')

<div class="page-content">
    <div class="container-fluid">

        @include('dashboard.pages.form.components.sub-head')

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-1">Input Penilaian Kolektif Poster Favorit</h4>
                        <p class="card-title-desc mb-4">Input jumlah vote untuk beberapa peserta sekaligus</p>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('dashboard.favorite-poster.bulk-update') }}" id="bulk-form">
                            @csrf

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 5%;">No</th>
                                            <th style="width: 30%;">Nama Peserta</th>
                                            <th style="width: 12%;">Kategori</th>
                                            <th style="width: 18%;">Petugas</th>
                                            <th style="width: 15%;">Jumlah Vote <span class="text-danger">*</span></th>
                                            <th style="width: 20%;">Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($votes as $index => $vote)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>
                                                <strong>{{ $vote->participant_name }}</strong>
                                                <br><small class="text-muted">ID: {{ $vote->respondent_id }}</small>
                                            </td>
                                            <td class="text-center">
                                                @if($vote->participant_type === 'bpkh')
                                                    <span class="badge bg-primary">BPKH</span>
                                                @else
                                                    <span class="badge bg-success">Produsen DG</span>
                                                @endif
                                            </td>
                                            <td>{{ $vote->petugas ?? '-' }}</td>
                                            <td>
                                                <input
                                                    type="number"
                                                    class="form-control form-control-sm"
                                                    name="participants[{{ $vote->id }}][vote_count]"
                                                    value="{{ old('participants.'.$vote->id.'.vote_count', $vote->vote_count > 0 ? $vote->vote_count : '') }}"
                                                    placeholder="0"
                                                    min="0"
                                                    required
                                                >
                                            </td>
                                            <td>
                                                <textarea
                                                    class="form-control form-control-sm"
                                                    name="participants[{{ $vote->id }}][notes]"
                                                    rows="2"
                                                    maxlength="1000"
                                                >{{ old('participants.'.$vote->id.'.notes', $vote->notes) }}</textarea>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-content-save me-1"></i> Simpan Semua
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

@push('styles')
<style>
    .table td {
        vertical-align: middle;
    }
    .form-control-sm {
        font-size: 0.875rem;
    }
</style>
@endpush
