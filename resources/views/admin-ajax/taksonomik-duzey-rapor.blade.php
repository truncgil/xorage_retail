<?php $logo = url("assets/img/logo.svg"); 
if(getisset("ogrenci")) {
    $sonuclar = db2("sonuclar")
    ->where("tc_kimlik_no",get("ogrenci"));
    if(postisset("sinav")) {
        $sonuclar =  $sonuclar 
        ->whereIn("title",$_POST['sinav']);
    }
   

    $sonuclar = $sonuclar ->get();
    

} else {
    $sonuc = db2("sonuclar")->where("id",get("id"))->first();
    $analiz = j($sonuc->analiz);
    $key = $sonuc->tc_kimlik_no;
    if($key=="") $key = $sonuc->ogrenci_adi;
    if(!$sonuc) {

        die("Bu bölümü görme yetkiniz yok ya da böyle bir sonuç yok");
    }
}
//print2($sonuclar);
    $dogru = array();
    $yanlis = array();
    $bos = array();
    $checkbox_dersler = array();
    $checkbox_sinavlar = array();
    $checkbox_konular = array();
    $filtrele = false;
    if(postisset("konu")) {
        $filtrele = true;
    }
    if(postisset("ders")) {
        $filtrele = true;
    }
    
    if(getisset("ogrenci")) { //öğrencinin girdiği bütün sınav sonuçlarını analiz etmek için
        $key = get("ogrenci");
        foreach($sonuclar AS $sonuc) {
            $analiz = j($sonuc->analiz);
            foreach($analiz AS $alan => $deger) {
                $ders_title = slug_to_title($alan);
                array_push($checkbox_dersler,$ders_title);
                foreach($deger['kazanim-dogru'] AS $kazanim) {
                    if(!isset($checkbox_konular[$kazanim])) {
                        array_push($checkbox_konular,$kazanim);
                    }
                }
                foreach($deger['kazanim-yanlis'] AS $kazanim) {
                    if(!isset($checkbox_konular[$kazanim])) {
                        array_push($checkbox_konular,$kazanim);
                    }
                }
                foreach($deger['kazanim-bos'] AS $kazanim) {
                    if(!isset($checkbox_konular[$kazanim])) {
                        array_push($checkbox_konular,$kazanim);
                    }
                }
                if($filtrele) {
                    if(in_array($ders_title,$_POST['ders'])) {
                        $say = 0;
                        foreach($deger['tak-dogru'] AS $td_alan ) {
                            if(in_array($deger['kazanim-dogru'][$say],$_POST['konu'])) {
                                if(!isset($dogru[$td_alan])) $dogru[$td_alan] = 0;
                                $dogru[$td_alan]++;
                                $say++;
                            }
                        }
                    
                        $say = 0;
                        foreach($deger['tak-yanlis'] AS $td_alan ) {
                            if(in_array($deger['kazanim-yanlis'][$say],$_POST['konu'])) {
                                if(!isset($yanlis[$td_alan])) $yanlis[$td_alan] = 0;
                                $yanlis[$td_alan]++;
                                $say++;
                            }
                        }
                   
                        $say = 0;
                        foreach($deger['tak-bos'] AS $td_alan ) {
                            if(in_array($deger['kazanim-bos'][$say],$_POST['konu'])) {
                                if(!isset($bos[$td_alan])) $bos[$td_alan] = 0;
                                $bos[$td_alan]++;
                                $say++;
                            }
                        }
                    }
                } else {
                 
                    foreach($deger['tak-dogru'] AS $td_alan ) {
                        if(!isset($dogru[$td_alan])) $dogru[$td_alan] = 0;
                        $dogru[$td_alan]++;
                    }
                    foreach($deger['tak-yanlis'] AS $td_alan ) {
                        if(!isset($yanlis[$td_alan])) $yanlis[$td_alan] = 0;
                        $yanlis[$td_alan]++;
                    }
                    foreach($deger['tak-bos'] AS $td_alan ) {
                        if(!isset($bos[$td_alan])) $bos[$td_alan] = 0;
                        $bos[$td_alan]++;
                    }
                }
            }

        }
    } else {
        foreach($analiz AS $alan => $deger) {
            $ders_title = slug_to_title($alan);
            array_push($checkbox_dersler,$ders_title);
            if(postisset("ders")) {
                if(in_array($ders_title,$_POST['ders'])) {
                    foreach($deger['kazanim-dogru'] AS $kazanim) {
                        if(!isset($checkbox_konular[$kazanim])) {
                            array_push($checkbox_konular,$kazanim);
                        }
                    }
                    foreach($deger['kazanim-yanlis'] AS $kazanim) {
                        if(!isset($checkbox_konular[$kazanim])) {
                            array_push($checkbox_konular,$kazanim);
                        }
                    }
                    foreach($deger['kazanim-bos'] AS $kazanim) {
                        if(!isset($checkbox_konular[$kazanim])) {
                            array_push($checkbox_konular,$kazanim);
                        }
                    }
                }
            } else {
                foreach($deger['kazanim-dogru'] AS $kazanim) {
                    if(!isset($checkbox_konular[$kazanim])) {
                        array_push($checkbox_konular,$kazanim);
                    }
                }
                foreach($deger['kazanim-yanlis'] AS $kazanim) {
                    if(!isset($checkbox_konular[$kazanim])) {
                        array_push($checkbox_konular,$kazanim);
                    }
                }
                foreach($deger['kazanim-bos'] AS $kazanim) {
                    if(!isset($checkbox_konular[$kazanim])) {
                        array_push($checkbox_konular,$kazanim);
                    }
                }
            }
            
        
                
           
            if($filtrele) {
                if(in_array($ders_title,$_POST['ders'])) {
                    $say = 0;
                    foreach($deger['tak-dogru'] AS $td_alan ) {
                        if(in_array($deger['kazanim-dogru'][$say],$_POST['konu'])) {
                            if(!isset($dogru[$td_alan])) $dogru[$td_alan] = 0;
                            $dogru[$td_alan]++;
                            $say++;
                        }
                    }
                
                    $say = 0;
                    foreach($deger['tak-yanlis'] AS $td_alan ) {
                        if(in_array($deger['kazanim-yanlis'][$say],$_POST['konu'])) {
                            if(!isset($yanlis[$td_alan])) $yanlis[$td_alan] = 0;
                            $yanlis[$td_alan]++;
                            $say++;
                        }
                    }
               
                    $say = 0;
                    foreach($deger['tak-bos'] AS $td_alan ) {
                        if(in_array($deger['kazanim-bos'][$say],$_POST['konu'])) {
                            if(!isset($bos[$td_alan])) $bos[$td_alan] = 0;
                            $bos[$td_alan]++;
                            $say++;
                        }
                    }
                }
            } else {
             
                foreach($deger['tak-dogru'] AS $td_alan ) {
                    if(!isset($dogru[$td_alan])) $dogru[$td_alan] = 0;
                    $dogru[$td_alan]++;
                }
                foreach($deger['tak-yanlis'] AS $td_alan ) {
                    if(!isset($yanlis[$td_alan])) $yanlis[$td_alan] = 0;
                    $yanlis[$td_alan]++;
                }
                foreach($deger['tak-bos'] AS $td_alan ) {
                    if(!isset($bos[$td_alan])) $bos[$td_alan] = 0;
                    $bos[$td_alan]++;
                }
            }
            
        }
    }

  //  print2($checkbox_konular);
 //   print2($checkbox_dersler);
    /*
    print2($dogru);
    print2($yanlis);
    print2($bos);
    
    */
   // print2($_POST['ders']);
    $col = explode(",","A,B,C,D")
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php bootstrap(); ?>
    <style>
        .progress {
            height: 100px;
            background:white;
        }
        td {
            padding:0px !important;

        }
        th {
            vertical-align:middle
        }
        @media print {
            .print-none {
                display:none;
            }
            .print-100 {
                width:100%;
            }
        }
    </style>
