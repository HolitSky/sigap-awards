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

                        <div class="mb-3">
                            <a href="{{ route('dashboard.form.bpkh.index') }}" class="btn btn-light">
                                <i class="mdi mdi-arrow-left me-1"></i> Kembali
                            </a>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h4 class="mb-0">Responden Id :{{ $form->respondent_id }} <br> {{ $form->nama_bpkh }}</h4>
                                @if($latestAssessment)
                                    <div class="mt-2 d-flex align-items-center gap-2">
                                        <span class="badge rounded-pill text-white" style="background-color:#D26607;padding:.5rem .75rem;font-size:.9rem;">
                                            <i class="mdi mdi-account me-1"></i>
                                            User Penilai Terakhir: {{ $latestAssessment->user_name }}
                                            <small class="ms-2">({{ $latestAssessment->created_at->format('d M Y, H:i') }})</small>
                                        </span>
                                        <button type="button" class="btn btn-sm btn-info" onclick="showHistoryModal()">
                                            <i class="mdi mdi-history"></i> History
                                        </button>
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
                                <span class="badge {{ $badgeClass }} rounded-pill" style="padding:.5rem .75rem;font-size:.95rem;">{{ $form->status_label ?? $form->status_nilai }}</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div><strong>Petugas</strong>: {{ $form->petugas_bpkh ?: '-' }}</div>
                            <div><strong>Phone/WA</strong>: {{ $form->phone ?: '-' }}</div>
                            <div><strong>Website</strong>: @if($form->website)<a href="{{ str_starts_with($form->website, 'http') ? $form->website : 'https://'.$form->website }}" target="_blank">{{ $form->website }}</a>@else - @endif</div>
                        </div>

                        @if(auth()->user()->role !== 'admin-view')
                        <div class="mb-3">
                            <a href="{{ route('dashboard.form.bpkh.score.edit', $form->respondent_id) }}" class="btn btn-primary">Nilai Ulang Form</a>
                        </div>
                        @endif

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
                                            <a href="#sp-details-{{ $sp }}" class="ms-2" data-bs-toggle="collapse" data-bs-target="#sp-details-{{ $sp }}" aria-expanded="false" aria-controls="sp-details-{{ $sp }}" onclick="event.preventDefault();">
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
                            <h5 class="mb-3">Grafik Radar Nilai per SP</h5>
                            <canvas id="spRadarChart" style="width:100%;max-width:720px;aspect-ratio:1/1;margin:0 auto;display:block;"></canvas>
                            <div class="mt-2 d-flex gap-2 justify-content-center">
                                <button type="button" class="btn btn-sm btn-outline-primary" id="exportRadarPng">Download PNG</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="exportRadarJpg">Download JPEG</button>
                            </div>
                        </div>

                        @push('scripts')
                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            var labels = @json($__spCats);
                            var values = @json($__spData);
                            var chartTitle = 'Nama Responden: ' + @json($form->nama_bpkh);
                            var canvas = document.getElementById('spRadarChart');
                            if (!canvas) return;
                            var ctx = canvas.getContext('2d');

                            var data = {
                                labels: labels,
                                datasets: [{
                                    label: 'Nilai',
                                    data: values,
                                    fill: true,
                                    backgroundColor: 'rgba(30, 136, 229, 0.20)',
                                    borderColor: '#1E88E5',
                                    pointBackgroundColor: '#1E88E5',
                                    pointBorderColor: '#FFFFFF',
                                    pointHoverBackgroundColor: '#FFFFFF',
                                    pointHoverBorderColor: '#1E88E5',
                                    borderWidth: 2
                                }]
                            };

                            var options = {
                                responsive: true,
                                maintainAspectRatio: true,
                                aspectRatio: 1,
                                scales: {
                                    r: {
                                        min: 0,
                                        max: 100,
                                        ticks: { stepSize: 25, color: '#6c757d' },
                                        pointLabels: { color: '#6c757d' }
                                    }
                                },
                                elements: {
                                    point: { radius: 3, hitRadius: 4, hoverRadius: 5 },
                                    line: { borderWidth: 2 }
                                },
                                interaction: { mode: 'nearest', intersect: true },
                                plugins: {
                                    legend: { display: false },
                                    title: {
                                        display: true,
                                        text: chartTitle,
                                        color: '#2B2929',
                                        align: 'center',
                                        padding: { top: 4, bottom: 4 },
                                        font: { size: 14, weight: '600' }
                                    },
                                    tooltip: {
                                        enabled: true,
                                        callbacks: {
                                            label: function (ctx) {
                                                var v = (ctx.parsed && ctx.parsed.r != null) ? ctx.parsed.r : ctx.raw;
                                                return 'Nilai: ' + Math.round(v);
                                            }
                                        }
                                    }
                                }
                            };

                            if (canvas._chartInstance) { canvas._chartInstance.destroy(); }
                            canvas._chartInstance = new Chart(ctx, { type: 'radar', data: data, options: options });

                            var download = function (dataUrl, filename) {
                                var a = document.createElement('a');
                                a.href = dataUrl;
                                a.download = filename;
                                document.body.appendChild(a);
                                a.click();
                                document.body.removeChild(a);
                            };

                            var exportPngBtn = document.getElementById('exportRadarPng');
                            var exportJpgBtn = document.getElementById('exportRadarJpg');
                            if (exportPngBtn) {
                                exportPngBtn.addEventListener('click', function () {
                                    var url = canvas.toDataURL('image/png', 1.0);
                                    download(url, 'grafik-radar.png');
                                });
                            }
                            if (exportJpgBtn) {
                                exportJpgBtn.addEventListener('click', function () {
                                    // Create white background for JPEG to avoid black transparency
                                    var bgCanvas = document.createElement('canvas');
                                    bgCanvas.width = canvas.width;
                                    bgCanvas.height = canvas.height;
                                    var bgCtx = bgCanvas.getContext('2d');
                                    bgCtx.fillStyle = '#FFFFFF';
                                    bgCtx.fillRect(0, 0, bgCanvas.width, bgCanvas.height);
                                    bgCtx.drawImage(canvas, 0, 0);
                                    var url = bgCanvas.toDataURL('image/jpeg', 0.95);
                                    download(url, 'grafik-radar.jpg');
                                });
                            }
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
                                            // Extract one or more URLs if present (handles multiple links in one string)
                                            $links = [];
                                            if ($isArray) {
                                                foreach ((array) $value as $vv) {
                                                    if (is_string($vv) && preg_match_all('/https?:\/\/\S+/i', $vv, $mAll)) {
                                                        foreach ($mAll[0] as $u) { $links[] = $u; }
                                                    }
                                                }
                                            } else {
                                                if (preg_match_all('/https?:\/\/\S+/i', $raw, $mAll)) {
                                                    $links = $mAll[0];
                                                }
                                            }
                                        @endphp
                                        @if(count($links) > 1)
                                            @foreach($links as $u)
                                                <div><a href="{{ $u }}" target="_blank" rel="noopener" class="{{ $isKonsep ? 'text-white' : '' }}">{{ $u }}</a></div>
                                            @endforeach
                                        @elseif(count($links) === 1)
                                            <a href="{{ $links[0] }}" target="_blank" rel="noopener" class="{{ $isKonsep ? 'text-white' : '' }}">{{ $links[0] }}</a>
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

