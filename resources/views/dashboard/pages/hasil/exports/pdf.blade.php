<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
        }
        h1 {
            font-size: 18px;
            margin-bottom: 10px;
            text-align: center;
            color: #333;
        }
        .subtitle {
            text-align: center;
            margin-bottom: 20px;
            font-size: 11px;
            color: #666;
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
            background-color: #4a5568;
            color: white;
            font-weight: bold;
            font-size: 10px;
            text-align: center;
        }
        td {
            font-size: 9px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .rank-cell {
            font-weight: bold;
            font-size: 11px;
        }
        .rank-1 { color: #FFD700; }
        .rank-2 { color: #C0C0C0; }
        .rank-3 { color: #CD7F32; }
        .nilai-final {
            font-weight: bold;
            color: #4299e1;
            font-size: 11px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .footer {
            margin-top: 30px;
            font-size: 9px;
            text-align: center;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .info-box {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p class="subtitle">Nominees Only - SIGAP Award 2025</p>
        <p style="font-size: 9px; color: #666;">Dicetak pada: {{ date('d/m/Y H:i') }}</p>
    </div>

    <div class="info-box">
        <strong>Formula Penilaian:</strong> Nilai Final = Form (45%) + Presentasi (35%) + Exhibition (20%)<br>
        <strong>Catatan:</strong> Kolom "Juri Penilai Presentasi" dan "Juri Penilai Exhibition" menunjukkan jumlah juri yang sudah menilai
    </div>

    <table>
        <thead>
            <tr>
                @foreach(array_keys($data[0]) as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
                <tr>
                    @foreach($row as $key => $cell)
                        @if($key === 'Rank')
                            <td class="text-center rank-cell {{ $index < 3 ? 'rank-' . ($index + 1) : '' }}">
                                {{ $cell }}
                            </td>
                        @elseif($key === 'Nilai Final')
                            <td class="text-center nilai-final">{{ $cell }}</td>
                        @elseif(in_array($key, ['Form (45%)', 'Presentasi (35%)', 'Exhibition (20%)', 'Juri Penilai Presentasi', 'Juri Penilai Exhibition']))
                            <td class="text-center">{{ $cell }}</td>
                        @else
                            <td>{{ $cell }}</td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p><strong>SIGAP Award 2025</strong> - Sistem Informasi Penilaian Final</p>
        <p>Total Nominees: {{ count($data) }} | Dokumen ini bersifat rahasia</p>
    </div>
</body>
</html>
