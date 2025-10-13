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

                        <h5 class="mt-4">Semua Jawaban</h5>
                        <dl class="row">
                            @foreach(($form->meta ?? []) as $key => $value)
                                <dt class="col-sm-4">{{ $key }}</dt>
                                <dd class="col-sm-8">{{ is_array($value) ? json_encode($value) : ($value === '' ? '-' : $value) }}</dd>
                            @endforeach
                        </dl>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
