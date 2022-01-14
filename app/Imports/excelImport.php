<?php

namespace App\Imports;

use App\Trans;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Log;
class excelImport implements ToCollection
{
    public $say = 0;
    public function collection(Collection $rows)
    {   
       print2($rows);
         echo "ok";
        
    }
}