<!-- History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historyModalLabel">
                    <i class="mdi mdi-history"></i> Riwayat Penilaian
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="historyContent">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function showHistoryModal() {
        const modal = new bootstrap.Modal(document.getElementById('historyModal'));
        modal.show();

        // Fetch history data
        fetch('{{ route('dashboard.form.bpkh.history', $form->respondent_id) }}')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.length > 0) {
                    let html = '<div class="timeline">';

                    data.data.forEach((record, index) => {
                        const date = new Date(record.created_at);
                        const formattedDate = date.toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });

                        // Build meta changes HTML
                        let metaChangesHtml = '';
                        if (record.meta_changes && Object.keys(record.meta_changes).length > 0) {
                            metaChangesHtml = '<div class="mt-3"><strong style="font-size: 1rem;">Jawaban yang dinilai ulang:</strong><ul class="mb-0" style="font-size: 0.95rem;">';
                            for (const [code, change] of Object.entries(record.meta_changes)) {
                                metaChangesHtml += `<li class="mb-1">Soal ${code}: <span class="text-danger fw-bold">${change.old || 'kosong'}</span> → <span class="text-success fw-bold">${change.new}</span></li>`;
                            }
                            metaChangesHtml += '</ul></div>';
                        }

                        html += `
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-2">
                                                <i class="mdi mdi-account-circle text-primary"></i>
                                                ${record.user_name}
                                            </h6>
                                            <p class="text-muted mb-2">
                                                <small>
                                                    <i class="mdi mdi-email-outline"></i> ${record.user_email} |
                                                    <i class="mdi mdi-shield-account"></i> ${record.user_role}
                                                </small>
                                            </p>
                                            ${record.total_score !== null ? `<p class="mb-2" style="font-size: 1rem;"><strong>Nilai Final:</strong> <span class="badge bg-primary" style="font-size: 1.1rem; padding: 0.5rem 0.75rem;">${record.total_score}</span></p>` : ''}
                                            ${metaChangesHtml}
                                            ${record.notes ? `<p class="mb-0 mt-3" style="font-size: 0.95rem;"><strong>Catatan:</strong><br><span class="text-dark">${record.notes}</span></p>` : ''}
                                        </div>
                                        <div class="text-end ms-3">
                                            <span class="badge bg-info">${record.action_type}</span>
                                            <p class="text-muted mb-0 mt-1">
                                                <small><i class="mdi mdi-clock-outline"></i> ${formattedDate}</small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    html += '</div>';
                    document.getElementById('historyContent').innerHTML = html;
                } else {
                    document.getElementById('historyContent').innerHTML = `
                        <div class="alert alert-info">
                            <i class="mdi mdi-information"></i> Belum ada riwayat penilaian.
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('historyContent').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="mdi mdi-alert"></i> Terjadi kesalahan saat mengambil data.
                    </div>
                `;
            });
    }
</script>
@endpush
