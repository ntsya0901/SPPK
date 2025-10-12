<?php

namespace App\Imports;

use App\Models\Data;
use Maatwebsite\Excel\Concerns\ToModel;

class DataImport implements ToModel
{
    public function model(array $row)
    {
        return new Data([
            'nama' => $row[0],
            'kriteria1' => $row[1],
            'kriteria2' => $row[2],
            'kriteria3' => $row[3],
        ]);
    }
}