<?php $ogrenci = ogrenci($key); 
$ogrenci_title = @$ogrenci->title;
?>
    <title>{{$ogrenci_title}} Taksonomik Düzey Raporu</title>
</head>

<body>
<nav class="navbar navbar-light bg-light navbar-expand-lg print-none">
  <a class="navbar-brand" href="#">
        <img src="{{$logo}}" style="width:200px;margin:0 10px;" alt="">
  </a>
  <strong>{{$ogrenci_title}} {{e2("Taksonomik Analiz Raporu")}}</strong>
  
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <form action="" method="post">
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">Yazdır</a>
      </li>
  
      <li class="nav-item">
       
       
      </li>
      <li class="nav-item">
        
      </li>
      
    
    </ul>
 
  </div>
  </form>
</nav>
    <div class="container-fluid p-5">
        <div class="row">
            <div class="col-2 print-none">
                <div class="btn-group">
                    <button class="btn btn-danger deselect">Tüm Seçimi Kaldır</button>
                    <button class="btn btn-success select-all">Tümünü Seç</button>
                </div>
                <script>
                $(function(){
                $(".select-all").click(function () {
                    $(".select2 > option").prop("selected", true);
                    $(".select2").trigger("change"); 
                });
                    $(".deselect").click(function () {
                        $(".select2").each(function () { //added a each loop here
                            $(this).val('').change();
                        });
                    }); 
                });
                </script>
                <form action="" method="post">
                <br>
                <div class="btn-group">
                    <button class="btn btn-block btn-primary">Sonucu Göster</button>
                    <a href="" class="btn btn-danger">Sonucu Sıfırla</a>
                </div>
                
                <?php //print2($_POST) ?>
                {{csrf_field()}}
                <?php if(isset($sonuclar)) { ?>
                    {{e2("Sınavlar")}}: <br>
                  
                    <select name="sinav[]" id="" class="select2 w-100" multiple>
                
                      
                        <?php foreach($sonuclar AS $sinav) { ?>
                            <option value="{{$sinav->title}}" 
                            <?php if(postisset("sinav")) {
                                if(in_array($sinav->title,$_POST['sinav'])) {
                                    echo "selected";
                                }
                            } else {
                                echo "selected";
                            } ?>
                            >
                            {{$sinav->title}}</option>
                        <?php } ?>
                    </select>
                    <?php } ?>
                    {{e2("Dersler")}}: <br>
                    <select name="ders[]" id="" class="select2 w-100" multiple>
                
                      
                    <?php foreach($checkbox_dersler AS $ders) { ?>
                            <option value="{{$ders}}" 
                            <?php if(postisset("ders")) {
                                if(in_array($ders,$_POST['ders'])) {
                                    echo "selected";
                                }
                            } else {
                                echo "selected";
                            } ?>
                            >{{$ders}}</option>
                        <?php } ?>
                    </select>
                    {{e2("Konular")}}: <br>
                  <?php $say = 0; ?>
                    <select name="konu[]" id="konu" class="select2 w-100" multiple>
                   
                        <?php foreach($checkbox_konular AS $konu) { ?>
                            <option value="{{$konu}}" 
                            <?php if(postisset("konu")) {
                                if(in_array($konu,$_POST['konu'])) {
                                    echo "selected";
                                    $say++;
                                }
                            } else {
                                echo "selected";
                                $say++;
                            } ?>
                            >{{$konu}}</option>
                        <?php } ?>
                    </select>
                    <br>
