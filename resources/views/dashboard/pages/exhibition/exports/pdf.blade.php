<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        h1 {
            font-size: 18px;
            margin-bottom: 10px;
            text-align: center;
        }
        h2 {
            font-size: 14px;
            margin-top: 15px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-row {
            margin-bottom: 5px;
        }
        .label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Dicetak pada: {{ date('d/m/Y H:i') }}</p>
    </div>

    <div class="info-section">
        <h2>Informasi Peserta</h2>
        @if(isset($form->nama_bpkh))
            <div class="info-row">
                <span class="label">Nama BPKH:</span>
                <span>{{ $form->nama_bpkh }}</span>
            </div>
            <div class="info-row">
                <span class="label">Petugas:</span>
                <span>{{ $form->petugas_bpkh }}</span>
            </div>
        @else
            <div class="info-row">
                <span class="label">Nama Instansi:</span>
                <span>{{ $form->nama_instansi }}</span>
            </div>
            <div class="info-row">
                <span class="label">Penanggung Jawab:</span>
                <span>{{ $form->penanggung_jawab }}</span>
            </div>
        @endif
        <div class="info-row">
            <span class="label">Nilai Final:</span>
            <span>{{ number_format($form->nilai_final ?? 0, 2) }}</span>
        </div>
        <div class="info-row">
            <span class="label">Bobot Exhibition:</span>
            <span>{{ $form->bobot_exhibition }}%</span>
        </div>
        <div class="info-row">
            <span class="label">Nilai Final Dengan Bobot:</span>
            <span>{{ number_format($form->nilai_final_dengan_bobot ?? 0, 2) }}</span>
        </div>
        <div class="info-row">
            <span class="label">Kategori:</span>
            <span>{{ $form->kategori_penilaian ?? '-' }}</span>
        </div>
    </div>

    <h2>Data Penilaian ({{ ucfirst($type) }})</h2>
    <table>
        <thead>
            <tr>
                @foreach(array_keys($data[0]) as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
                <tr>
                    @foreach($row as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>SIGAP Award 2025 - Sistem Informasi Penilaian Exhibition/Poster</p>
    </div>
</body>
</html>
