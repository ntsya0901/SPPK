<!DOCTYPE html>
<html>

<head>
    <title>Hasil SPPK MAUT</title>
</head>

<body>
    <h2>Hasil Perhitungan MAUT</h2>

    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>Peringkat</th>
                <th>Alternatif</th>
                <th>Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($hasil as $i => $h)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $h['nama'] }}</td>
                <td>{{ $h['nilai'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <br>
    <a href="{{ url('sppk/download/' . $filename) }}">Download PDF</a>
</body>

</html>