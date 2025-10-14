<!DOCTYPE html>
<html>

<head>
    <title>Hasil SPPK MAUT</title>
    <style>
        /* Variabel Warna Pink */
        :root {
            --primary-pink: #FF69B4; /* Hot Pink */
            --light-pink: #FFC0CB; /* Light Pink */
            --pale-pink: #FFE4E1; /* Misty Rose, untuk latar belakang */
            --text-color: #333;
            --container-bg: white;
            --shadow-color: rgba(255, 105, 180, 0.4); /* Bayangan pink */
        }

        /* Gaya Dasar dan Pemusatan */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--pale-pink);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Rata atas, tapi konten utama tetap di tengah */
            min-height: 100vh;
            margin: 0;
            padding: 40px 20px;
        }

        /* Kontainer Utama (Card) */
        .main-container {
            background-color: var(--container-bg);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px var(--shadow-color);
            width: 100%;
            max-width: 650px;
            text-align: center; /* Teks di dalam container rata tengah */
        }

        /* Judul */
        h2 {
            color: var(--primary-pink);
            text-align: center;
            border-bottom: 2px solid var(--light-pink);
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        /* Gaya Tabel */
        table {
            width: 80%; /* Lebar tabel */
            border-collapse: collapse;
            margin: 0 auto 30px auto; /* Pusatkan tabel dan berikan margin bawah */
            border-radius: 10px;
            overflow: hidden; /* Penting untuk sudut membulat */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        thead tr {
            background-color: var(--primary-pink);
            color: white;
        }

        th, td {
            padding: 12px 15px;
            text-align: center;
            border: 1px solid var(--light-pink);
        }

        tbody tr:nth-child(even) {
            background-color: var(--pale-pink); /* Warna selang-seling */
        }

        tbody tr:hover {
            background-color: var(--light-pink);
            color: var(--text-color);
            cursor: default;
        }
        
        /* Tombol Download */
        .download-btn {
            display: inline-block;
            padding: 12px 30px;
            border: 2px solid var(--primary-pink);
            background-color: var(--primary-pink);
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            font-size: 16px;
            transition: background-color 0.3s, color 0.3s;
        }
        .download-btn:hover {
            background-color: white;
            color: var(--primary-pink);
        }
    </style>
</head>

<body>
    <div class="main-container">
        <h2>Hasil Perhitungan MAUT</h2>

        <table id="hasil-table">
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
                    <td>{{ number_format($h['nilai'], 4) }}</td> </tr>
                @endforeach
            </tbody>
        </table>

        <br>
        <a href="{{ url('sppk/download/' . $filename) }}" class="download-btn">Download PDF Hasil</a>
    </div>
</body>

</html>