<!DOCTYPE html>
<html>

<head>
    <title>SPPK MAUT</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Variabel Warna Pink */
        :root {
            --primary-pink: #FF69B4; /* Hot Pink */
            --light-pink: #FFC0CB; /* Light Pink */
            --pale-pink: #FFE4E1; /* Misty Rose, untuk latar belakang */
            --text-color: #333;
            --container-bg: white;
            --shadow-color: rgba(255, 105, 180, 0.4); /* Bayangan pink */
            --delete-color: #dc3545; /* Merah untuk tombol hapus */
        }

        /* Gaya Dasar dan Pemusatan */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--pale-pink);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        /* Kontainer Utama (Card) */
        .main-container {
            background-color: var(--container-bg);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px var(--shadow-color);
            width: 100%;
            max-width: 900px;
        }

        /* Judul Rata Tengah */
        h2 {
            color: var(--primary-pink);
            text-align: center;
            margin-bottom: 5px;
            font-weight: 700;
        }

        h3 {
            color: var(--text-color);
            text-align: center;
            border-bottom: 2px solid var(--light-pink);
            padding-bottom: 10px;
            margin-bottom: 30px;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        /* Tata Letak Kriteria */
        .kriteria-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #fffafa;
            padding: 10px 15px;
            margin-bottom: 25px;
            border-radius: 8px;
            border: 1px solid var(--light-pink);
            transition: all 0.3s ease-in-out;
        }
        .kriteria-row:hover {
             box-shadow: 0 4px 10px rgba(255, 105, 180, 0.2);
        }
        
        /* Grup utama dalam baris (Kode + Nama + Bobot + Tipe) */
        .kriteria-group {
            display: flex;
            align-items: center;
            gap: 25px; 
        }

        .bobot-info {
            display: flex;
            align-items: center;
            gap: 15px; 
        }

        .label-bobot {
            width: 70px;
            font-weight: 600;
            color: var(--primary-pink);
        }

        /* Input Styles */
        .input-kode, .input-bobot, .input-nama {
            padding: 10px;
            border: 1px solid var(--light-pink);
            border-radius: 5px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: border-color 0.3s;
        }
        
        .input-kode {
            width: 50px;
            text-align: center;
        }
        .input-nama {
            width: 150px; 
        }
        .input-bobot {
            width: 80px;
        }

        .input-kode:focus, .input-bobot:focus, .input-nama:focus {
            border-color: var(--primary-pink);
            outline: none;
        }

        /* Radio Button Benefit/Cost */
        .tipe-kriteria {
            display: flex;
            gap: 20px;
            font-size: 14px;
        }

        /* Tombol Hapus Kriteria */
        .remove-btn {
            background: none;
            border: none;
            color: var(--delete-color);
            font-size: 18px;
            cursor: pointer;
            padding: 5px;
            opacity: 0.7;
            transition: color 0.3s, opacity 0.3s, transform 0.2s;
        }
        .remove-btn:hover {
            opacity: 1;
            color: var(--delete-color);
            transform: scale(1.1);
        }
        
        /* Grup Tombol Tambah dan Keterangan */
        .add-group-section {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            margin-top: 35px;
            margin-bottom: 45px;
        }
        
        .add-kriteria-note {
            border-right: 2px dashed var(--light-pink);
            padding-right: 15px;
            margin-right: 15px;
            font-size: 13px;
            line-height: 1.5;
            color: #666;
            text-align: right;
        }

        /* Tombol Tambah (+) */
        .add-button-placeholder {
            width: 40px;
            height: 40px;
            line-height: 38px;
            text-align: center;
            background-color: var(--primary-pink);
            color: white;
            border-radius: 50%;
            font-weight: bold;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 8px var(--shadow-color);
            transition: transform 0.2s, background-color 0.3s;
        }
        .add-button-placeholder:hover {
            transform: scale(1.1);
            background-color: #e55a9b;
        }

        /* Tombol Choose File */
        .choose-file-btn {
            padding: 12px 30px;
            border: 2px solid var(--light-pink);
            border-radius: 30px;
            background-color: var(--pale-pink);
            color: var(--text-color);
            cursor: pointer;
            display: block;
            width: fit-content;
            margin: 20px auto;
            transition: background-color 0.3s, border-color 0.3s;
        }

        /* Tombol Hitung */
        .hitung-btn {
            padding: 18px 50px;
            border: none;
            background-color: var(--primary-pink);
            color: white;
            border-radius: 35px;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
            display: block;
            margin: 30px auto 0;
            box-shadow: 0 6px 15px var(--shadow-color);
        }
    </style>
