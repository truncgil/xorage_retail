<?php

namespace App\Imports;

use App\Trans;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Log;
class cnfImport2 implements ToCollection
{
    public $say = 0;
    public function collection(Collection $rows)
    {   
        global $say;
       
        $dizi = array();
        $col = $rows[0];
      //  print_R($rows);
        $dizi['col'] = $col;
        print_r($col[0]);
        $k = 0;
        foreach ($rows as $row) 
        {  
            if($k!=0) {
                
                for($k=1;$k<=3;$k++) {
                    
                    @$dizi['row'][$row[0]][$row[1]][trim($col[$k])] = @$row[$k];
                    
                }
            }
            $k++;
        }
        $say++;
      //  echo $say;
        if($say==1) { //ilk sayfadaysa
            $dizi = json_encode_tr($dizi);
            ekle([
                "type" => "cnf2-cfg",
                "json" => $dizi
            ]);
        }
        
        
    }
}
