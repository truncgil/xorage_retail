<?php 

    $s = db("sinavlar")->where("id",get("id"))->first();
    $ders = j($s->dersler);
    $kazanim = j($s->kazanimlar);
    $dosya_adi = "{$s->title} Kazanım ve Cevaplar";
  //  print_r($kazanim);
  header('Content-Type: text/html; charset=utf-8');
  header('Content-type: application/vnd.ms-excel');
  header("Content-Disposition: attachment; filename=$dosya_adi.xls");  //File name extension was wrong

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
        <title>Excel Kazanım</title>
    </head>
    <body>
        
    
    
<table border="1">
    <tr>
        <th>Test Adı</th>
        <th>A Soru No</th>
        <th>B Soru No</th>
        <th>Doğru Cevap	</th>
        <th>Kazanım Kodu veya Kazanım Metni	</th>
        <th>Taksonomik Düzey	</th>
        <th>C Soru No	</th>
        <th>D Soru No</th>

    </tr>
    <?php foreach($ders AS $d) {
                     ?>
                     <h3></h3>
                     <?php $soru_sayi = $d['soru']; 
                     $sik = explode(",","A,B,C,D,E,X");
                     $tak_list = tak_list();
                     $slug = str_slug($d['isim']);
                     ?>
    <?php for($k=1;$k<=$soru_sayi;$k++) {
        $cevap = NULL;
        if(isset($kazanim[$slug.'_cevap_'.$k])) {
            $cevap = $kazanim[$slug.'_cevap_'.$k];
        }
                          ?>
    <tr>
        <td>{{$d['isim']}}</td>
        <td>{{$k}}</td>
        <td>{{@$kazanim[$slug.'_b_soru_no_'.$k]}}</td>
        <td>
        <?php 
        if(is_array($cevap)) {
            foreach($cevap AS $c) {
                echo $c;
            } 
        }
        ?>
        </td>
        <td>{{@$kazanim[$slug.'_kazanim_'.$k]}}</td>
        <td>{{@$kazanim[$slug.'_tak_'.$k]}}</td>
        <td>{{@$kazanim[$slug.'_c_soru_no_'.$k]}}</td>
        <td>{{@$kazanim[$slug.'_d_soru_no_'.$k]}}</td>
    </tr>
    <?php } ?>
    <?php } ?>
</table>
</body>
    </html>
<?php exit(); ?>