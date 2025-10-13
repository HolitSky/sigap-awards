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
                                <h4 class="mb-0">Responden Id :{{ $form->respondent_id }} <br> {{ $form->nama_bpkh }}</h4>
                                @if(!empty($form->juri_penilai))
                                    <div class="mt-2">
                                        <span class="badge rounded-pill text-white" style="background-color:#D26607;padding:.5rem .75rem;font-size:.9rem;">
                                            User Penilai Terakhir: {{ $form->juri_penilai }}
                                        </span>
                                    </div>
                                @endif
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
                            <a href="{{ route('dashboard.form.bpkh.score.edit', $form->respondent_id) }}" class="btn btn-primary">Nilai Ulang Form</a>
                        </div>

                        @php
                            // Build entries once for aggregation
                            $calcEntries = [];
                            if (is_array($form->meta)) {
                                $isOrderedCalc = isset($form->meta[0]) && is_array($form->meta[0]) && array_key_exists('key', $form->meta[0]) && array_key_exists('value', $form->meta[0]);
                                if ($isOrderedCalc) {
                                    foreach ($form->meta as $item) { $calcEntries[] = [$item['key'] ?? '', $item['value'] ?? '']; }
                                } else {
                                    foreach ($form->meta as $k => $v) { $calcEntries[] = [$k, $v]; }
                                }
                            }

                            // Aggregate per SP (first number before dot in "soal X.Y") and capture item details
                            $spTotals = [];
                            $spItems = [];
                            foreach ($calcEntries as [$k, $v]) {
                                if (preg_match('/^\s*soal\s+([0-9]+(?:\.[0-9]+)*)/i', (string) $k, $mm)) {
                                    $code = $mm[1];
                                    $sp = (int) explode('.', $code)[0];
                                    $num = null;
                                    if (!is_array($v) && is_numeric($v)) {
                                        $num = (float) $v;
                                    }
                                    if ($num !== null) {
                                        if (!isset($spTotals[$sp])) { $spTotals[$sp] = ['sum' => 0.0, 'count' => 0]; }
                                        $spTotals[$sp]['sum'] += $num;
                                        $spTotals[$sp]['count'] += 1;
                                        if (!isset($spItems[$sp])) { $spItems[$sp] = []; }
                                        $spItems[$sp][] = ['code' => $code, 'value' => $num];
                                    }
                                }
                            }
                            ksort($spTotals);
                            $overallSumAvg = 0.0;
                            $overallCountSp = 0;
                        @endphp

                        <h5 class="mt-4">Ringkasan Nilai per SP</h5>
                        <table class="table table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>SP</th>
                                    <th>Jumlah Soal</th>
                                    <th>Jumlah Nilai Per Soal</th>
                                    <th>Nilai (rata-rata)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($spTotals as $sp => $tot)
                                    @php $sum = $tot['sum']; $count = $tot['count']; $avg = $count ? $sum / $count : null; @endphp
                                    <tr>
                                        <td>SP {{ $sp }}{{ isset($spLabels[$sp]) ? ' - '.$spLabels[$sp] : '' }}</td>
                                        <td>{{ $count }}</td>
                                        <td>
                                            @php $items = $spItems[$sp] ?? []; @endphp
                                            <span class="fw-semibold">total = {{ $sum+0 }}</span>
                                            <a href="#" class="ms-2" data-bs-toggle="collapse" data-bs-target="#sp-details-{{ $sp }}" aria-expanded="false" aria-controls="sp-details-{{ $sp }}">
                                                <i class="mdi mdi-eye-outline"></i>
                                            </a>
                                        </td>
                                        <td>
                                            @php if ($avg !== null) { $overallSumAvg += $avg; $overallCountSp++; } @endphp
                                            {{ $avg === null ? '-' : round($avg) }}
                                        </td>
                                    </tr>
                                    <tr class="collapse" id="sp-details-{{ $sp }}">
                                        <td colspan="4">
                                            @foreach($items as $it)
                                                <div>soal {{ $it['code'] }} = {{ $it['value']+0 }}</div>
                                            @endforeach
                                            <div class="fw-semibold mt-1">total = {{ $sum+0 }}</div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Belum ada jawaban soal yang dapat dihitung.</td>
                                    </tr>
                                @endforelse
                                @if($overallCountSp)
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Total Penilaian Keseluruhan</td>
                                        <td class="fw-bold">{{ round($overallSumAvg / $overallCountSp) }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        @php
                            $__spCats = [];
                            $__spData = [];
                            foreach ($spTotals as $sp => $tot) {
                                $avgVal = $tot['count'] ? $tot['sum'] / $tot['count'] : null;
                                $__spCats[] = 'SP '.$sp.(isset($spLabels[$sp]) ? ' - '.$spLabels[$sp] : '');
                                $__spData[] = $avgVal === null ? 0 : round($avgVal);
                            }
                        @endphp

                        <div class="my-4">
                            <h5 class="mb-3">Radar Nilai per SP</h5>
                            <div id="spRadarChart" style="width:100%;height:560px;"></div>
                        </div>

                        @push('scripts')
                        <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            var categories = @json($__spCats);
                            var seriesData = @json($__spData);
                            var el = document.querySelector('#spRadarChart');
                            if (!el || typeof ApexCharts === 'undefined') return;
                            var options = {
                                chart: { type: 'radar', height: 560, toolbar: { show: false } },
                                series: [{ name: 'Nilai', data: seriesData }],
                                xaxis: { categories: categories, labels: { style: { colors: '#6c757d' } } },
                                yaxis: { min: 0, max: 100, tickAmount: 4, labels: { style: { colors: '#6c757d' } } },
                                stroke: { width: 2 },
                                fill: { opacity: 0.2 },
                                markers: { size: 3 },
                                dataLabels: { enabled: false }
                            };
                            new ApexCharts(el, options).render();
                        });
                        </script>
                        @endpush

                        <h5 class="mt-4">Hasil Jawaban Detail</h5>
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
                                    $isKonsep = preg_match('/^\s*Konsep\s+Paparan/i', (string) $key);
                                    $rowStyle = 'color:#2B2929;';
                                    if ($isKonsep) {
                                        $rowStyle = 'color:#FFFFFF;background-color:#E03737;';
                                    } elseif ($isQuestion) {
                                        $rowStyle .= 'background-color:#E2FFF4;';
                                    } elseif ($isAnswer) {
                                        $rowStyle .= 'background-color:#F8F6FF;';
                                    }
                                @endphp
                                <div class="row gx-3 align-items-start py-2 px-3{{ !$loop->last ? ' border-bottom' : '' }}" style="{{ $rowStyle }}">
                                    <div class="col-12 col-md-5 fs-6 {{ $isQuestion ? 'fw-bold' : 'fw-semibold' }} {{ $isKonsep ? 'text-white' : '' }}">
                                        @php $isAttachment = !$isQuestion && !$isAnswer && preg_match('/^\s*Lampiran/i', (string) $key); @endphp
                                        @if($isQuestion)
                                            <div>SP {{ $m[1] }} :</div>
                                            <div class="mt-1">{{ $key }}</div>
                                        @elseif($isAnswer)
                                            <div>Jawaban soal {{ $am[1] }}</div>
                                        @else
                                            @if($isAttachment)
                                                <i class="mdi mdi-file-document-outline me-1"></i>{{ $key }}
                                            @else
                                                {{ $key }}
                                            @endif
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-7 fw-semibold text-break {{ $isKonsep ? 'text-white' : '' }}">
                                        @php
                                            $isArray = is_array($value);
                                            $raw = $isArray ? json_encode($value) : (string) $value;
                                            $display = $raw === '' ? '-' : $raw;
                                            $isUrl = !$isArray && preg_match('/^https?:\/\//i', $raw);
                                        @endphp
                                        @if($isUrl)
                                            <a href="{{ $raw }}" target="_blank" rel="noopener" class="{{ $isKonsep ? 'text-white' : '' }}">{{ $raw }}</a>
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
