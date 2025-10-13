@extends('dashboard.layouts.app')
@section('title', 'Nilai Ulang Form Produsen DG')
@section('content')

<div class="page-content">
    <div class="container-fluid">

        @include('dashboard.pages.form.components.sub-head')

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <a href="{{ route('dashboard.form.produsen-dg.show', $form->respondent_id) }}" class="btn btn-light">
                                <i class="mdi mdi-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                        <h4 class="mb-3">Nilai Ulang Form Produsen DG — {{ $form->nama_petugas }}</h4>

                        <form method="post" action="{{ route('dashboard.form.produsen-dg.score.update', $form->respondent_id) }}">
                            @csrf

                            <div class="mb-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('dashboard.form.produsen-dg.show', $form->respondent_id) }}" class="btn btn-light">Batal</a>
                            </div>

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
                                <input type="number" name="total_score" class="form-control" min="0" max="100" value="{{ old('total_score', $form->total_score) }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Catatan</label>
                                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $form->notes) }}</textarea>
                            </div>

                            {{-- start of Ringkasan Nilai --}}

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
                            <tbody id="spSummaryBody">
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

                            {{-- end of Ringkasan Nilai --}}

                            <div class="my-4">
                                <h5 class="mb-3">Grafik Radar Nilai per SP</h5>
                                <canvas id="spRadarChartScore" style="width:100%;max-width:720px;aspect-ratio:1/1;margin:0 auto;display:block;"></canvas>
                                <div class="mt-2 d-flex gap-2 justify-content-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="exportRadarPngScore">Download PNG</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="exportRadarJpgScore">Download JPEG</button>
                                </div>
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
                                    // Build a robust map from question code (e.g. 1.1) to full question text for popover
                                    $questionTextByCode = [];
                                    foreach ($entries as [$k, $v]) {
                                        $ks = (string) $k;
                                        // Skip explicit answer keys like "soal 1.1"
                                        if (preg_match('/^\s*soal\s+/i', $ks)) { continue; }
                                        // Pattern: starts with code, optional separators (: ) . - – —), then text
                                        if (preg_match('/^\s*([0-9]+(?:\.[0-9]+)*)\s*[:\)\.\-\xE2\x80\x93\xE2\x80\x94]?\s*(.*)$/u', $ks, $qm)) {
                                            $qCode = $qm[1];
                                            $qText = trim($qm[2]) === '' ? trim($ks) : $qm[2];
                                            if (!isset($questionTextByCode[$qCode])) { $questionTextByCode[$qCode] = $qText; }
                                            continue;
                                        }
                                        // Fallback: any occurrence of code inside the key
                                        if (preg_match('/\b([0-9]+(?:\.[0-9]+)*)\b/u', $ks, $qm2)) {
                                            $qCode = $qm2[1];
                                            if (!isset($questionTextByCode[$qCode])) { $questionTextByCode[$qCode] = trim($ks); }
                                        }
                                    }
                                @endphp

                                @foreach($entries as [$key, $value])
                                    @php
                                        $isAnswer = preg_match('/^\s*soal\s+([0-9]+(?:\.[0-9]+)*)/i', (string) $key, $am);
                                    @endphp
                                    @if($isAnswer)
                                        @php
                                            $nextEntry = $entries[$loop->index + 1] ?? null;
                                            $attachmentUrl = null; $attachmentIsUrl = false;
                                            if ($nextEntry && preg_match('/^\s*Lampiran/i', (string) ($nextEntry[0] ?? ''))) {
                                                $attVal = $nextEntry[1] ?? '';
                                                $isAttArray = is_array($attVal);
                                                $attRaw = $isAttArray ? json_encode($attVal) : (string) $attVal;
                                                $attachmentUrl = $attRaw;
                                                $attachmentIsUrl = (!$isAttArray && preg_match('/^https?:\/\//i', $attRaw));
                                            }
                                        @endphp
                                        <div class="row gx-3 align-items-center py-2 px-3 border-bottom">
                                            <div class="col-12 col-md-6 text-muted">
                                                <button type="button" class="btn btn-link p-0 js-question-popover" data-code="{{ $am[1] }}" data-bs-toggle="popover" data-bs-placement="top" data-bs-trigger="focus" data-bs-html="true">Jawaban soal {{ $am[1] }}</button>
                                                @if($attachmentUrl)
                                                    <div class="mt-1 small">
                                                        @if($attachmentIsUrl)
                                                            <a href="{{ $attachmentUrl }}" target="_blank" rel="noopener">Lampiran</a>
                                                        @else
                                                            Lampiran: {{ $attachmentUrl }}
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <input type="number" name="answers[{{ $am[1] }}]" class="form-control" min="0" max="100" value="{{ is_array($value)?'':$value }}" />
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <h5 class="mt-4">Konsep Paparan</h5>
                            <div class="border rounded-3">
                                @php $hasKonsep = false; @endphp
                                @foreach(($entries ?? []) as [$k, $v])
                                    @php
                                        $isKonsep = preg_match('/^\s*Konsep\s+Paparan/i', (string) $k);
                                        $isArray = is_array($v);
                                        $raw = $isArray ? json_encode($v) : (string) $v;
                                        $display = $raw === '' ? '-' : $raw;
                                        $isUrl = !$isArray && preg_match('/^https?:\/\//i', $raw);
                                    @endphp
                                    @if($isKonsep)
                                        @php $hasKonsep = true; @endphp
                                        <div class="row gx-3 align-items-start py-2 px-3 border-bottom">
                                            <div class="col-12 col-md-5 fs-6 fw-semibold">{{ $k }}</div>
                                            <div class="col-12 col-md-7 fw-semibold text-break">
                                                @if($isUrl)
                                                    <a href="{{ $raw }}" target="_blank" rel="noopener">{{ $raw }}</a>
                                                @else
                                                    {{ $display }}
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                @if(!$hasKonsep)
                                    <div class="p-3 text-muted">Belum ada data Konsep Paparan.</div>
                                @endif
                            </div>

                            <div class="mt-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('dashboard.form.produsen-dg.show', $form->respondent_id) }}" class="btn btn-light">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var answersNodeList = document.querySelectorAll('input[name^="answers["]');
    var summaryBody = document.getElementById('spSummaryBody');
    var totalScoreInput = document.querySelector('input[name="total_score"]');
    var spLabels = { 1: 'Tata Kelola dan Institusi', 2: 'Kebijakan dan Hukum', 3: 'Finansial', 4: 'Data', 5: 'Inovasi', 6: 'Standard', 7: 'Kemitraan', 8: 'Kapasitas & Pendidikan', 9: 'Komunikasi & Keterlibatan' };

    // Initialize Chart.js radar
    var radarCanvas = document.getElementById('spRadarChartScore');
    var radarChart = null;
    var chartTitle = 'Nama Responden: ' + @json($form->nama_petugas);

    if (radarCanvas) {
        var radarCtx = radarCanvas.getContext('2d');
        radarChart = new Chart(radarCtx, {
            type: 'radar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Nilai',
                    data: [],
                    fill: true,
                    backgroundColor: 'rgba(30, 136, 229, 0.20)',
                    borderColor: '#1E88E5',
                    pointBackgroundColor: '#1E88E5',
                    pointBorderColor: '#FFFFFF',
                    pointHoverBackgroundColor: '#FFFFFF',
                    pointHoverBorderColor: '#1E88E5',
                    borderWidth: 2
                }]
            },
            options: {
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
            }
        });

        // Export handlers
        var exportPngBtn = document.getElementById('exportRadarPngScore');
        var exportJpgBtn = document.getElementById('exportRadarJpgScore');
        var download = function (dataUrl, filename) {
            var a = document.createElement('a');
            a.href = dataUrl;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        };
        if (exportPngBtn) {
            exportPngBtn.addEventListener('click', function () {
                var url = radarCanvas.toDataURL('image/png', 1.0);
                download(url, 'grafik-radar-score.png');
            });
        }
        if (exportJpgBtn) {
            exportJpgBtn.addEventListener('click', function () {
                var bgCanvas = document.createElement('canvas');
                bgCanvas.width = radarCanvas.width;
                bgCanvas.height = radarCanvas.height;
                var bgCtx = bgCanvas.getContext('2d');
                bgCtx.fillStyle = '#FFFFFF';
                bgCtx.fillRect(0, 0, bgCanvas.width, bgCanvas.height);
                bgCtx.drawImage(radarCanvas, 0, 0);
                var url = bgCanvas.toDataURL('image/jpeg', 0.95);
                download(url, 'grafik-radar-score.jpg');
            });
        }
    }

    function parseCodeFromName(name) {
        var m = name.match(/^answers\[(.+?)\]$/);
        return m ? m[1] : null;
    }

    function toNumberOrNull(v) {
        if (v === null || v === undefined) return null;
        var s = String(v).trim();
        if (s === '') return null;
        var num = Number(s);
        if (!isFinite(num)) return null;
        if (num < 0 || num > 100) return null;
        return num;
    }

    function recalcSummary() {
        var spTotals = {};
        answersNodeList.forEach(function (inp) {
            var code = parseCodeFromName(inp.name);
            if (!code) return;
            var spStr = String(code).split('.')[0];
            var sp = parseInt(spStr, 10);
            if (!isFinite(sp)) return;
            var num = toNumberOrNull(inp.value);
            if (num === null) return;
            if (!spTotals[sp]) spTotals[sp] = { sum: 0, count: 0, items: [] };
            spTotals[sp].sum += num;
            spTotals[sp].count += 1;
            spTotals[sp].items.push({ code: code, value: num });
        });

        var sps = Object.keys(spTotals).map(function (k) { return parseInt(k, 10); }).sort(function (a, b) { return a - b; });
        var html = '';
        var overallSumAvg = 0;
        var overallCountSp = 0;

        if (sps.length === 0) {
            html += '<tr><td colspan="4" class="text-center">Belum ada jawaban soal yang dapat dihitung.</td></tr>';
        } else {
            sps.forEach(function (sp) {
                var tot = spTotals[sp];
                var avg = tot.count ? (tot.sum / tot.count) : null;
                if (avg !== null) { overallSumAvg += avg; overallCountSp += 1; }
                var label = spLabels[sp] ? ' - ' + spLabels[sp] : '';
                var detailsId = 'sp-details-' + sp;
                html += '<tr>' +
                    '<td>SP ' + sp + label + '</td>' +
                    '<td>' + tot.count + '</td>' +
                    '<td><span class="fw-semibold">total = ' + (tot.sum + 0) + '</span>' +
                    ' <a href="#' + detailsId + '" class="ms-2" data-bs-toggle="collapse" data-bs-target="#' + detailsId + '" aria-expanded="false" aria-controls="' + detailsId + '" onclick="event.preventDefault();"><i class="mdi mdi-eye-outline"></i></a>' +
                    '</td>' +
                    '<td>' + (avg === null ? '-' : Math.round(avg)) + '</td>' +
                '</tr>';
                html += '<tr class="collapse" id="' + detailsId + '"><td colspan="4">';
                tot.items.forEach(function (it) {
                    html += '<div>soal ' + it.code + ' = ' + (it.value + 0) + '</div>';
                });
                html += '<div class="fw-semibold mt-1">total = ' + (tot.sum + 0) + '</div>';
                html += '</td></tr>';
            });
            if (overallCountSp) {
                var overall = Math.round(overallSumAvg / overallCountSp);
                html += '<tr><td colspan="3" class="text-end fw-bold">Total Penilaian Keseluruhan</td><td class="fw-bold">' + overall + '</td></tr>';
                if (totalScoreInput) totalScoreInput.value = String(overall);
            } else {
                if (totalScoreInput) totalScoreInput.value = '';
            }
        }

        if (summaryBody) summaryBody.innerHTML = html;

        // Update radar chart
        if (radarChart) {
            var chartLabels = [];
            var chartData = [];
            sps.forEach(function (sp) {
                var tot = spTotals[sp];
                var avg = tot.count ? (tot.sum / tot.count) : 0;
                var label = 'SP ' + sp + (spLabels[sp] ? ' - ' + spLabels[sp] : '');
                chartLabels.push(label);
                chartData.push(Math.round(avg));
            });
            radarChart.data.labels = chartLabels;
            radarChart.data.datasets[0].data = chartData;
            radarChart.update();
        }
    }

    recalcSummary();
    answersNodeList.forEach(function (inp) {
        inp.addEventListener('input', recalcSummary);
        inp.addEventListener('change', recalcSummary);
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Bootstrap Popover init for question detail
    var popoverTriggerList = [].slice.call(document.querySelectorAll('.js-question-popover'));
    var questionTextByCode = @json($questionTextByCode ?? []);
    popoverTriggerList.forEach(function (el) {
        var code = el.getAttribute('data-code');
        var content = questionTextByCode[code];
        if (!content) {
            // Fallback: try to find matching entry like "1.1 ..." from server-side map
            content = 'Detail soal tidak ditemukan.';
        }
        new bootstrap.Popover(el, { content: content, title: 'Soal ' + code, trigger: 'focus', placement: 'top', html: true });
    });
});
</script>
@endpush
@endsection
