<?php

namespace App\Imports;

use App\Trans;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Log;
class TransImport implements ToCollection
{
    public $say = 0;
    public function collection(Collection $rows)
    {   
        global $say;
        /*
        ["AUCTION NAME","US LOGI YOKOHAMA","ATJ KOBE","ATJ OSAKA","ATJ KISARAZU","ATJ TOKAI","HBS JAPAN IBARAKI"]  

        $dizi['MIRAi SAITAMA'] = array(
            "US LOGI YOKOHAMA" => 17.710, 
            "US LOGI YOKOHAMA" => 17.710, 
            "US LOGI YOKOHAMA" => 17.710, 
            "US LOGI YOKOHAMA" => 17.710, 
        );
        */
        $dizi = array();
        $col = $rows[0];
        $dizi['col'] = $col;
      //  print_r($col[0]);
        $k = 0;
        foreach ($rows as $row) 
        {  
            if($k>1) {
                
                for($k=1;$k<=6;$k++) {
                    
                    @$dizi['row'][$row[0]][$col[$k]] = @$row[$k];
                    
                }
            }
            $k++;
        }
        $say++;
      //  echo $say;
        if($say==1) { //ilk sayfadaysa
            $dizi = json_encode_tr($dizi);
            ekle([
                "type" => "transportation-cfg",
                "json" => $dizi
            ]);
        }
        
        
    }
}
