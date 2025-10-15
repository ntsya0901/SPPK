<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class SPPKController extends Controller
{
    public function index()
    {
        // Halaman input kriteria, bobot, tipe, dan file
        return view('viewcoba');
    }

    public function hitung(Request $request)
    {
        $request->validate([
            'bobot' => 'required|array',
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        // Ambil input bobot dan tipe kriteria
        $bobot = $request->bobot;
        $tipeKriteria = $request->input('tipe_kriteria'); 
        // contoh: ['benefit', 'cost', 'benefit', ...]

        // Pastikan total bobot = 1
        $totalBobot = array_sum($bobot);
        $bobot = array_map(fn($b) => $b / $totalBobot, $bobot);

        // === BACA FILE EXCEL ===
        $raw = Excel::toArray([], $request->file('file'))[0];
        $header = array_map('trim', $raw[0]);
        $rows = array_slice($raw, 1);

        // Deteksi kolom nama alternatif (non-numerik pertama)
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

        // Ubah data ke format array alternatif
        $data = [];
        foreach ($rows as $row) {
            $nama = $row[$indexNama];
            $kriteria = [];

            foreach ($row as $i => $val) {
                if ($i != $indexNama && is_numeric($val)) {
                    $kriteria[] = (float) $val;
                }
            }

            $data[] = [
                'nama' => $nama,
                'kriteria' => $kriteria
            ];
        }

        // === NORMALISASI (MIN-MAX, dengan BENEFIT & COST) ===
        $jumlahKriteria = count($data[0]['kriteria']);
        $nilaiMin = array_fill(0, $jumlahKriteria, INF);
        $nilaiMax = array_fill(0, $jumlahKriteria, -INF);

        // Cari nilai min dan max tiap kriteria
        foreach ($data as $d) {
            foreach ($d['kriteria'] as $i => $nilai) {
                if ($nilai < $nilaiMin[$i]) $nilaiMin[$i] = $nilai;
                if ($nilai > $nilaiMax[$i]) $nilaiMax[$i] = $nilai;
            }
        }

        // Proses normalisasi tiap alternatif
        $normalisasi = [];
        foreach ($data as $d) {
            $n = [];
            foreach ($d['kriteria'] as $i => $nilai) {
                $min = $nilaiMin[$i];
                $max = $nilaiMax[$i];
                $tipe = $tipeKriteria[$i] ?? 'benefit'; // default benefit

                if ($max == $min) {
                    $n[] = 1;
                } else {
                    if ($tipe === 'benefit') {
                        $n[] = ($nilai - $min) / ($max - $min);
                    } else { // cost
                        $n[] = ($max - $nilai) / ($max - $min);
                    }
                }
            }
            $normalisasi[] = [
                'nama' => $d['nama'],
                'normalisasi' => $n
            ];
        }

        // === PERHITUNGAN NILAI AKHIR (EVALUASI MAUT) ===
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
            'nilaiMin' => $nilaiMin,
            'nilaiMax' => $nilaiMax,
            'tipeKriteria' => $tipeKriteria
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