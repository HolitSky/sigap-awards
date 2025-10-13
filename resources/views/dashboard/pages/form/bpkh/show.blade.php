@extends('dashboard.layouts.app')
@section('title', 'Detail Respon BPKH')
@section('content')

<div class="page-content">
    <div class="container-fluid">

        @include('dashboard.pages.form.components.sub-head')

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h4 class="mb-0">{{ $form->respondent_id }} — {{ $form->nama_bpkh }}</h4>
                            </div>
                            <div>
                                @php
                                    $badgeClass = match($form->status_nilai) {
                                        'pending' => 'bg-secondary',
                                        'in_review' => 'bg-warning',
                                        'scored' => 'bg-success',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $form->status_nilai }}</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div><strong>Petugas</strong>: {{ $form->petugas_bpkh ?: '-' }}</div>
                            <div><strong>Phone/WA</strong>: {{ $form->phone ?: '-' }}</div>
                            <div><strong>Website</strong>: @if($form->website)<a href="{{ str_starts_with($form->website, 'http') ? $form->website : 'https://'.$form->website }}" target="_blank">{{ $form->website }}</a>@else - @endif</div>
                        </div>

                        <div class="mb-3">
                            <a href="{{ route('dashboard.form.bpkh.score.edit', $form->respondent_id) }}" class="btn btn-primary">Nilai Form</a>
                        </div>

                        <h5 class="mt-4">Hasil Jawaban</h5>
                        <div class="border rounded-3">
                            @php
                                // Support both legacy associative array and new ordered array format
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
                                    $isQuestion = preg_match('/^\s*(\d+)\s*\./', (string) $key, $m);
                                    $isAnswer = preg_match('/^\s*soal\s+([0-9]+(?:\.[0-9]+)*)/i', (string) $key, $am);
                                    $rowStyle = 'color:#2B2929;';
                                    if ($isAnswer) { $rowStyle .= 'background-color:#F8F6FF;'; }
                                @endphp
                                <div class="row gx-3 align-items-start py-2 px-3{{ !$loop->last ? ' border-bottom' : '' }}" style="{{ $rowStyle }}">
                                    <div class="col-12 col-md-5 fs-6 {{ $isQuestion ? 'fw-bold' : 'fw-semibold' }}">
                                        @if($isQuestion)
                                            <div>SP {{ $m[1] }} :</div>
                                            <div class="mt-1">{{ $key }}</div>
                                        @elseif($isAnswer)
                                            <div>Jawaban soal {{ $am[1] }}</div>
                                        @else
                                            {{ $key }}
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-7 fw-semibold text-break">
                                        @php
                                            $isArray = is_array($value);
                                            $raw = $isArray ? json_encode($value) : (string) $value;
                                            $display = $raw === '' ? '-' : $raw;
                                            $isUrl = !$isArray && preg_match('/^https?:\/\//i', $raw);
                                        @endphp
                                        @if($isUrl)
                                            <a href="{{ $raw }}" target="_blank" rel="noopener">{{ $raw }}</a>
                                        @else
                                            {{ $display }}
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
