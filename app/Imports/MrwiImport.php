<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\Importable;

class MrwiImport implements ToArray
{
    use Importable;

    public function array(array $array)
    {
        return $array;
    }
}
