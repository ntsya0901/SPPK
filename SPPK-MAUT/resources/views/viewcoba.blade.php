<!DOCTYPE html>
<html>

<head>
    <title>SPPK MAUT</title>
</head>

<body>
    <h2>SPPK Metode MAUT</h2>

    <form action="{{ route('sppk.hitung') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <p>Masukkan Bobot Kriteria:</p>

        <div id="bobot-container">
            <input type="number" step="0.01" name="bobot[]" placeholder="Bobot K1" required>
            <input type="number" step="0.01" name="bobot[]" placeholder="Bobot K2" required>
            <input type="number" step="0.01" name="bobot[]" placeholder="Bobot K3" required>
            <input type="number" step="0.01" name="bobot[]" placeholder="Bobot K4" required>
            <input type="number" step="0.01" name="bobot[]" placeholder="Bobot K5" required>
        </div>

        <p>Upload File Excel / CSV:</p>
        <input type="file" name="file" accept=".csv,.xls,.xlsx" required>

        <br><br>
        <button type="submit">Hitung</button>
    </form>
</body>

</html>