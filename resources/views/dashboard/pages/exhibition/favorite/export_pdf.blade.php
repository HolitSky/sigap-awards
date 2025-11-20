<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 20px;
        }
        h1 {
            text-align: center;
            font-size: 16px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">No</th>
                <th style="width: 15%;">Respondent ID</th>
                <th style="width: 30%;">Nama</th>
                <th class="text-center" style="width: 12%;">Kategori</th>
                <th style="width: 18%;">Petugas</th>
                <th class="text-center" style="width: 10%;">Jumlah Vote</th>
                <th style="width: 10%;">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $row['Respondent ID'] }}</td>
                <td>{{ $row['Nama'] }}</td>
                <td class="text-center">{{ $row['Kategori'] }}</td>
                <td>{{ $row['Petugas'] }}</td>
                <td class="text-center">{{ $row['Jumlah Vote'] }}</td>
                <td>{{ $row['Catatan'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top: 20px; font-size: 9px;">
        Dicetak pada: {{ date('d/m/Y H:i') }}
    </p>
</body>
</html>