</head>

<body>
    <div class="main-container">
        <h2>SPPK Metode MAUT </h2>

        <form action="{{ route('sppk.hitung') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <h3>Masukkan Bobot Kriteria:</h3>

            <div id="bobot-container">
                <div class="kriteria-row" id="kriteria-row-1">
                    <div class="kriteria-group">
                        <div class="bobot-info">
                            <div class="label-bobot">Bobot 1</div>
                            <input type="text" name="kode_kriteria[]" class="input-kode" placeholder="Kode" required>
                            <input type="text" name="nama_kriteria[]" class="input-nama" placeholder="Nama Kriteria" required>
                            <input type="number" step="0.01" name="bobot[]" class="input-bobot" placeholder="Nilai Kriteria" required>
                        </div>
                        <div class="tipe-kriteria">
                            <input type="radio" id="benefit1" name="tipe_kriteria_1" value="benefit" required>
                            <label for="benefit1">Benefit</label>
                            <input type="radio" id="cost1" name="tipe_kriteria_1" value="cost">
                            <label for="cost1">Cost</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="add-group-section">
                <div class="add-kriteria-note">
                    Tekan tombol (+) untuk<br>
                    menambahkan kriteria baru.
                </div>
                <span class="add-button-placeholder" onclick="addKriteriaRow()">+</span>
            </div>

            <label for="file-upload" class="choose-file-btn">Choose file Excel / CSV 📎</label>
            <input type="file" name="file" id="file-upload" accept=".csv,.xls,.xlsx" style="display: none;" required>

            <button type="submit" class="hitung-btn">Hitung Hasil MAUT</button>
        </form>
    </div>

    <script>
        // Counter dimulai dari 1 karena Bobot 1 sudah ada secara default
        let kriteriaCount = 1;

        // Fungsi untuk menghapus baris kriteria
        function removeKriteriaRow(element) {
            const rowToRemove = element.closest('.kriteria-row');
            if (rowToRemove) {
                rowToRemove.remove();
            }
        }

        // Fungsi untuk menambahkan baris kriteria baru
        function addKriteriaRow() {
            kriteriaCount++;
            const container = document.getElementById('bobot-container');
            
            // Template HTML untuk satu baris kriteria baru
            const newRowHtml = `
                <div class="kriteria-row" id="kriteria-row-${kriteriaCount}">
                    <div class="kriteria-group">
                        <div class="bobot-info">
                            <div class="label-bobot">Bobot ${kriteriaCount}</div>
                            <input type="text" name="kode_kriteria[]" class="input-kode" placeholder="Kode${kriteriaCount}" required>
                            <input type="text" name="nama_kriteria[]" class="input-nama" placeholder="Nama Kriteria ${kriteriaCount}" required>
                            <input type="number" step="0.01" name="bobot[]" class="input-bobot" placeholder="Nilai Kriteria${kriteriaCount}" required>
                        </div>
                        <div class="tipe-kriteria">
                            <input type="radio" id="benefit${kriteriaCount}" name="tipe_kriteria_${kriteriaCount}" value="benefit" required>
                            <label for="benefit${kriteriaCount}">Benefit</label>
                            <input type="radio" id="cost${kriteriaCount}" name="tipe_kriteria_${kriteriaCount}" value="cost">
                            <label for="cost${kriteriaCount}">Cost</label>
                        </div>
                    </div>
                    <button type="button" class="remove-btn" onclick="removeKriteriaRow(this)">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', newRowHtml);
        }
    </script>
</body>

</html>