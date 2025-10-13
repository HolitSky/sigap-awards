@extends('dashboard.layouts.app')
@section('title', 'Nilai Form BPKH')
@section('content')

<div class="page-content">
    <div class="container-fluid">

        @include('dashboard.pages.form.components.sub-head')

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">Nilai Form — {{ $form->respondent_id }} — {{ $form->nama_bpkh }}</h4>

                        <form method="post" action="{{ route('dashboard.form.bpkh.score.update', $form->respondent_id) }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-bold">Status Nilai</label>
                                <select name="status_nilai" class="form-select" required>
                                    <option value="pending" {{ $form->status_nilai==='pending'?'selected':'' }}>pending</option>
                                    <option value="in_review" {{ $form->status_nilai==='in_review'?'selected':'' }}>in_review</option>
                                    <option value="scored" {{ $form->status_nilai==='scored'?'selected':'' }}>scored</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Total Score</label>
                                <input type="number" name="total_score" class="form-control" min="0" max="100" value="{{ old('total_score', $form->total_score) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Catatan</label>
                                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $form->notes) }}</textarea>
                            </div>

                            <h5 class="mt-4">Update Jawaban Soal</h5>
                            <div class="border rounded-3">
                                @php
                                    $entries = [];
                                    if (is_array($form->meta)) {
                                        $isOrdered = isset($form->meta[0]) && is_array($form->meta[0]) && array_key_exists('key', $form->meta[0]) && array_key_exists('value', $form->meta[0]);
                                        if ($isOrdered) {
                                            foreach ($form->meta as $item) { $entries[] = [$item['key'], $item['value']]; }
                                        } else {
                                            foreach ($form->meta as $k => $v) { $entries[] = [$k, $v]; }
                                        }
                                    }
                                @endphp

                                @foreach($entries as [$key, $value])
                                    @php
                                        $isAnswer = preg_match('/^\s*soal\s+([0-9]+(?:\.[0-9]+)*)/i', (string) $key, $am);
                                    @endphp
                                    @if($isAnswer)
                                        <div class="row gx-3 align-items-center py-2 px-3 border-bottom">
                                            <div class="col-12 col-md-6 text-muted">Jawaban soal {{ $am[1] }}</div>
                                            <div class="col-12 col-md-6">
                                                <input type="number" name="answers[{{ $am[1] }}]" class="form-control" min="0" max="100" value="{{ is_array($value)?'':$value }}" />
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <div class="mt-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('dashboard.form.bpkh.show', $form->respondent_id) }}" class="btn btn-light">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
