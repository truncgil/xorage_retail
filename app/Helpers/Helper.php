<?php

ini_set('session.gc_probability', 1);
use App\Card;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Contents;
use App\Fields;
use App\User;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;



function branslar() {
    $brans = db("contents")->select("id","title","json")->where("type","Branşlar")->get();
    return $brans;
}
/*
where(function($query) use($deger,$alan){
            foreach($deger AS $a => $s) {
                $query->orWhere($alan,$s);
            }
            
        })
*/
function urun_ozellikleri($j) {
    $olmayan = explode(",","qty,id");
    foreach($j AS $alan => $deger) {
        if(!in_array($alan,$olmayan)) {
        $alan = str_replace("_"," ",$alan);
         ?>
         <small>
         <strong><?php echo $alan ?></strong> : <?php echo $deger ?>
         </small>
         <?php 
        }
    } 
}
function alan_text($title) {
    return str_replace("_"," ",$title);
}
function admin_delete($id) {
    ?>
    <?php if(u()->level=="Admin") {
         ?>
         <a href="?sil=<?php echo $id ?>"  teyit="Bu kaydı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz" class="btn btn-danger"><i class="fa fa-times"></i></a>
         <?php 
    } ?>
    <?php 
}
function siparis_durumlari() {
    
    return explode(",","Express,Express Hazırlanıyor,Hazırlanıyor,Gönderime Hazır,Gönderildi,İptal Edildi");
}

