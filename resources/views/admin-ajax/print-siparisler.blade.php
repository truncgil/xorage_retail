<?php 
$y = 0;
if(getisset("y")) $y = get("y");
$siparisler = db("siparisler")->where("kid",get("id"))
->where("y",$y)
->orderBy("id","DESC")->get();
$urunler = contents_to_array("Ürünler");
$musteri = c(get("id"));
$stok_cikis_sayim = stok_cikis_sayim();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$musteri->title}} Açık Siparişler</title>	
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <style>
        body {
            background:white;
        }
        .print {
            width:1000px;
            border:solid 1px #c6c6c6;
            padding:10px;
            margin:0 auto;
        }
    </style>
</head>
<body>
    <div class="print text-center">
    <table class="table text-center">
            <tr>
                <td>
                    <img src="{{url("logo.svg")}}" class="m-5" width="300" alt="">
                </td>
                <td style="vertical-align:middle">
                        <h1>{{$musteri->title}} 
                         <small><br>{{e2("Açık Siparişler")}}</small>
                </td>
            </tr>
        </table>
        
        </h1>
        <table class="table">
            <tr>
                <th>Sipariş Kodu</th>
                <th>Ürün Adı</th>
                <th>Ürün Özellikleri</th>
                <th>Sipariş Notları</th>
                <th>Sipariş Miktarı</th>
                <th>Sevk Edilen</th>
                <th>Kalan Miktar</th>
                <th>Termin Tarihi</th>
            </tr>
            <?php foreach($siparisler AS $s)  { 
              ?>
               <?php 
                                $urun = $urunler[$s->type];
                                $j = j($s->json);
                                $stok_cikis = 0;
                                if(isset($stok_cikis_sayim[$s->id])) $stok_cikis = $stok_cikis_sayim[$s->id];
                            ?>
             <tr>
                 <td>{{$s->id}}</td>
                 <td>{{$urun->title}}</td>
                 <td>
                   <?php foreach($j AS $alan => $deger) {
                       if($alan!="qty") {
                        ?>
                        <div class="badge badge-default">{{alan_text($alan)}}: {{$deger}}</div>
                        <?php 
                       }
                   } ?>
                 </td>
                 <td>{{$urun->html}}</td>
                 <td>{{$s->qty}}</td>
                 <td>{{$stok_cikis}}</td>
                 <td>{{$s->qty - $stok_cikis}}</td>
                 <td>{{date("d.m.Y",strtotime($s->date))}}</td>
             </tr> 
             <?php } ?>
        </table>
    </div>
</body>
</html>
<script>
    <?php if(!getisset("noprint")) {
         ?>
         window.print();
         <?php 
    } ?>
</script>


