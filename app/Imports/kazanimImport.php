<?php

namespace App\Imports;

use App\Trans;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Log;
class kazanimImport implements ToCollection
{
    public $say = 0;
    public function collection(Collection $rows)
    {   
     //   $rows = (Array) $rows;
    //    unset($rows[0]);
    $kazanim = array();
        $k = 0 ;
        foreach($rows AS $r) {
            if($k>0) {
              
           
            $d = $r;
          //  $d = $d['items'];
          //  print2($r[0]); exit();
            $slug = str_slug(trim($d[0]));
            $soru =  trim($d[1]);
            $cevap = $slug ."_cevap_".$soru;
            $kazanima = $slug ."_kazanim_".$soru;
            $tak = $slug ."_tak_".$soru;
            $b_soru_no = $slug ."_b_soru_no_".$soru;
            $c_soru_no = $slug ."_c_soru_no_".$soru;
            $d_soru_no = $slug ."_d_soru_no_".$soru;
    
           
        // print2($d[4]); exit();
            $kazanim[$cevap] = str_split(trim($d[3]));
            $kazanim[$kazanima] = $d[4];
            $kazanim[$tak] = $d[5];
            $kazanim[$b_soru_no] = $d[2];
            $kazanim[$c_soru_no] = $d[6];
            $kazanim[$d_soru_no] = $d[7];
        }
            $k++;
        }
        db("sinavlar")
        ->where("id",get("kazanimlar"))
        ->update([
            "kazanimlar" => json_encode_tr($kazanim)
        ]);
   
        
    }
}