<script>
$(function(){
    <?php if($say==0) {
         ?>
         $("#konu > option").prop("selected", true);
                    $("#konu").trigger("change"); 
                    $("form").submit();
         <?php 
    } ?>
});
</script>
                    <button class="btn btn-primary btn-block">Sonucu Göster</button>
                </form>
            </div>
            <div class="col-10">
               
                <table class="table text-center table-bordered">
                    <?php for($row_k=0;$row_k<4;$row_k++) { ?>
                    <?php if($row_k==0) { ?>
                    <tr>
                        <th></th>
                        <?php for($col_k=1;$col_k<=6;$col_k++) { ?>
                        <th>{{$col_k}}</th>
                        <?php } ?>
                    </tr>
                    <?php } ?>
                    <tr>
                        <th>{{$col[$row_k]}}</th>
                        <?php for($col_k=1;$col_k<=6;$col_k++) { ?>

                        <td>


                            <?php 
                            
                            $key = $col[$row_k] . $col_k;
                            if(!isset($dogru[$key])) $dogru[$key] =0;
                            if(!isset($yanlis[$key])) $yanlis[$key] = 0;
                            if(!isset($bos[$key])) $bos[$key] = 0; 
                            $toplam = $dogru[$key] + $yanlis[$key] + $bos[$key]; 
                            if($dogru[$key]==0) {
                                $dogru_yuzde = 0;
                            } else {
                                $dogru_yuzde = round(100*$dogru[$key] / $toplam,2);
                            }
                            if($bos[$key]==0) {
                                $bos_yuzde = 0;
                            } else {
                                $bos_yuzde = round(100*$bos[$key] / $toplam,2);
                            }
                            if($yanlis[$key]==0) {
                                $yanlis_yuzde = 0;
                            } else {
                                $yanlis_yuzde = round(100*$yanlis[$key] / $toplam,2);
                            }
                            
                           
                            ?>

                            <div class="progress">
                            <div class="progress-bar bg-danger" style="width:{{$yanlis_yuzde}}%">

                                    %{{$yanlis_yuzde}}
                                </div>
                                <div class="progress-bar bg-warning" style="width:{{$bos_yuzde}}%">

                                    %{{$bos_yuzde}}
                                </div>
                                <div class="progress-bar bg-success" style="width:{{$dogru_yuzde}}%">

                                    %{{$dogru_yuzde}}


                                </div>
                                
                                
                            </div>

                        </td>
                        <?php } ?>
                    </tr>
                    <?php } ?>
                </table>
                <div style="width:300px;float:right;">
                    <div class="progress">
                    <div class="progress-bar bg-danger" style="width:33.3%">
                            {{e2("Yanlış")}}
                        </div>
                        <div class="progress-bar bg-warning" style="width:33.3%">
                            {{e2("Boş")}}
                        </div>
                        <div class="progress-bar bg-success" style="width:33.3%">
                            {{e2("Doğru")}}


                        </div>
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>