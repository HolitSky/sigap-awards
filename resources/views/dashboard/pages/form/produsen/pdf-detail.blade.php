<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Form Produsen DG Detail - SIGAP Award 2025</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 8px;
            line-height: 1.3;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 2px solid #333;
        }
        .header h1 {
            font-size: 14px;
            margin-bottom: 3px;
            color: #333;
        }
        .header p {
            font-size: 9px;
            color: #666;
        }
        
        .participant-section {
            page-break-inside: avoid;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            padding: 10px;
            background: #fff;
        }
        
        .participant-header {
            background: #f0f0f0;
            color: #000000;
            padding: 8px;
            margin: -10px -10px 10px -10px;
            font-size: 10px;
            font-weight: bold;
            border-bottom: 2px solid #28a745;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            width: 25%;
            padding: 4px 8px;
            font-weight: bold;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            vertical-align: top;
        }
        
        .info-value {
            display: table-cell;
            width: 75%;
            padding: 4px 8px;
            border: 1px solid #dee2e6;
            word-wrap: break-word;
            vertical-align: top;
        }
        
        .metadata-section {
            margin-top: 10px;
            padding: 8px;
            background: #f8f9fa;
            border-left: 3px solid #28a745;
        }
        
        .metadata-title {
            font-weight: bold;
            font-size: 9px;
            margin-bottom: 5px;
            color: #28a745;
        }
        
        .metadata-item {
            margin-bottom: 6px;
            padding: 5px;
            background: white;
            border: 1px solid #dee2e6;
        }
        
        .metadata-label {
            font-weight: bold;
            color: #495057;
            display: block;
            margin-bottom: 2px;
        }
        
        .metadata-value {
            color: #212529;
            word-wrap: break-word;
            white-space: pre-wrap;
        }
        
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
            display: inline-block;
        }
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }
        .badge-warning {
            background-color: #ffc107;
            color: #333;
        }
        
        .footer {
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 7px;
            color: #666;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Data Form Penilaian Produsen DG (Detail Lengkap)</h1>
        <p>SIGAP Award 2025 - Produsen Data Geospasial Kehutanan</p>
        <p>Tanggal Export: {{ date('d F Y, H:i') }} WIB | Total Data: {{ $forms->count() }}</p>
    </div>

    @forelse($forms as $index => $form)
    @if($index > 0 && $index % 2 == 0)
        <div class="page-break"></div>
    @endif
    <div class="participant-section">
        <div class="participant-header">
            {{ $index + 1 }}. {{ $form->nama_instansi ?? 'N/A' }}
        </div>
        
        <!-- Basic Information -->
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nama Instansi</div>
                <div class="info-value">{{ $form->nama_instansi ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Nama Petugas</div>
                <div class="info-value">{{ $form->nama_petugas ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Nominasi</div>
                <div class="info-value">
                    @if($form->nominasi)
                        <span class="badge badge-success">Masuk Nominasi</span>
                    @else
                        <span class="badge badge-secondary">Tidak Masuk</span>
                    @endif
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Telepon / WhatsApp</div>
                <div class="info-value">{{ $form->phone ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $form->email ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Website</div>
                <div class="info-value">{{ $form->website ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status Penilaian</div>
                <div class="info-value">
                    @php
                        $badgeClass = match($form->status_nilai) {
                            'scored' => 'badge-success',
                            'in_review' => 'badge-warning',
                            default => 'badge-secondary',
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $form->status_label ?? 'Pending' }}</span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Nilai Final</div>
                <div class="info-value"><strong>{{ $form->total_score ?? 'Belum Ada' }}</strong></div>
            </div>
            <div class="info-row">
                <div class="info-label">Nilai Bobot (45%)</div>
                <div class="info-value"><strong>{{ $form->nilai_bobot_total !== null ? number_format($form->nilai_bobot_total, 2) : 'Belum Ada' }}</strong></div>
            </div>
            <div class="info-row">
                <div class="info-label">Kategori Penilaian</div>
                <div class="info-value">{{ $form->kategori_penilaian ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Catatan</div>
                <div class="info-value">{{ $form->notes ?? '-' }}</div>
            </div>
        </div>
        
        <!-- Metadata Section -->
        @if($form->meta)
        <div class="metadata-section">
            <div class="metadata-title">[DETAIL] HASIL JAWABAN DETAIL</div>
            
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
            
            @if(count($entries) > 0)
                @foreach($entries as [$key, $value])
                    @php
                        $isQuestion = preg_match('/^\s*(\d+)\s*\./', (string) $key, $m);
                        $isAnswer = preg_match('/^\s*soal\s+([0-9]+(?:\.[0-9]+)*)/i', (string) $key, $am);
                        $isKonsep = preg_match('/^\s*Konsep\s+Paparan/i', (string) $key);
                        $isAttachment = !$isQuestion && !$isAnswer && preg_match('/^\s*Lampiran/i', (string) $key);
                        
                        $bgColor = '#ffffff';
                        if ($isKonsep) {
                            $bgColor = '#E03737';
                        } elseif ($isQuestion) {
                            $bgColor = '#E2FFF4';
                        } elseif ($isAnswer) {
                            $bgColor = '#F8F6FF';
                        }
                    @endphp
                    <div class="metadata-item" style="background: {{ $bgColor }}; {{ $isKonsep ? 'color: white;' : '' }}">
                        <span class="metadata-label" style="{{ $isKonsep ? 'color: white;' : '' }}">
                            @if($isQuestion)
                                SP {{ $m[1] }} : {{ $key }}
                            @elseif($isAnswer)
                                Jawaban soal {{ $am[1] }}
                            @elseif($isAttachment)
                                [Lampiran] {{ $key }}
                            @else
                                {{ $key }}
                            @endif
                        </span>
                        <span class="metadata-value" style="{{ $isKonsep ? 'color: white;' : '' }}">
                            @php
                                $isArray = is_array($value);
                                $raw = $isArray ? json_encode($value) : (string) $value;
                                $display = $raw === '' ? '-' : $raw;
                                // Extract URLs
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
                                    <div><a href="{{ $u }}" target="_blank" style="{{ $isKonsep ? 'color: white;' : 'color: #007bff;' }}">{{ $u }}</a></div>
                                @endforeach
                            @elseif(count($links) === 1)
                                <a href="{{ $links[0] }}" target="_blank" style="{{ $isKonsep ? 'color: white;' : 'color: #007bff;' }}">{{ $links[0] }}</a>
                            @else
                                {{ $display }}
                            @endif
                        </span>
                    </div>
                @endforeach
            @else
                <div class="metadata-item">
                    <span class="metadata-value">Tidak ada data jawaban tersedia</span>
                </div>
            @endif
        </div>
        @endif
        
        <!-- Additional Info -->
        <div style="margin-top: 8px; font-size: 7px; color: #6c757d;">
            <strong>Tanggal Submit:</strong> {{ $form->created_at ? $form->created_at->format('d/m/Y H:i') : '-' }} | 
            <strong>Respondent ID:</strong> {{ $form->respondent_id ?? '-' }}
        </div>
    </div>
    
    @empty
    <div style="text-align: center; padding: 20px; color: #999;">
        Tidak ada data tersedia
    </div>
    @endforelse

    <div class="footer">
        <p>Generated by SIGAP Award System | © 2025 Kementerian Lingkungan Hidup dan Kehutanan</p>
    </div>
</body>
</html>
