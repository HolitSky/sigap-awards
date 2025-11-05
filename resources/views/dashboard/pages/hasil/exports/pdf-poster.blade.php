<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
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
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #4a5568;
            color: white;
            font-weight: bold;
            font-size: 11px;
            text-align: center;
        }
        td {
            font-size: 10px;
        }
        .text-center {
            text-align: center;
        }
        .rank-cell {
            font-weight: bold;
            font-size: 12px;
        }
        .rank-1 { color: #FFD700; }
        .rank-2 { color: #C0C0C0; }
        .rank-3 { color: #CD7F32; }
        .nilai-final {
            font-weight: bold;
            color: #4299e1;
            font-size: 12px;
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
            background-color: #e0f2fe;
            border: 1px solid #0ea5e9;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            font-size: 10px;
        }
        .badge-bpkh {
            background-color: #3b82f6;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-produsen {
            background-color: #10b981;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p class="subtitle">Poster/Exhibition Category - SIGAP Award 2025</p>
        <p style="font-size: 9px; color: #666;">Dicetak pada: {{ date('d/m/Y H:i') }}</p>
    </div>

    <div class="info-box">
        <strong>Informasi:</strong> Tabel ini menampilkan hasil penilaian Poster/Exhibition dari BPKH dan Produsen yang sudah dinilai oleh juri.<br>
        Nilai yang ditampilkan adalah khusus kategori exhibition / poster.
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
                        @elseif($key === 'Kategori')
                            <td class="text-center">
                                @if($cell === 'BPKH')
                                    <span class="badge-bpkh">BPKH</span>
                                @else
                                    <span class="badge-produsen">Produsen</span>
                                @endif
                            </td>
                        @elseif($key === 'Nilai Exhibition')
                            <td class="text-center nilai-final">{{ $cell }}</td>
                        @elseif($key === 'Juri Penilai Exhibition')
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
        <p><strong>SIGAP Award 2025</strong> - Sistem Informasi Penilaian Poster/Exhibition</p>
        <p>Total Peserta: {{ count($data) }} | Dokumen ini bersifat rahasia</p>
    </div>
</body>
</html>