function stok_cikis_sayim($id="") {
    $siparisler = db("siparis_grubu")->select("id","durum")->get();
    $siparis_durum = [];
    foreach($siparisler AS $sip) {
        $siparis_durum[$sip->id] = $sip->durum;
    }
    $stok_cikisi = db("siparisler")->select("type","qty","kid");
    if($id!="") {
        if(!is_array($id)) $id = [$id];
        $stok_cikisi = $stok_cikisi->whereIn("type",$id);
    }
    $stok_cikisi = $stok_cikisi->get();
    $dizi = array();
    foreach($stok_cikisi AS $s) {
        if(!isset($dizi[$s->type])) $dizi[$s->type] = 0;
        $durum = "";
        if(isset($siparis_durum[$s->kid])) $durum = $siparis_durum[$s->kid]; 
        if($durum!="İptal Edildi") {
            $dizi[$s->type] += $s->qty;
        }
        
    }
    return $dizi;
}
function stok_giris_sayim($id="") {
    $stoklar = db("stoklar")->select("type","qty");
    if($id!="") {
        if(!is_array($id)) $id = [$id];
        $stoklar = $stoklar->whereIn("type",$id);
    }
    $dizi = array();
   
    $stoklar = $stoklar->get();
    
    foreach($stoklar AS $s) {
        if(!isset($dizi[$s->type])) $dizi[$s->type] = 0;
        $dizi[$s->type] += $s->qty;
    }
    return $dizi;
}
function urun_stok_durum($id) {
   
    $giris = stok_giris_sayim($id);
    $cikis = stok_cikis_sayim($id);
    $giris_sayi = 0;
    $cikis_sayi = 0;
    if(isset($giris[$id])) $giris_sayi = $giris[$id];
    if(isset($cikis[$id])) $cikis_sayi = $cikis[$id];
    return $giris_sayi - $cikis_sayi;
}
function col($size,$title="",$color="1") {
    $colors = colors();
   
     ?>
     <div class="<?php echo $size ?>">
        <div class="block block-themed">
            <?php if($title!="") {
                 ?>
                 <div class="block-header bg-<?php echo $colors[$color]; ?>"><?php echo $title ?></div>
                 <?php 
            } ?>
            
            <div class="block-content">
               
           
     <?php 
}
function _col() {
     ?>
      </div>
        </div>
    </div>
     <?php 
}
function colors() {
    $dizi = explode("\n","Primary
Primary Light
Primary Dark
Primary Darker
Success
Info
Warning
Danger
Gray
Gray Dark
Gray Darker
Black
Elegance
Elegance Light
Elegance Dark
Elegance Darker
Pulse
Pulse Light
Pulse Dark
Pulse Darker
Flat
Flat Light
Flat Dark
Flat Darker
Corporate
Corporate Light
Corporate Dark
Corporate Darker
Earth
Earth Light
Earth Dark
Earth Darker
Aqua
Cherry
Dusk
Emerald
Lake
Leaf
Sea
Sun");
    $dizi = array_map("str_slug",$dizi);
    return $dizi;
}
function kazanimlar() {
    $brans = branslar();
    $kazanimlar = array();
    //print2($brans); exit();
    foreach($brans AS $b) {
        $j = j($b->json);
        if(is_array($j)) {
           // print2($j); exit();
            $konular = explode("\n",$j['Konular']);
            $kazanim_liste = explode("\n",$j['Kazanımlar']);
            foreach($konular AS $konu) {
                $konu = trim($konu);
                if($konu!="") {
                    if(!isset($kazanimlar[$b->title][$konu])) $kazanimlar[$b->title][$konu] = [];
                    foreach($kazanim_liste AS $kazanim) {
                        if($kazanim!="") {
                            if(strpos($kazanim,$konu)!==false) {
                                $kazanim = str_replace($konu . " / ","",$kazanim);
                                array_push($kazanimlar[$b->title][$konu],$kazanim);
                            }
                        }
                        
                    }
                }
                
                
            }
        }
        
        
       // array_push($kazanimlar[$b->title][$j['Konular']],)
    }
    return $kazanimlar;
}
function kazanimlar2($brans="",$konu="") { //önceden girilmiş olan kazanimları listeler
    $sorgu = db("soru_bankasi");
    if($brans!="") {
        $brans = explode(",",$brans);
        $sorgu = $sorgu->where(function($query) use($brans) {
            foreach($brans AS $brans_item) {
                $query -> orWhere("brans",$brans_item);
            }
        });
    }
    if($konu!="") {
        $konu = explode(",",$konu);
        
        $sorgu = $sorgu->where(function($query) use($konu){
            foreach($konu AS $konu_item) {
                $query -> orWhere("konu",$konu_item);
            }
        });
    }
    $sorgu = $sorgu->groupBy("kazanim")->get("kazanim");

    return $sorgu;
}
function konular($brans="") { //önceden girilmiş olan kazanimları listeler
    $sorgu = db("soru_bankasi");
    if($brans!="") {
        $brans = explode(",",$brans);
        $sorgu = $sorgu->where(function($query) use($brans) {
            foreach($brans AS $brans_item) {
                $query -> orWhere("brans",$brans_item);
            }
        });
    }
    $sorgu = $sorgu->groupBy("konu")->get("konu");

    return $sorgu;
}
function bootstrap() {
     ?>
     <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
        <!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script>
$(function(){
$(".select2").select2();
});
</script>
     <?php 
}
function slug_to_title($slug) {
    $sorgu = db("contents")->where("slug",$slug)->first("title");
    if($sorgu) {
        return $sorgu->title;
    } else {
        return $slug;
    }
    
}
function ogrenci($hash) {
    $sorgu = db("ogrenciler")
        ->where("title",$hash)
        ->orWhere("tc_kimlik_no",$hash)
        ->orWhere("id",$hash)
        ->first();
    return $sorgu;
}
function file_to_analiz($dosya) {
    $dosya = upload2("$dosya","sonuclar");
                //echo $dosya;
                $dosya_icerik = file_get_contents($dosya);
                $sinav = db("sinavlar")->where("id",post("sinav"))->first();
                $optik = db("optik")->where("id",post("optik"))->first();
                $optik_json = j($optik->json);
                $sinav_json = j($sinav->json);
                $sinav_kazanim = j($sinav->kazanimlar);
              //  print2($sinav_kazanim); exit();
                // $fileEndEnd = iconv( 'UTF-8', 'UTF-8',$fileEndEnd);
                
               // print_r($sinav_json);
              //  $dosya_icerik = trk($dosya_icerik);
                $dosya_icerik = explode("\n",$dosya_icerik);
             //   print2($dosya_icerik);
              //  exit();
                //      echo $dosya_icerik;
                /*
                $satir = $dosya_icerik[0];
                $satir_toplam =strlen($satir);
                for($k=0;$k<$satir_toplam;$k++) {
                    echo $k . " " . trk($satir[$k]) . "<br>";
                }
                */
                $satir = 1;
                $ogrenci_bilgi_dizi = array();
                $ogrenci_cevap_dizi = array();

                foreach($dosya_icerik AS $di) {
                    $ok = 0;
                   
                 //   $di = str_replace(" ","*",$di);
                
                    foreach($optik_json['alan'] AS $optik_alan) {
                        $bas = $optik_json['bas'][$ok] - 1;
                        $son = $optik_json['son'][$ok] - 1;
                       // echo "$bas - $son :: <br>" ; 
                       $optik_alan = trim($optik_alan);
                       
                       $optik_alan_slug = str_slug($optik_alan);
                       if($optik_alan=="Öğrenci Adı") {
                            $ogrenci_bilgi_dizi[$satir][$optik_alan_slug] = trk(substr($di,$bas,$son-$bas));

                       } else {
                           if($optik_alan_slug=="tc-kimlik-no" || $optik_alan_slug=="kitapcik") {
                                $ogrenci_bilgi_dizi[$satir][$optik_alan_slug] = substr($di,$bas,$son-$bas);
                           } else {
                                $ogrenci_cevap_dizi[$satir][$optik_alan_slug] = substr($di,$bas,$son-$bas);
                           }
                            
                       }
                        
                        $ok++;
                    }
                  //  print2($ogrenci_cevap_dizi);
                  $satir++;
                }

               // print2($optik_json);
              //  print2($sinav_json); 
         //       print2($sinav_kazanim);
         if($ogrenci_bilgi_dizi[1]['kitapcik']=="") {
            echo "Kitapçık boş geldi. Lütfen optik işaretlemeyi kontrol ediniz veya txt dosyasındaki boş kayıtları düzeltiniz. Öğrencilerin bilgileri şu şekilde: ";
            print2($ogrenci_bilgi_dizi);
            exit(); 
         }
        
           
           
           /*
           sınav kazanım array örneği
            [fizik-tyt_kazanim_2] => Elektriksel alan
            [fizik-tyt_tak_2] => A2
            [fizik-tyt_c_soru_no_2] => 
            [fizik-tyt_d_soru_no_2] => 
            [fizik-tyt_b_soru_no_3] => 7
            [fizik-tyt_cevap_3] => Array
                (
                    [0] => C
                )

           */
           
           $sonuc_analiz = array();
           $ogrenci_sira = 1;
                foreach($ogrenci_cevap_dizi AS $alan =>  $cevaplar) {
               //     print2($alan);
              // print2($ogrenci_bilgi_dizi[$ogrenci_sira]);
                    $kitapcik = trim($ogrenci_bilgi_dizi[$ogrenci_sira]['kitapcik']);
                    $ogrenci_adi = trim($ogrenci_bilgi_dizi[$ogrenci_sira]['ogrenci-adi']);
                    $tckimlik = trim($ogrenci_bilgi_dizi[$ogrenci_sira]['tc-kimlik-no']);
                    
                    if(trim($tckimlik)!="") {
                        $hash = trim($tckimlik);
                    } else {
                        $hash = trim($ogrenci_adi);
                    }
                    if($hash!="") {
                        
                    
                    $sonuc_analiz[$hash]['kitapcik'] = $kitapcik;
                    $sonuc_analiz[$hash]['ogrenci-adi'] = $ogrenci_adi;
                    $sonuc_analiz[$hash]['tc-kimlik-no'] = $tckimlik;
                  //  echo($hash); 
                   
                    foreach($cevaplar AS $cevap_alan => $cevap_str) {
                        $cevap_str = str_split($cevap_str);
                  //     print2($cevap_alan);
                   //     print2($cevap_str);
                        
                        $soru_no = 1;
                        if(!isset($sonuc_analiz[$hash]['analiz'][$cevap_alan])) $sonuc_analiz[$hash]['analiz'][$cevap_alan] = array();
                        $bu_soru = $sonuc_analiz[$hash]['analiz'][$cevap_alan];
                        
                        if(!isset($bu_soru['dogru'])) $bu_soru['dogru'] = 0;
                        if(!isset($bu_soru['yanlis'])) $bu_soru['yanlis'] = 0;
                        if(!isset($bu_soru['bos'])) $bu_soru['bos'] = 0;
                        if(!isset($bu_soru['kazanim-dogru'])) $bu_soru['kazanim-dogru'] = array();
                        if(!isset($bu_soru['kazanim-yanlis'])) $bu_soru['kazanim-yanlis'] = array();
                        if(!isset($bu_soru['kazanim-bos'])) $bu_soru['kazanim-bos'] = array();
                        if(!isset($bu_soru['tak-dogru'])) $bu_soru['tak-dogru'] = array();
                        if(!isset($bu_soru['tak-yanlis'])) $bu_soru['tak-yanlis'] = array();
                        if(!isset($bu_soru['tak-bos'])) $bu_soru['tak-bos'] = array();
                        if(!isset($bu_soru['cevaplar'])) $bu_soru['cevaplar'] = "";
                        
                        foreach($cevap_str AS $ogrenci_cevap) {
                     //       echo $soru_no;
                            $kitapcik_soru_no = $soru_no; // a kitapçığında soru no sırasıyla
                            if(trim($kitapcik)=="") {
                                continue;
                                //die("Kitapçık boş geldi, lütfen optik işaretlemeyi kontrol ediniz.");
                            } else {
                                if($kitapcik!="A") {
                                    // echo $kitapcik; exit();
                                      $kitapcik_slug = str_slug($kitapcik);
                                      $soru_no_pattern = $cevap_alan."_" . $kitapcik_slug . "_soru_no_".$soru_no;  //fizik-tyt_c_soru_no_1
                                      
                                     $kitapcik_soru_no = $sinav_kazanim[$soru_no_pattern];
                                  }
                            }
                            
                            
                            $soru_pattern = $cevap_alan . "_cevap_" . $soru_no;
                            $kazanim_pattern = $cevap_alan . "_kazanim_" . $soru_no; //fizik-tyt_kazanim_2
                            $tak_pattern = $cevap_alan . "_tak_" . $soru_no; // [fizik-tyt_tak_2] => A2
                            $dogru_cevap = $sinav_kazanim[$soru_pattern];
                            $kazanim = $sinav_kazanim[$kazanim_pattern];
                            $tak = $sinav_kazanim[$tak_pattern];
                          
                            
                            
                            
                            
                            if(trim($ogrenci_cevap)!="") {
                                if(in_array($ogrenci_cevap,$dogru_cevap)) {
                                   
                                    array_push($bu_soru['tak-dogru'],$tak);
                                    array_push($bu_soru['kazanim-dogru'],$kazanim);
                                    $bu_soru['cevaplar'] .= $ogrenci_cevap;
                                    $bu_soru['dogru']++;
                                } else {
                                    array_push($bu_soru['tak-yanlis'],$tak);
                                    array_push($bu_soru['kazanim-yanlis'],$kazanim);
                                    $bu_soru['cevaplar'] .= strtolower($ogrenci_cevap);
                                    $bu_soru['yanlis']++;
                                }
                            } else {
                                array_push($bu_soru['tak-bos'],$tak);
                                array_push($bu_soru['kazanim-bos'],$kazanim);
                                $bu_soru['cevaplar'] .= "*";
                                $bu_soru['bos']++;
                            }
                            $soru_no++;
                        }
                        //if(!isset($sonuc_analiz[$hash][$cevap_alan][$soru_no])) $sonuc_analiz[$hash][$cevap_alan][$soru_no] = array();
                        
                        
                        
                        
                        
                        
                        
                        
                        if($bu_soru['dogru']==0 && $bu_soru['yanlis']==0) {
                            unset($sonuc_analiz[$hash]['analiz'][$cevap_alan]);
                        } else {
                            $sonuc_analiz[$hash]['analiz'][$cevap_alan] = $bu_soru;
                        }
                        
                        $soru_no++;
                        
                     //   print2($soru_anahtar);
                     //   print2($sinav_kazanim[$soru_anahtar]);

                        //analiz yapalım 
                    }
                    
                    $ogrenci_sira++;
                }
            }
               // print2($sonuc_analiz);
               return $sonuc_analiz;
}
function encoder($icerik) {
   // $icerik = utf8_encode($icerik);
    $icerik = strtr($icerik, array(
		'â€¢' => '•',
		'â€œ' => '“',
		'â€' => '”',
		'â€˜' => '‘',
		'â€™' => '’',
		'Ý¾' => 'İ',
		'Ý' => 'İ',
		'Ä°' => 'İ',
		'Ã' => 'İ',
		'â€¹' => 'İ',
		'&Yacute;' => 'İ',
		'ý' => 'ı',
		'Ä±' => 'ı',
		'Â±' => 'ı',
		'Ã½' => 'ı',
		'Ã›' => 'ı',
		'â€º' => 'ı',
		'&yacute;' => 'ı',
		'Þ' => 'Ş',
		'Åž' => 'Ş',
		'Ã…Å¸' => 'Ş',
		'Ã¥Ã¿' => 'Ş',
		'&THORN;' => 'Ş',
		'þ' => 'ş',
		'Å?' => 'ş',
		'ÅŸ' => 'ş',
		'&thorn;' => 'ş',
		'Ð' => 'Ğ',
		'Äž' => 'Ğ',
		'ð' => 'ğ',
		'Ä?' => 'ğ',
		'ÄŸ' => 'ğ',
		'&eth;' => 'ğ',
		'Ã‡' => 'Ç',
		'Ã?' => 'Ç',
		'&Ccedil;' => 'Ç',
        'Ã§' => 'ç',
		'&ccedil;' => 'ç',
		'Ã–' => 'Ö',
		'&Ouml;' => 'Ö',
		'Ã¶' => 'ö',
		'&ouml;' => 'ö',
		'Ãœ' => 'Ü',
		'&Uuml;' => 'Ü',
		'ÃƒÂ¼' => 'ü',
		'Ã£Â¼' => 'ü',
		'Ã¼' => 'ü',
        '&uuml;' => 'ü',
	));
    return $icerik;
}
function trk($icerik) {
//  $icerik =  iconv("ISO-8859-1", "UTF-8//TRANSLIT", $icerik);
 $encode = mb_detect_encoding($icerik);
 //echo $encode;
 //  $icerik =   mb_convert_encoding($icerik, "windows-1254", $encode);
  $icerik =   @iconv('windows-1254','UTF-8',$icerik);
   $icerik = encoder($icerik);
    return $icerik;
}
function excel_import($target_file) {
   // include 'Classes/PHPExcel/IOFactory.php';
    $objPHPExcel = PHPExcel_IOFactory::load($target_file);
    $excel = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
    return $excel;
}
function zf($d2){
$d1 = date('Y-m-d H:i:s');
//$d1= date('Y-m-d H:i:s', strtotime($d1. "$zaman_dilimi hour"));
//e($d1);
    if(!is_int($d1)) $d1=strtotime($d1);
    if(!is_int($d2)) $d2=strtotime($d2);
    $d=abs($d1-$d2);
if ($d1-$d2<0) {
$ifade = "sonra";
} else {
$ifade = "önce";
}

$once = " "; 
    if($d>=(60*60*24*365))    $sonuc  = $once . floor($d/(60*60*24*365)) . " yıl $ifade";
    else if($d>=(60*60*24*30))     $sonuc = $once . floor($d/(60*60*24*30)) . " ay $ifade";
    else if($d>=(60*60*24*7))  $sonuc  = $once . floor($d/(60*60*24*7)) . " hafta $ifade";
    else if($d>=(60*60*24))    $sonuc  = $once . floor($d/(60*60*24)) . " gün $ifade";
    else if($d>=(60*60))   $sonuc = $once . floor($d/(60*60)) . " saat $ifade";
    else if($d>=60) $sonuc  = $once . floor($d/60)  . " dakika $ifade";
    else $sonuc = "Az $ifade";

    return $sonuc;
}
function cfg($slug) {
    $c = db("contents")
    ->Where("kid","configuration-".$slug)
    ->get();
    $cikti = array();
    foreach($c AS $s) {
        array_push($cikti,$s->title);
    }
    return $cikti;
}

function logo($size="128",$style="") {
     ?>
     <img src="<?php echo url("assets/logo.svg") ?>" width="<?php echo $size ?>" style="    width: <?php echo $size ?>px;<?php echo $style ?>" alt="">
     <?php 
}
function center_logo() {
    logo("128","display:block;margin:0 auto;");
}
function navbar($title="") {
     ?>
     <!-- Top Navbar -->
<div class="navbar">
  <div class="navbar-bg"></div>
  <div class="navbar-inner">
    <div class="title">
        <!--
        <a href="./" class="back icon-only">
        <i class="f7-icons">arrow_left_circle_fill</i>
        </a>
-->
        <img src="<?php echo url("assets/icon.svg") ?>" width="48" style="    width: 40px;
vertical-align: middle" alt="">
       <?php echo $title ?>
    </div>
  </div>
</div>
     <?php 
}
function mailtemp($mail,$name,$data="") {
    $temp = db("contents")->where("title",$name)->first();
    $html = $temp->html;
    $subject = $temp->title2;
    if(is_array($data)) {
        foreach($data AS $a => $d) {
            $html = str_replace("{".$a."}",$d,$html);
            $subject = str_replace("{".$a."}",$d,$subject);
        }
    }
    
    @mailsend($mail,$subject,$html);
}

function total($tablo,$col,$val) {
    $sorgu = db($tablo)->where($col,$val)->get($col);
    return count($sorgu);
}
function variable($title) {
    $s = db("contents")->where("title",$title)->first();
    return $s->html;
}
function df($date,$format="d.m.Y H:i") {
    return date($format,strtotime($date));
}
function mailsend($to="",$subject="",$html="") {
    //VBgDMfu6L5kksh noreply@truncgil.com
    
    $data = array(
        'html'=>$html,
        "subject" => $subject,
        "to" => $to 
    );
    $title = "Sphyzer";
    try {
        Mail::send("mail-template", $data, function($message) use($to, $subject,$title){
        
            $message->from("noreply@truncgil.com", $title);
            $message->to($to);
            $message->subject($subject);
        });
    } catch (\Throwable $th) {
        //throw $th;
    }

    
}

function alert($text,$type="success") {
     ?>
    <script>
    
        window.setTimeout(function(){
            var notification  = app.notification.create({
            // icon: '',
                title: 'Sphyzer',
           //     titleRightText: 'Şimdi',
                subtitle: '<?php e2($text) ?>',
                text: 'Kapat',
                closeOnClick: true,
            });
            notification.open();
        },500);
  
    </script>
     <?php 
}

function iptolocation() {
    $j = file_get_contents("http://ip-api.com/json/{$_SERVER['REMOTE_ADDR']}");
    $j = json_decode($j);
    if($j->country == "United States") {
        $j->country = "USA";
    }
    return $j;
    
}
function ed($text,$elsetext) {
    if($text=="") return $elsetext;
    else return $text;
}
function sales_status($y="") {
    if($y=="") {
        return explode(",","Under Negotiate,Due to Payment,Payment Complete,Booking,Shipment,Sold");
    }
    
}
function status($y) {
    $status = array("Wait to Invoice","Non Approve","Approve");
    return $status[$y];
}
function status_color($y) {
    $color = array("danger","warning","success");
    return $color[$y];
}
function languages() {
    $diller = explode(",","en,de,tr");
    return $diller;
}
function picture($f,$type="large") {
    $f =  str_replace("storage/app/files/","",$f);
    $f = url("cache/$type/".$f);
    return $f;
}
function picture2($f,$size,$storage=1) {
    if($storage==1) {
        $f = "storage/app/files/$f";
    }    
    $f = url("r.php?p=$f&w=$size");
    return $f;
}
function price($price,$type="¥") {
    $price = str_replace(",","",$price);
    $price = str_replace(".","",$price);
    $price = str_replace("$","",$price);
    $price = str_replace(" ","",$price);
  //  echo $price;
    $price = @number_format($price, 0, ',', '.');
    return "$type $price";
}
function remaining_cost($inquiry_id) {
    $sorgu = db("payments")->select(DB::raw('SUM(fob) AS toplam'))->where("kid",$inquiry_id)->first();
    return $sorgu->toplam;
}
function clean_price($price) {
    $price = str_replace(",","",$price);
    $price = str_replace(".","",$price);
    $price = str_replace("$","",$price);
    $price = str_replace("¥","",$price);
    $price = str_replace("€","",$price);
    $price = str_replace(" ","",$price);
  //  echo $price;
 //   $price = @number_format($price, 0, ',', '.');
    return (float) $price;
}


function price2($price,$type="¥") {
    //$price = str_replace(".","",$price);
    $price = @number_format($price, 0, ',', '.');
    return "$type $price";
}
function mile($mile,$type="KM") {
    $type = strtoupper($type);
    $mile = str_replace(".","",$mile);
    $mile = @number_format($mile, 0, ',', '.');
    return "$mile $type";
}
function currency() {
    return explode(",","Dolar,Euro");
}
function stock_no() {
    $son = db("vehicles")->orderBY("id","desc")->first();
    $date = date("y");
    if($son) {

   
    $j = j($son->json);
    
    if(isset($j['stock_no'])) {
        $stock_no = $j['stock_no'];
        $stock_no = str_replace("HBS-","",$stock_no);
        $stock_no = substr($stock_no,2,strlen($stock_no));
      //  echo $stock_no;
        $stock_no++;
        $kalan = 7-strlen($stock_no);
        $zero ="";
        for($k=1;$k<$kalan;$k++) {
            $zero .=0;
        }
        $stock_no = "S".$date.$zero.$stock_no;
    } else {
        $stock_no = "S".$date."000001";
    }
    } else {
        $stock_no = "S".$date."000001";
    }
    return $stock_no;

}
function simdi() {
    return date("Y-m-d H:i:s");
}
function fob($price) {
    $fob = str_replace("$ ","",$price);
	$fob = str_replace(",","",$fob);
	return $fob;
}
function curr($type) {
    $kur = cfg3("currency-settings");
    return $kur[$type];
}

function cfg2($slug) {
    $c = db("contents")
    ->Where("kid","configuration-".$slug)
    ->get();
    $cikti = array();
    foreach($c AS $s) {
        array_push($cikti,$s);
    }
    return $cikti;
}
function cfg3($slug)
{

    $c = Contents::where("slug", $slug)->orWhere("type", $slug)->orderBy("id","DESC")->first();
    if($c) {
        $c = json_decode($c->json,true);
    } else {
        $c = array();
    }
    
    return $c;
}
function pic($pic,$type) {
    $pic = str_replace("storage/app/files/","",$pic); 
    return url("cache/$type/$pic");
}
function product($c) {
    //bu fonk. bir ürün blok tasarımını örnekler
     ?>
       <div class="card">
        <img class="card-img" src="https://s3.eu-central-1.amazonaws.com/bootstrapbaymisc/blog/24_days_bootstrap/vans.png" alt="Vans">
        <div class="card-img-overlay d-flex justify-content-end">
          <a href="#" class="card-link text-danger like">
            <i class="fas fa-heart"></i>
          </a>
        </div>
        <div class="card-body">
          <h4 class="card-title">{title}</h4>
          <h6 class="card-subtitle mb-2 text-muted">Style: VA33TXRJ5</h6>
          <p class="card-text">
            The Vans All-Weather MTE Collection features footwear and apparel designed to withstand the elements whilst still looking cool.             </p>
          <div class="options d-flex flex-fill">
             <select class="custom-select mr-1">
                <option selected>Color</option>
                <option value="1">Green</option>
                <option value="2">Blue</option>
                <option value="3">Red</option>
            </select>
            <select class="custom-select ml-1">
                <option selected>Size</option>
                <option value="1">41</option>
                <option value="2">42</option>
                <option value="3">43</option>
            </select>
          </div>
          <div class="buy d-flex justify-content-between align-items-center">
            <div class="price text-success"><h5 class="mt-4">$125</h5></div>
             <a href="#" class="btn btn-danger mt-3"><i class="fas fa-shopping-cart"></i> Add to Cart</a>
          </div>
        </div>
      </div>
     <?php 
}



function number($str)
{
    $str = str_replace(",", ".", $str);
    $str = floatval($str);
    return $str;
}
function nf($price,$type="Ad.",$decimal=0) {
    /*
    $price = str_replace(",","",$price);
    $price = str_replace(".","",$price);
    $price = str_replace("$","",$price);
    $price = str_replace(" ","",$price);
    */
  //  echo $price;
    $price = @number_format($price, $decimal, ',', '.');
    return "$price $type";
}

function map($title)
{
    $sorgu = db("contents")->where("kid", "configuration-planning-column-mapping")
        ->where("title", $title)
        ->first();
    if ($sorgu) {
        if ($sorgu->title2 != "") {
            return $sorgu->title2;
        } else {
            return $title;
        }
    } else {
        return $title;
    }

}

function is_json($string)
{
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}

function whereJ($db, $col, $isaret, $val, $fonk = "")
{
    $db = $db->whereRaw("$fonk(JSON_UNQUOTE(json_extract(json, '$.\"$col\"'))) $isaret $val");
    return $db;
}

function orWhereJ($db, $col, $isaret, $val, $fonk = "")
{
    $db = $db->orWhereRaw("$fonk(JSON_UNQUOTE(json_extract(json, '$.\"$col\"'))) $isaret $val");
    return $db;
}
function tirnakli($text) {
    return "'$text'";
}
function chartsByData($db,$labelGroup,$title="",$type="bar",$colorItem="1",$columnSize="col-md-6") {
    $array = array();
    $db = db($db)->get();
    foreach($db AS $d) {
        if($labelGroup=="date") {
            $label = date("d.m.Y",strtotime($d->created_at));
        } else {
            $label = $d->{$labelGroup};
        }
        if(!isset($array[$label])) $array[$label] = 0;
        $array[$label]++;
    }       
    $values = implode(",",$array);
    $labels =  implode_key(",",$array);
     ?>
     <div class="<?php echo $columnSize ?>">
        <div class="block block-themed">
            <div class="block-header bg-<?php echo colors()[$colorItem]; ?>">
                <div class="block-title">
                    <span><?php echo $title ?></span>
                </div>
                <div class="block-options">
                   <?php echo $db->count() ?>
                </div>
            </div>
            <div class="block-content">
            <?php 
                    charts($labels,$values,$title,$type);
                ?>
            </div>
        </div>
     </div>
     <?php 
}
function charts($labels,$values,$title="",$type="doughnut",$height="400") {
    $id = rand();
    $opacity = 1;
    $labels = explode(",",$labels);
    $labels = array_map('tirnakli', $labels);
    $labels = implode(",",$labels);
     ?>
<canvas id="truncgil<?php echo $id ?>" class="truncgil-chart"  style="width:<?php echo $height; ?>px !important;height:<?php echo $height; ?>px !important;max-height:<?php echo $height; ?>px !important" ></canvas>
<script>
var ctx = document.getElementById('truncgil<?php echo $id ?>');
var myChart = new Chart(ctx, {
    type: '<?php echo $type ?>',
    data: {
        labels: [<?php echo $labels ?>],
        datasets: [{
            label: '<?php echo $title ?>',
            data: [<?php echo $values ?>],
            backgroundColor: [
                'rgba(54, 162, 235, <?php echo $opacity ?>)',
                'rgba(255, 99, 132, <?php echo $opacity ?>)',
                'rgba(255, 206, 86, <?php echo $opacity ?>)',
                'rgba(75, 192, 192, <?php echo $opacity ?>)',
                'rgba(153, 102, 255, <?php echo $opacity ?>)',
                'rgba(255, 159, 64, <?php echo $opacity ?>)'
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
            display: false
            }
        }
    }
});
</script>
     <?php 
}
function charts2($labels,$values,$title="",$type="doughnut",$height="400") {
    $id = rand();
    $opacity = 1;
    $labels = explode(",",$labels);
    $labels = array_map('tirnakli', $labels);
    $labels = implode(",",$labels);
     ?>
<canvas id="truncgil<?php echo $id ?>" class="truncgil-chart"  style="width:<?php echo $height; ?>px !important;height:<?php echo $height; ?>px !important;max-height:<?php echo $height; ?>px !important" ></canvas>
<script>
var ctx = document.getElementById('truncgil<?php echo $id ?>');
var myChart = new Chart(ctx, {
    type: '<?php echo $type ?>',
    data: {
        labels: [<?php echo $labels ?>],
        datasets: [{
            
            label: '<?php echo $title ?>',
            data: [<?php echo $values ?>],
            backgroundColor: [
                'rgba(54, 162, 235, <?php echo $opacity ?>)',
                'rgba(255, 99, 132, <?php echo $opacity ?>)',
                'rgba(255, 206, 86, <?php echo $opacity ?>)',
                'rgba(75, 192, 192, <?php echo $opacity ?>)',
                'rgba(153, 102, 255, <?php echo $opacity ?>)',
                'rgba(255, 159, 64, <?php echo $opacity ?>)'
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            },
            x: [{
            gridLines: {
                display:false
            }
            }],
            y: [{
                gridLines: {
                    display:false
                }   
            }]
        },
        plugins: {
            legend: {
            display: false
            }
        }
    }
});
</script>
     <?php 
}
function chart($type, $col, $val)
{
    $id = rand(111, 999);
    ?>

    <canvas id="chart-area<?php echo $id ?>"></canvas>

    <script>


        var config<?php echo $id ?> = {
            type: '<?php echo $type ?>',
            data: {
                datasets: [{
                    data: [
                        <?php echo $val; ?>
                    ],
                    backgroundColor: [
                        window.chartColors.red,
                        window.chartColors.orange,
                        window.chartColors.yellow,
                        window.chartColors.green,
                        window.chartColors.blue,
                    ],
                    label: 'Dataset <?php echo $id ?>'
                }],
                labels: [
                    <?php echo $col; ?>
                ]
            },
            options: {
                responsive: false
            }
        };

        window.onload = function () {
            var ctx<?php echo $id ?> = document.getElementById('chart-area<?php echo $id ?>').getContext('2d');
            window.test<?php echo $id ?> = new Chart(ctx<?php echo $id ?>, config<?php echo $id ?>);
        };


    </script>

    <?php
}
function tak_list() {
    $tak_harf = explode(",","A,B,C,D");
    $tak_sayi = 6;
    $dizi = array();
    foreach($tak_harf AS $t) {
        for($z=1;$z<=$tak_sayi;$z++) {
            array_push($dizi,$t.$z);
        }
    }
    return $dizi;
}
function upload($file, $folder = "")
{
    $request = \Request::all();

    $ext = $request[$file]->getClientOriginalExtension();
    $name = str_slug($request[$file]->getClientOriginalName());
    $path = $request[$file]->storeAs("files/$folder", "$name.$ext");
    return "storage/app/$path";
}
function upload2($file, $folder = "") 
{
    $u = u();
    $dizin = str_slug($u->name." ". $u->surname);
    $request = \Request::all();
    @mkdir("storage/app/$dizin/$folder",true);
    $ext = $request[$file]->getClientOriginalExtension();
    $name = str_slug($request[$file]->getClientOriginalName());
    $path = $request[$file]->storeAs("files/$dizin/$folder", $name.".".$ext);
    return "storage/app/$path";
}
function file_get_contents_utf8($fn) {
    $content = file_get_contents($fn);
     return iconv("ISO-8859-1","UTF-8",$content);
}
function correct_encoding($text) {
    $current_encoding = mb_detect_encoding($text, 'auto');
    $text = iconv($current_encoding, 'UTF-8', $text);
    return $text;
}

function varmi($dizi)
{
    if (count($dizi) > 0) {
        return true;
    } else {
        return false;
    }
}
function vehicles() {
    $s = db("vehicles");
    $s = $s->where("y","1");
    $s = $s->take(10);
    return $s;
}

function slugtotitle($slug)
{
    $slug = str_replace("-", " ", $slug);
    $slug = ucwords($slug);
    return $slug;
}

function seri()
{
    ?>
    <script type="text/javascript">
        $(function () {

            $(".seri").on("submit", function (e) {
                var buton = $(".seri button");
                var ajax_alan = $(this).attr("ajax");
                if (ajax_alan == undefined) {
                    ajax_alan = ".seri_ajax";
                }
                var yazi = buton.html();
                var data = $(this).serialize();
                buton.prop("disabled", "disabled");
                e.preventDefault();
                var formData = new FormData($(this)[0]);
                $.ajax({
                    url: $(this).attr("action"),
                    type: "POST",
                    cache: false,
                    processData: false,  // tell jQuery not to process the data
                    contentType: false,  // tell jQuery not to set contentType
                    data: formData,
                    success: function (d) {
                        buton.removeAttr("disabled");
                        $(ajax_alan).html(d);


                    }
                });
                return false;
            });
        });
    </script>
    <?php
}

function sf($id, $ajax = ".ajax", $html = "")
{
    $ajax = "$id $ajax";
    ?>
    <script type="text/javascript">
        $(function () {
            $("<?php echo $id ?>").on("submit", function () {
                var form = $("<?php echo $id ?>");
                var data = form.serialize();
                $(this).children("button").html("<?php e2("İşlem başarılı") ?>");
                $.ajax({
                    type: "POST",
                    url: form.attr("action"),
                    data: data,
                    dataType: "json",
                    success: function (data) {
                        <?php if($html == "") { ?>
                        $("<?php echo $ajax ?>").html(d);
                        <?php } else { ?>
                        $("<?php echo $ajax ?>").html("<?php echo($html) ?>");
                        <?php } ?>
                        $("<?php echo $id ?> button").html("<?php e2("İşlem başarılı") ?>");
                    }
                });
                return false;
            });
        });
    </script>
    <?php
}
 
function c($slug)
{
    $c = Contents::where("slug", $slug)->orWhere("id", $slug)->first();
    return $c;
}


function contents($type)
{
    return db("contents")
        ->where("kid", $type)
        ->orWhere("type", $type)
//		->orWhere("title",$type)
        ->get();
}
function contents2($type)
{
    return db("contents")
        ->where("kid", $type)
        ->orWhere("type", $type);
//		->orWhere("title",$type)
       
}

function kd()
{
    return 0;
}

function user() {
    global $_SESSION;
    oturumAc();
    if(oturumisset("uid")) {
        $u = (Array) db("users")->where("id",oturum("uid"))->first();
        unset($u['id']);
        unset($u['password']);
        unset($u['password_hash']);
        unset($u['recover']);
        unset($u['remember_token']);
        unset($u['permissions']);
        unset($u['created_at']);
        unset($u['updated_at']);
        return $u;
    } else {
        return false;
    }
    
}
function usersArray() {
    $users = db("users")
    ->get();
    $users = dbArray($users,"id");
    return $users;
}

function username($id,$users) {
   
    $user = "";
    if(isset($users[$id])) {
        $user =$users[$id];
        $user = $user->name . " " . $user->surname;
    }
    return $user;
}
function users($level)
{
    return User::where("level", $level)->get();
}

function who($uid)
{
    return User::where("id", $uid)->first();
}

function ksorgu()
{
    return 0;
}

function e2($text)
{
    echo __($text);
}

function set($text)
{
    echo __($text);
}

function set_return($text)
{
    return __($text);
}
function permission() {
    oturumAc();

    if(!oturumisset("uid"))  {
       
        echo("Bu sayfayı görmek için yetkiniz bulunmamaktadır");
        exit();
    }
}

function u()
{   
    oturumAc();
    if(Auth::check()) {
        $u = Auth::user();
        $alias = alias_to_ids($u->alias);
        $u['alias_ids'] = $alias;
        return $u;
    } elseif(oturumisset("uid")) {
        $uid = db("users")->where("id",oturum("uid"))->first();
        
        return $uid;
    }
    
}

function u2($id)
{   
   
        $uid = db("users")->where("id",$id)->first();
        return $uid;
    
    
}

function alias_to_ids($alias) { //aynı etki alanına sahip kullanıcıların id listesini döndürür.
    $sorgu = db("users")->where("alias",$alias)->get();
    $ids = array();
    foreach($sorgu AS $s) {
        array_push($ids,$s->id);
    }
    return $ids;
}
function ekle($dizi, $tablo = "contents")
{
    oturumAc();
    $uid = "";
    if(isset(u()->id)) {
        $uid = u()->id;
    }
    
    if($uid=="") $uid = oturum("uid");
    $dizi['created_at'] = date("Y-m-d H:i:s");
    $dizi['uid'] = $uid;
    if($dizi['uid']=="") unset($dizi['uid']);
	//print_r($dizi);
    return DB::table($tablo)->insertGetId($dizi);
}
function ekle2($dizi, $tablo = "contents") //uid siz ekleme yapar
{
    oturumAc();
    $dizi['created_at'] = date("Y-m-d H:i:s");
   // $dizi['uid'] = "";
    return DB::table($tablo)->insertGetId($dizi);
}
function login() {
    oturumAc();
    global $_SESSION;
    if(oturumisset("uid")) {
        return true;
    } else {
        return false;
    }
}

function kripto($text) {
    return Hash::make($text);
}
function guncelle($dizi, $tablo = "contents")
{
    oturumAc();
    $dizi['updated_at'] = date("Y-m-d H:i:s");
    $dizi['uid'] = u()->id;
//	print_r($dizi);
    return DB::table($tablo)->update($dizi);
}

function dbFirst($tablo, $id)
{
    return $s = DB::table($tablo)->where("id", $id)->first();
}

function db($tablo)
{

    $s = DB::table($tablo);
    return $s;
}
function db2($tablo)
{
    $u = u();
 //   $alias_id = implode(",",$u->alias_ids);

    $s = DB::table($tablo)->whereIn("uid",$u->alias_ids);
    return $s;
}

function sorgu($tablo, $where = "", $order = "")
{
    $s = DB::table($tablo);
    if (strpos("%", $where) !== false) {
        $s = $s->where("json", "like", "$where");
    } else {
        if ($where != "") {
            $where = explode(",", $where);
            foreach ($where as $w) {
                $w2 = explode("=", $w);
                if (count($w2) > 1) {
                    $s = $s->whereJsonContains("json->" . $w2[0], $w2[1]);
                }
                $w2 = explode("%", $w);
                if (count($w2) > 1) {
                    $s = $s->where("json", "like", $w2[1]);
                }

            }
        }
    }
    if ($order != "") $s = $s->orderByRaw($order);
    $cache = array();
    $sorgu = $s->simplePaginate(15);
    $col = array();
    $row = array();
    $cache['col'] = array();
    $cache['row'] = array();
    $cache['links'] = "";
    if (count($sorgu) > 0) {

        foreach ($sorgu as $s) {
            $j = json_decode($s->json);
            $j->id = $s->id;
            $j->Create_Date = $s->created_at;
            unset($j->_token);
            array_push($cache, $j);
        }
        foreach ($cache as $a => $d) {
            array_push($row, $d);
        }
        foreach ($cache[0] as $a => $d) {
            array_push($col, str_replace("_", " ", $a));
        }
        $cache['col'] = $col;
        $cache['row'] = $row;
        $cache['row'] = array_filter($cache['row']);
        $cache['links'] = $sorgu->links();
        $cache['table'] = $tablo;
    }

    return $cache;
}
function dbArray($db,$key) {
    $dizi = array();
    foreach($db AS $d) {
  
        $dizi[$d->$key] = $d;
    }
    return $dizi;
}
function table_to_array($table,$key="id") {
    $dizi = array();
    $db = db($table)->get();
    foreach($db AS $d) {
  
        $dizi[$d->$key] = $d;
    }
    return $dizi;
}
function table_to_array2($table,$key="id") {
    $dizi = array();
    if($table=="users") {
        $db = db($table)->where("alias",u()->alias)->get();
    } else {
        $db = db2($table)->get();
    }
    
    foreach($db AS $d) {
  
        $dizi[$d->$key] = $d;
    }
    return $dizi;
}
function contents_to_array($type,$key="id") {
    $dizi = array();
   
    $db = db("contents")->where("type",$type)
    ->whereNotNull("title")
    ->get();
   
    
    foreach($db AS $d) {
  
        $dizi[$d->$key] = $d;
    }
    return $dizi;
}
function dbJson($db, $tablo = "")
{ //db oluşturulmuş bir sorguyu json cache çıktısını verir.

    $cache = array();
    $sorgu = $db;
    $col = array();
    $row = array();
    $cache['col'] = array();
    $cache['row'] = array();
    $cache['links'] = "";
    if (count($sorgu) > 0) {

        foreach ($sorgu as $s) {
            $j = json_decode($s->json);
            $j->id = $s->id;
            $j->Create_Date = $s->created_at;
            unset($j->_token);
            array_push($cache, $j);
        }
        foreach ($cache as $a => $d) {
            array_push($row, $d);
        }
        foreach ($cache[0] as $a => $d) {
            array_push($col, str_replace("_", " ", $a));
        }
        $cache['col'] = $col;
        $cache['row'] = $row;
        $cache['row'] = array_filter($cache['row']);
        $cache['links'] = $sorgu->links();
        $cache['table'] = $tablo;
    }

    return $cache;
}


function bilgi($text,$type="success")
{
    ?>
    <div class="alert alert-<?php echo $type ?>"><?php echo __($text); ?></div>
    <?php
}

function showMessage($text, $message_type)
{
    switch ($message_type) {
        case MessageType::Success:
            ?>
            <div class="alert alert-success"><?php echo __($text); ?></div>
            <?php
            break;
        case MessageType::Error:
            ?>
            <div class="alert alert-danger"><?php echo __($text); ?></div>
            <?php
            break;
    }
}

function json_encode_tr($string)
{
    return json_encode($string, JSON_UNESCAPED_UNICODE);
}

function j($json, $true = true)
{
    return json_decode($json, $true);
}

function get($isim)
{
    if (isset($_GET[$isim])) {
        return $_GET[$isim];
    } else {
        return "";
    }
}

function yonlendir($url)
{
    header("Location: $url");
    exit();
}

function getisset($isim)
{
    if (isset($_GET[$isim])) {
        return 1;
    } else {
        return 0;
    }
}

function postEsit($post, $deger)
{
    $post = post($post);
    if ($post == $deger) {
        return 1;
    } else {
        return 0;
    }
}

function oturumEsit($oturum, $deger)
{
    $oturum = oturum($oturum);
    if ($oturum == $deger) {
        return 1;
    } else {
        return 0;
    }
}

function getEsit($get, $deger)
{
    $get = get($get);
    if ($get == $deger) {
        return 1;
    } else {
        return 0;
    }
}

function post($isim, $deger = "")
{
    if ($deger != "") {
        $_POST[$isim] = $deger;
    } else {
        if (isset($_POST[$isim])) {
            return @trim($_POST[$isim]);
        } else {
            return "";
        }
    }
}

function postisset($isim)
{
    if (isset($_POST[$isim])) {
        return 1;
    } else {
        return 0;
    }
}

function oturum($isim, $deger = "")
{
    oturumAc();
    if (isset($_SESSION[$isim])) {
        if ($deger == "") {
            return $_SESSION[$isim];
        } else {
            $_SESSION[$isim] = $deger;
            return $_SESSION[$isim];
        }
    } elseif ($deger != "") {
        $_SESSION[$isim] = $deger;
        return $_SESSION[$isim];

    }
}

function oturumisset($isim)
{
    oturumAc();
    if (isset($_SESSION[$isim])) {
        return 1;
    } else {
        return 0;
    }
}

function oturumAc($sonuc = "")
{
    if (!isset($_SESSION)) {
        session_start();
        echo $sonuc;
    }
}

function diger_ayarlar()
{
    return explode(",", "users,languages,contents,new,fields,search,ALL PRIVILEGES");

}

function fields()
{
    $fields = Fields::get();
    $fields = json_decode($fields, true);
    $fields2 = array();
    foreach (@$fields as $r) {
        if (in_array($r['title'], $content_type)) {
            $fields2[$r['title']] = array(
                "values" => explode(",", $r['values']),
                "type" => $r['input_type']
            );
        }

    }
    $fields = $fields2;
    /*
        if(isset($ct->fields)) {
            $content_fields = explode(",",$ct->fields); // içerik alanları
        }
    */
    return $fields;
}

function json_field($json, $field)
{ //bir json içinde girilmiş alanı bulur bu aslında post ederken boşluk içeren alanlarda otomatik oluşan _ karakteri sorunundan dolayı üretildi
    return @$json[str_replace(" ", "_", $field)];

}

function validBase64($string)
{
    $decoded = base64_decode($string, true);

    // Check if there is no invalid character in string
    if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $string)) return false;

    // Decode the string in strict mode and send the response
    if (!base64_decode($string, true)) return false;

    // Encode and compare it to original one
    if (base64_encode($decoded) != $string) return false;

    return true;
}

function isJSON($string)
{
    return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
}

function getLangFile($lang)
{
    $path = "resources/lang/$lang" . ".json";
    if (file_exists($path)) {
        return file_get_contents($path);
    } else {
        $json = json_encode(array());
        file_put_contents($path, $json);
        return file_get_contents($path);
    }
}

function putLangFile($lang, $json)
{
    if (isJSON($json)) {
        return file_put_contents("resources/lang/$lang" . ".json", $json);
    } else {
        return null;
    }
}

function is_html($string)
{
    return preg_match("/<[^<]+>/", $string, $m) != 0;
}

function readYPTABFile($yptab_file_name)
{

    Log::debug("Start parsing the file $yptab_file_name");

    $file_content = file_get_contents($yptab_file_name);
    $file_content_rows = explode("\n", $file_content);

    $row_index = 0;

    $sap_planning = array();
    $columns_name = array();

    // read give file rows
    foreach ($file_content_rows as $file_content_row) {

        $file_content_row_columns = explode("|", $file_content_row);

        $sap_planning['row'][$row_index] = array();
        if ($row_index == 0) {
            // the first row is columns name
            $sap_planning['col'] = array();
            $column_index = 0;

            array_push($sap_planning['col'], "ID");
            array_push($sap_planning['col'], "Color");

            foreach ($file_content_row_columns as $column_title) {
                array_push($sap_planning['col'], $column_title);
                $columns_name[$column_title] = $column_index;
                $column_index++;
            }
        } else {
            $x = 0;
            @$sap_planning['row'][$row_index][$sap_planning['col'][$x]] = $file_content_row_columns[8];//date("His").rand(11111,99999);
            @$sap_planning['row'][$row_index][$sap_planning['col'][$x + 1]] = "hsla(" . rand(100, 360) . "," . rand(0, 100) . "%," . rand(10, 100) . "%,0.3)";

            // iterate over columns to complete the row
            foreach ($file_content_row_columns as $column_content) {
                @$sap_planning['row'][$row_index][$sap_planning['col'][$x + 2]] = trim($column_content);
                $x++;
            }
        }

        $row_index++;
    }
    $sap_planning['row'] = array_filter($sap_planning['row']);
    $sap_panning['map'] = $columns_name;

    Log::debug("Finish parsing the file $yptab_file_name");

    return $sap_planning;
}

function readYPLABFile($yptab_file_name)
{

    Log::debug("Start parsing the file $yptab_file_name");

    $file_content = file_get_contents($yptab_file_name);
    $file_content_rows = explode("\n", $file_content);

    $row_index = 0;

    $sap_planning = array();
    $columns_name = array();

    // read give file rows
    foreach ($file_content_rows as $file_content_row) {

        $file_content_row_columns = explode("|", $file_content_row);

        $sap_planning['row'][$row_index] = array();
        if ($row_index == 0) {
            // the first row is columns name
            $sap_planning['col'] = array();
            $column_index = 0;

            array_push($sap_planning['col'], "ID");
            array_push($sap_planning['col'], "Color");

            foreach ($file_content_row_columns as $column_title) {
                array_push($sap_planning['col'], $column_title);
                $columns_name[$column_title] = $column_index;
                $column_index++;
            }
        } else {
            $x = 0;
            @$sap_planning['row'][$row_index][$sap_planning['col'][$x]] = $file_content_row_columns[8];//date("His").rand(11111,99999);
            @$sap_planning['row'][$row_index][$sap_planning['col'][$x + 1]] = "hsla(" . rand(100, 360) . "," . rand(0, 100) . "%," . rand(10, 100) . "%,0.3)";

            // iterate over columns to complete the row
            foreach ($file_content_row_columns as $column_content) {
                @$sap_planning['row'][$row_index][$sap_planning['col'][$x + 2]] = trim($column_content);
                $x++;
            }
        }

        $row_index++;
    }
    $sap_planning['row'] = array_filter($sap_planning['row']);
    $sap_panning['map'] = $columns_name;

    Log::debug("Finish parsing the file $yptab_file_name");

    return $sap_planning;
}

function readYPROFile($ypro_file_name)
{

    Log::debug("Start parsing the file $ypro_file_name");

    $file_content = file_get_contents($ypro_file_name);
    $file_content_rows = explode("\n", $file_content);

    $row_index = 0;

    $sap = array();
    $columns_name = array();

    // read give file rows
    foreach ($file_content_rows as $file_content_row) {

        $file_content_row_columns = explode(";", $file_content_row);

        $sap['row'][$row_index] = array();
        if ($row_index == 0) {
            // the first row is columns name
            $sap['col'] = array();
            $column_index = 0;
            foreach ($file_content_row_columns as $column_title) {
                array_push($sap['col'], $column_title);
                $columns_name[$column_title] = $column_index;
                $column_index++;
            }
        } else {
            // iterate over columns to complete the row
            foreach ($file_content_row_columns as $column_content) {
                if (is_float($column_content)) {
                    array_push($sap['row'][$row_index], eval($column_content));
                } else {
                    array_push($sap['row'][$row_index], ($column_content));
                }
            }
        }

        $row_index++;
    }
    $sap['map'] = $columns_name;

    Log::debug("Finish parsing the file $ypro_file_name");

    return $sap;
}

/**
 *
 *
 * @param $qty_diff int how many item should be added to the board
 * @param $qty_of_each_card int quantity of each card
 * @param $last_card any last card of process id
 * @param $total_shaped int how many items are already shaped
 * @param $planning_board any the whole board
 */
function pushNewCards($qty_diff, $qty_of_each_card, $last_card, $total_shaped, $planning_board)
{
    // There is not any new card
    if (empty($qty_diff) || empty($last_card) || empty($last_card['html'])) {
        return;
    }

    $changed_items = array();

    $last_card = getCardById($last_card['id'], $planning_board);
    // Fill the latest card if it has capacity
    if (isset($last_card->place_holder['qty']) &&
        $last_card->place_holder['qty'] < $qty_of_each_card) {
        if ($qty_diff < ($qty_of_each_card - $last_card->place_holder['qty'])) {
            $last_card->place_holder['qty'] += $qty_diff;
            $total_shaped -= $qty_diff;
            $qty_diff = 0;
        } else {
            $qty_diff -= ($qty_of_each_card - $last_card->place_holder['qty']);
            $total_shaped -= ($qty_of_each_card - $last_card->place_holder['qty']);
            $last_card->place_holder['qty'] = $qty_of_each_card;
        }
        $last_card->place_holder['html'] = Card::generateHtml(
            $last_card->place_holder['jid'],
            $last_card->place_holder['qty'],
            str_replace(Card::PART_TITLE, "", $last_card->place_holder['number']),
            $total_shaped >= $last_card->place_holder['qty'] ?
                100 : round(100 * $total_shaped / $last_card->place_holder['qty'], 0),
            $last_card->place_holder['html']
        );

    }
    if (!isset($changed_items[$last_card->place_holder['date']])) {
        $changed_items[$last_card->place_holder['date']] = array();
    }
    array_push(
        $changed_items[$last_card->place_holder['date']],
        $last_card
    );
    $planning_board = updatePlanningBoard($planning_board, $changed_items, false);

    $card_number = (int)str_replace("Part ", "", $last_card->place_holder['number']);
    $jid = $last_card->place_holder['jid']; // use the same jid of latest card
    // clear any progress
    $html_template = str_replace(' checked3="checked"><div class="json d-none">', '><div class="json d-none">', $last_card->place_holder['html']);
    $html_template = preg_replace('/job(.*) fill halfcheck(.*)- /m', 'job fill', $html_template);

    // Loop over current workstation in the board to find the first empty or not finished place holder
    $next_place_holder = getCardById(
        nextCardId($last_card->place_holder),
        $planning_board
    );
    while (!empty($next_place_holder->place_holder['html']) &&
        (strpos($next_place_holder->place_holder['html'], ' checked3="checked"') ||
            strpos($next_place_holder->place_holder['html'], ' job fill halfcheck'))
    ) {
        $next_card_id = nextCardId($next_place_holder->place_holder);
        $next_place_holder = getCardById(
            $next_card_id,
            $planning_board);
    }


    // if there is not any place holder to fill
    if (empty($next_place_holder)) {
        //TODO: throw exception
        Log::error("There is not any card to push the new cards");
        return;
    }

    $current_place_holder = new Card(
        isset($next_place_holder->place_holder['jid']) ? $next_place_holder->place_holder['jid'] : "",
        $next_place_holder->place_holder['id'],
        $next_place_holder->place_holder['html'],
        $next_place_holder->place_holder['date'],
        "",
        $next_place_holder->place_holder['number'],
        $next_place_holder->place_holder['qty'],
        $next_place_holder->place_holder['workstation'],
        $next_place_holder->index_per_day
    );
    for (; $qty_diff > 0;) {
        // keep current content of the place holder
        if (isset($current_place_holder->jid)) {
            $moving_card_jid = $current_place_holder->jid;
        }
        $moving_card_html = $current_place_holder->html;
        $moving_card_qty = $current_place_holder->qty;
        $moving_card_number = $current_place_holder->number;

        // fill the place holder with new content
        $card_number++;
        if ($qty_diff < $qty_of_each_card) {
            $qty = $qty_diff;
            $qty_diff = 0;
        } else {
            $qty_diff -= $qty_of_each_card;
            $qty = $qty_of_each_card;
        }
        $current_place_holder->jid = $jid;
        $current_place_holder->html = Card::generateHtml(
            $jid,
            $qty,
            $card_number,
            $total_shaped >= $qty ?
                100 : round(100 * $total_shaped / $qty, 0),
            $html_template
        );
        $current_place_holder->qty = $qty;
        $current_place_holder->number = Card::PART_TITLE . " " . $card_number;
        $total_shaped -= $qty;

        // Save changes into the in-memory board
        list($changed_items, $planning_board) = saveCard(
            $changed_items,
            (object)['place_holder' => (array)$current_place_holder, 'index_per_day' => $current_place_holder->index_per_day],
            $planning_board,
            false
        );

        $next_card = (object)['place_holder' => (array)$current_place_holder];
        // push next cards forward if needed
        while (!empty($moving_card_html)) {

            // Get the next card to continue
            $next_card = getCardById(
                nextCardId($next_card->place_holder),
                $planning_board
            );

            // End of board
            if (empty($next_card)) {
                break;
            }

            // exchange contents
            $temp_place_holder = new Card(
                isset($next_card->place_holder['jid']) ? $next_card->place_holder['jid'] : "",
                $next_card->place_holder['id'],
                $next_card->place_holder['html'],
                $next_card->place_holder['date'],
                "",
                $next_card->place_holder['number'],
                $next_card->place_holder['qty'],
                $next_card->place_holder['workstation'],
                $next_card->index_per_day
            );
            fillCard($next_card, $moving_card_jid, $moving_card_html, $moving_card_qty, $moving_card_number);
            list($changed_items, $planning_board) = saveCard($changed_items, $next_card, $planning_board, false);

            if (isset($temp_place_holder->jid)) {
                $moving_card_jid = $temp_place_holder->jid;
            }
            $moving_card_html = $temp_place_holder->html;
            $moving_card_qty = $temp_place_holder->qty;
            $moving_card_number = $temp_place_holder->number;

        }

        // Get next place_holder to fetch any changes
        $next_moving_card = getCardById(
            nextCardId((array)$current_place_holder),
            $planning_board
        );
        $current_place_holder = new Card(
            isset($next_moving_card->place_holder['jid']) ? $next_moving_card->place_holder['jid'] : "",
            $next_moving_card->place_holder['id'],
            $next_moving_card->place_holder['html'],
            $next_moving_card->place_holder['date'],
            "",
            $next_moving_card->place_holder['number'],
            $next_moving_card->place_holder['qty'],
            $next_moving_card->place_holder['workstation'],
            $next_moving_card->index_per_day
        );

    }

    updatePlanningBoard($planning_board, $changed_items, true);
}

/**
 * @param array $changed_items
 * @param $card
 * @param $planning_board
 * @param $persist_planning_board
 * @return array
 */


/**
 * @param $new_jid
 * @param $card
 * @param $new_html
 * @param $new_qty
 * @param $new_number
 */


/**
 *
 * @param $qty_diff int number of item that should be removed
 * @param process_id_cards the chain of process cards
 * @param $planning_board array whole board
 */
function popPreviousCards($qty_diff, $process_id_cards, $planning_board)
{
    // There is not any item to remove
    if (empty($qty_diff) || empty($process_id_cards)) {
        return;
    }

    $changed_items = array();

    // try to decrease cards from last
    for ($i = count($process_id_cards) - 1; $i >= 0 && $qty_diff > 0; $i--) {

        $latest_place_holder = $process_id_cards[$i];

        $current_place_holder = getCardById(
            $latest_place_holder['id'],
            $planning_board
        );

        if (strpos($current_place_holder->place_holder['html'], ' checked3="checked"') ||
            strpos($current_place_holder->place_holder['html'], ' job fill halfcheck')) {
            // could not decrease already shaped card
            return;
        }

        $place_holder_qty = $current_place_holder->place_holder['qty'];
        $current_place_holder->place_holder['qty'] -= $qty_diff;

        if ($current_place_holder->place_holder['qty'] <= 0) {
            // The card should be removed
            $qty_diff -= $place_holder_qty;

            $next_moving_card = getCardById(
                nextCardId($current_place_holder->place_holder),
                $planning_board
            );

            // pop the current place holder
            while (!empty($next_moving_card)) {

                if (isset($next_moving_card->place_holder['jid'])) {
                    $current_place_holder->place_holder['jid'] = $next_moving_card->place_holder['jid'];
                } else if (isset($current_place_holder->place_holder['jid'])) {
                    unset($current_place_holder->place_holder['jid']);
                }
                $current_place_holder->place_holder['html'] = $next_moving_card->place_holder['html'];
                $current_place_holder->place_holder['qty'] = $next_moving_card->place_holder['qty'];
                $current_place_holder->place_holder['number'] = $next_moving_card->place_holder['number'];

                list($changed_items, $planning_board) = saveCard($changed_items, $current_place_holder, $planning_board, false);

                $current_place_holder = $next_moving_card;
                if (!isset($current_place_holder) || empty($current_place_holder->place_holder['html'])) {
                    break;
                }

                $next_moving_card = getCardById(
                    nextCardId($next_moving_card->place_holder),
                    $planning_board
                );

            }

        } else {

            $percentage = 0;
            if (strpos(' checked3="checked"><div class="json d-none">', $current_place_holder->place_holder['html'])) {
                $percentage = 100;
            } else if (preg_match('/job fill halfcheck-([0-9]+)-.*/', $current_place_holder->place_holder['html'], $percentage_matches)) {
                $previous_percentage = $percentage_matches[1];
                if (preg_match('/title="([0-9]+).*/', $current_place_holder->place_holder['html'], $qty_matches)) {
                    $previous_qty = $qty_matches[1];
                    $percentage = ($previous_percentage * $current_place_holder->place_holder['qty']) / $previous_qty;
                    $percentage = $percentage > 1 ? 100 : $percentage * 100;
                }
            }
            $current_place_holder->place_holder['html'] = Card::generateHtml(
                $current_place_holder->place_holder['jid'],
                $current_place_holder->place_holder['qty'],
                str_replace(Card::PART_TITLE, "", $current_place_holder->place_holder['number']),
                $percentage,
                $current_place_holder->place_holder['html']
            );

            // The card should be remained
            list($changed_items, $planning_board) = saveCard($changed_items, $current_place_holder, $planning_board, false);
            break;
        }
    }

    updatePlanningBoard($planning_board, $changed_items, true);
}

/**
 * @param $planning_board
 * @param array $changed_items
 */
function updatePlanningBoard($planning_board, array $changed_items, $persist_planning_board)
{

    // If there is not any item to save
    if (empty($changed_items)) {
        return $planning_board;
    }

    // loop over all board per day
    foreach ($planning_board as $planning_board_day) {

        // if there is any change in this date
        if (isset($changed_items[$planning_board_day->date])) {
            $planning_board_day_place_holder_list = json_decode($planning_board_day->json, true);

            // loop over all changes in this date
            foreach ($changed_items[$planning_board_day->date] as $changed_place_holder) {

                // update the corresponding place holder
                $place_holder = &$planning_board_day_place_holder_list[$changed_place_holder->index_per_day];
                if (isset($changed_place_holder->place_holder['jid'])) {
                    $place_holder['jid'] = $changed_place_holder->place_holder['jid'];
                } else if (isset($place_holder['jid'])) {
                    unset($place_holder['jid']);
                }
                $place_holder['html'] = $changed_place_holder->place_holder['html'];
                $place_holder['qty'] = $changed_place_holder->place_holder['qty'];
                $place_holder['number'] = $changed_place_holder->place_holder['number'];

            }

            $json = json_encode_tr($planning_board_day_place_holder_list);
            $planning_board_day->json = $json;
            if ($persist_planning_board) {
                db("planning-board")
                    ->where("id", $planning_board_day->id)
                    ->update([
                        "json" => $json
                    ]);
            }
        }
    }

    return $planning_board;
}

function nextCardId($current_card)
{
    $new_card_date = $current_card['date'];
    $new_card_workstation = $current_card['workstation'];
    $new_card_shift_name =
        str_replace(
            "-",
            "",
            str_replace(
                $new_card_date,
                "",
                str_replace(
                    strtolower($current_card['workstation']),
                    "",
                    $current_card['id']
                )
            )
        );
    switch ($new_card_shift_name) {
        case "f":
            $new_card_shift_name = "f2";
            break;
        case "f2":
            $new_card_shift_name = "m";
            break;
        case "m":
            $new_card_shift_name = "m2";
            break;
        case "m2":
            $new_card_shift_name = "n";
            break;
        case "n":
            $new_card_shift_name = "n2";
            break;
        case "n2":
            $new_card_shift_name = "f";
            $new_card_date = date('Y-m-d', strtotime('+1 day', strtotime($new_card_date)));
            break;
    }
    return strtolower($new_card_workstation) . "-" . $new_card_date . "-" . $new_card_shift_name;
}

function getCardById($card_id, $planning_board)
{
    $index = 0;
    foreach ($planning_board as $planning_board_day) {
        $index_per_day = 0;
        $planning_board_day_place_holder_list = json_decode($planning_board_day->json, true);
        foreach ($planning_board_day_place_holder_list as $planning_board_day_place_holder) {
            if ($planning_board_day_place_holder['id'] == $card_id) {
                return (object)[
                    "index" => $index,
                    "id" => $planning_board_day->id,
                    "place_holder" => $planning_board_day_place_holder,
                    "index_per_day" => $index_per_day
                ];
            }
            $index_per_day++;
        }
        $index++;
    }
    return null;
}




 
function url_get_contents($url) {
    if (function_exists('curl_exec')){ 
        $conn = curl_init($url);
        curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($conn, CURLOPT_FRESH_CONNECT,  true);
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
        $url_get_contents_data = (curl_exec($conn));
        curl_close($conn);
    }elseif(function_exists('file_get_contents')){
        $url_get_contents_data = file_get_contents($url);
    }elseif(function_exists('fopen') && function_exists('stream_get_contents')){
        $handle = fopen ($url, "r");
        $url_get_contents_data = stream_get_contents($handle);
    }else{
        $url_get_contents_data = false;
    }
	return $url_get_contents_data;
} 
/**
 * SelectorDOM.
 *
 * Persitant object for selecting elements.
 *
 *   $dom = new SelectorDOM($html);
 *   $links = $dom->select('a');
 *   $list_links = $dom->select('ul li a');
 *
 */
function print2($array) {
     ?>
     <pre>
     <?php print_r($array); ?>
     </pre>
     <?php 
}
class SelectorDOM {
  public function __construct($data) {
    if ($data instanceof DOMDocument) {
        $this->xpath = new DOMXpath($data);
    } else {
        $dom = new DOMDocument();
        @$dom->loadHTML($data);
        $this->xpath = new DOMXpath($dom);
    }
  }
  
  public function select($selector, $as_array = true) {
    $elements = $this->xpath->evaluate(selector_to_xpath($selector));
    return $as_array ? elements_to_array($elements) : $elements;
  }
}
/**
 * Select elements from $html using the css $selector.
 * When $as_array is true elements and their children will
 * be converted to array's containing the following keys (defaults to true):
 *
 *  - name : element name
 *  - text : element text
 *  - children : array of children elements
 *  - attributes : attributes array
 *
 * Otherwise regular DOMElement's will be returned.
 */
function select_elements($selector, $html, $as_array = true) {
  $dom = new SelectorDOM($html);
  return $dom->select($selector, $as_array);
}
/**
 * Convert $elements to an array.
 */
function elements_to_array($elements) {
  $array = array();
  for ($i = 0, $length = $elements->length; $i < $length; ++$i)
    if ($elements->item($i)->nodeType == XML_ELEMENT_NODE)
      array_push($array, element_to_array($elements->item($i)));
  return $array;
}
/**
 * Convert $element to an array.
 */
function element_to_array($element) {
  $array = array(
    'name' => $element->nodeName,
    'attributes' => array(),
    'text' => $element->textContent,
    'children' =>elements_to_array($element->childNodes)
    );
  if ($element->attributes->length)
    foreach($element->attributes as $key => $attr)
      $array['attributes'][$key] = $attr->value;
  return $array;
}
/** 
 * Convert $selector into an XPath string.
 */
function selector_to_xpath($selector) {
    // remove spaces around operators
    $selector = preg_replace('/\s*>\s*/', '>', $selector);
    $selector = preg_replace('/\s*~\s*/', '~', $selector);
    $selector = preg_replace('/\s*\+\s*/', '+', $selector);
    $selector = preg_replace('/\s*,\s*/', ',', $selector);
    $selectors = preg_split('/\s+(?![^\[]+\])/', $selector);
    foreach ($selectors as &$selector) {
        // ,
        $selector = preg_replace('/,/', '|descendant-or-self::', $selector);
        // input:checked, :disabled, etc.
        $selector = preg_replace('/(.+)?:(checked|disabled|required|autofocus)/', '\1[@\2="\2"]', $selector);
        // input:autocomplete, :autocomplete
        $selector = preg_replace('/(.+)?:(autocomplete)/', '\1[@\2="on"]', $selector);
        // input:button, input:submit, etc.
        $selector = preg_replace('/:(text|password|checkbox|radio|button|submit|reset|file|hidden|image|datetime|datetime-local|date|month|time|week|number|range|email|url|search|tel|color)/', 'input[@type="\1"]', $selector);
        // foo[id]
        $selector = preg_replace('/(\w+)\[([_\w-]+[_\w\d-]*)\]/', '\1[@\2]', $selector);
        // [id]
        $selector = preg_replace('/\[([_\w-]+[_\w\d-]*)\]/', '*[@\1]', $selector);
        // foo[id=foo]
        $selector = preg_replace('/\[([_\w-]+[_\w\d-]*)=[\'"]?(.*?)[\'"]?\]/', '[@\1="\2"]', $selector);
        // [id=foo]
        $selector = preg_replace('/^\[/', '*[', $selector);
        // div#foo
        $selector = preg_replace('/([_\w-]+[_\w\d-]*)\#([_\w-]+[_\w\d-]*)/', '\1[@id="\2"]', $selector);
        // #foo
        $selector = preg_replace('/\#([_\w-]+[_\w\d-]*)/', '*[@id="\1"]', $selector);
        // div.foo
        $selector = preg_replace('/([_\w-]+[_\w\d-]*)\.([_\w-]+[_\w\d-]*)/', '\1[contains(concat(" ",@class," ")," \2 ")]', $selector);
        // .foo
        $selector = preg_replace('/\.([_\w-]+[_\w\d-]*)/', '*[contains(concat(" ",@class," ")," \1 ")]', $selector);
        // div:first-child
        $selector = preg_replace('/([_\w-]+[_\w\d-]*):first-child/', '*/\1[position()=1]', $selector);
        // div:last-child
        $selector = preg_replace('/([_\w-]+[_\w\d-]*):last-child/', '*/\1[position()=last()]', $selector);
        // :first-child
        $selector = str_replace(':first-child', '*/*[position()=1]', $selector);
        // :last-child
        $selector = str_replace(':last-child', '*/*[position()=last()]', $selector);
        // :nth-last-child
        $selector = preg_replace('/:nth-last-child\((\d+)\)/', '[position()=(last() - (\1 - 1))]', $selector);
        // div:nth-child
        $selector = preg_replace('/([_\w-]+[_\w\d-]*):nth-child\((\d+)\)/', '*/*[position()=\2 and self::\1]', $selector);
        // :nth-child
        $selector = preg_replace('/:nth-child\((\d+)\)/', '*/*[position()=\1]', $selector);
        // :contains(Foo)
        $selector = preg_replace('/([_\w-]+[_\w\d-]*):contains\((.*?)\)/', '\1[contains(string(.),"\2")]', $selector);
        // >
        $selector = preg_replace('/>/', '/', $selector);
        // ~
        $selector = preg_replace('/~/', '/following-sibling::', $selector);
        // +
        $selector = preg_replace('/\+([_\w-]+[_\w\d-]*)/', '/following-sibling::\1[position()=1]', $selector);
        $selector = str_replace(']*', ']', $selector);
        $selector = str_replace(']/*', ']', $selector);
    }
    // ' '
    $selector = implode('/descendant::', $selectors);
    $selector = 'descendant-or-self::' . $selector;
    // :scope
    $selector = preg_replace('/(((\|)?descendant-or-self::):scope)/', '.\3', $selector);
    // $element
    $sub_selectors = explode(',', $selector);
    foreach ($sub_selectors as $key => $sub_selector) {
        $parts = explode('$', $sub_selector);
        $sub_selector = array_shift($parts);
        if (count($parts) && preg_match_all('/((?:[^\/]*\/?\/?)|$)/', $parts[0], $matches)) {
            $results = $matches[0];
            $results[] = str_repeat('/..', count($results) - 2);
            $sub_selector .= implode('', $results);
        }
        $sub_selectors[$key] = $sub_selector;
    }
    $selector = implode(',', $sub_selectors);
    
    return $selector;
} 