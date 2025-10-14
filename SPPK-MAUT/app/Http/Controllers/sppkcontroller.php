<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class SPPKController extends Controller
{
    public function index()
    {
        return view('viewcoba'); // halaman input kriteria & file
    }

    public function hitung(Request $request)
    {
        $request->validate([
            'bobot' => 'required|array',
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $bobot = $request->bobot;

        // Pastikan total bobot = 1 (jika user isi dalam bentuk persen)
        $totalBobot = array_sum($bobot);
        $bobot = array_map(fn($b) => $b / $totalBobot, $bobot);

        // === BACA FILE EXCEL ===
        $raw = Excel::toArray([], $request->file('file'))[0];
        $header = array_map('trim', $raw[0]);
        $rows = array_slice($raw, 1);

        // deteksi kolom nama (non-numerik pertama)
        $firstRow = $rows[0];
        $indexNama = null;
        foreach ($firstRow as $i => $val) {
            if (!is_numeric($val)) {
                $indexNama = $i;
                break;
            }
        }

        if ($indexNama === null) {
            return back()->with('error', 'Tidak ditemukan kolom nama alternatif!');
        }

        // ubah jadi array data alternatif
        $data = [];
        foreach ($rows as $row) {
            $nama = $row[$indexNama];
            $kriteria = [];

            foreach ($row as $i => $val) {
                if ($i != $indexNama && is_numeric($val)) {
                    $kriteria[] = (float)$val;
                }
            }

            $data[] = [
                'nama' => $nama,
                'kriteria' => $kriteria
            ];
        }

        // === NORMALISASI (MIN-MAX) ===
$jumlahKriteria = count($data[0]['kriteria']);

// Inisialisasi nilai min dan max untuk tiap kriteria
$nilaiMin = array_fill(0, $jumlahKriteria, INF);
$nilaiMax = array_fill(0, $jumlahKriteria, -INF);

// Cari nilai min dan max dari setiap kriteria
foreach ($data as $d) {
    foreach ($d['kriteria'] as $i => $nilai) {
        if ($nilai < $nilaiMin[$i]) {
            $nilaiMin[$i] = $nilai; // xi minimum
        }
        if ($nilai > $nilaiMax[$i]) {
            $nilaiMax[$i] = $nilai; // xi maksimum
        }
    }
}

// Normalisasi tiap alternatif
$normalisasi = [];
foreach ($data as $d) {
    $n = [];
    foreach ($d['kriteria'] as $i => $nilai) {
        $min = $nilaiMin[$i];
        $max = $nilaiMax[$i];

        if ($max == $min) {
            // Semua nilai sama → dianggap 1 (netral)
            $n[] = 1;
        } else {
            // Normalisasi min-max
            $n[] = ($nilai - $min) / ($max - $min);
        }
    }
    $normalisasi[] = [
        'nama' => $d['nama'],
        'normalisasi' => $n
    ];
}

// === EVALUASI (PERHITUNGAN MAUT) ===
$hasil = [];
foreach ($normalisasi as $d) {
    $utility = 0;
    foreach ($d['normalisasi'] as $i => $val) {
        $utility += $val * $bobot[$i]; // perkalian normalisasi × bobot
    }
    $hasil[] = [
        'nama' => $d['nama'],
        'nilai' => round($utility, 4)
    ];
}

// Urutkan hasil berdasarkan nilai tertinggi (peringkat)
usort($hasil, fn($a, $b) => $b['nilai'] <=> $a['nilai']);

        // === EVALUASI MAUT ===
        $hasil = [];
        foreach ($normalisasi as $d) {
            $utility = 0;
            foreach ($d['normalisasi'] as $i => $val) {
                $utility += $val * $bobot[$i];
            }
            $hasil[] = [
                'nama' => $d['nama'],
                'nilai' => round($utility, 4)
            ];
        }

        // Urutkan berdasarkan nilai tertinggi
        usort($hasil, fn($a, $b) => $b['nilai'] <=> $a['nilai']);

        // === CETAK PDF ===
        $pdf = Pdf::loadView('hasilpdf', [
            'hasil' => $hasil,
            'bobot' => $bobot,
            'normalisasi' => $normalisasi,
        ]);

        $filename = 'hasil_sppk_' . time() . '.pdf';
        $pdf->save(storage_path('app/public/' . $filename));

        return view('hasil_view', compact('hasil', 'filename'));
    }

    public function download($filename)
    {
        $path = storage_path('app/public/' . $filename);
        if (file_exists($path)) {
            return response()->download($path);
        }
        return back()->with('error', 'File tidak ditemukan.');
    }
}