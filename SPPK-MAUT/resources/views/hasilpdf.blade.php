<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan SPPK MAUT</title>
    <style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 5px;
        text-align: center;
    }

    /* Gaya tambahan untuk judul bagian */
    h4 {
        margin-top: 20px;
        margin-bottom: 5px;
    }
    </style>
</head>

<body>
    <h3 align="center">Laporan Hasil SPPK Metode MAUT</h3>

    <h4>Bobot Kriteria:</h4>
    <p>{{ implode(' | ', $bobot) }}</p>

    <h4>Hasil Akhir Evaluasi:</h4>
    <table>
        <tr>
            <th>Peringkat</th>
            <th>Alternatif</th>
            <th>Nilai Akhir</th>
        </tr>
        @foreach ($hasil as $i => $h)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $h['nama'] }}</td>
            <td>{{ $h['nilai'] }}</td>
        </tr>
        @endforeach
    </table>
</body>

</html>